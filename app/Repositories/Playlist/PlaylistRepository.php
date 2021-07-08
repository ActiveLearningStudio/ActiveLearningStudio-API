<?php

namespace App\Repositories\Playlist;

use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Djoudi\LaravelH5p\Eloquents\H5pContent;
use Illuminate\Support\Collection;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PlaylistRepository extends BaseRepository implements PlaylistRepositoryInterface
{

    private $activityRepository;

    /**
     * PlaylistRepository constructor.
     *
     * @param Playlist $model
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(Playlist $model, ActivityRepositoryInterface $activityRepository)
    {
        parent::__construct($model);

        $this->activityRepository = $activityRepository;
    }

    /**
     * Update model in storage
     *
     * @param array $attributes
     * @param $id
     * @return Model
     */
    public function update(array $attributes, $id)
    {
        $is_updated = $this->model->where('id', $id)->update($attributes);

        if ($is_updated) {
            $this->model->where('id', $id)->searchable();
        }

        return $is_updated;
    }

    /**
     * Get latest order of playlist for Project
     *
     * @param Project $project
     * @return int
     */
    public function getOrder(Project $project)
    {
        $playlist = $this->model->where('project_id', $project->id)
            ->orderBy('order', 'desc')
            ->first();

        return ($playlist && $playlist->order) ? $playlist->order : 0;
    }

    /**
     * Save Playlist array
     *
     * @param array $playlists
     */
    public function saveList(array $playlists)
    {
        foreach ($playlists as $playlist) {
            $this->update([
                'order' => $playlist['order'],
            ], $playlist['id']);

            // Reorder activities
            foreach ($playlist['activities'] as $activity) {
                $act = Activity::find($activity['id']);
                $act->order = $activity['order'];
                $act->playlist_id = $playlist['id'];
                $act->save();
            }
        }
    }

    /**
     * To Clone Playlist and associated activities
     *
     * @param Project $project
     * @param Playlist $playlist
     * @param string $token
     */
    public function clone(Project $project, Playlist $playlist, string $token)
    {
        $isDuplicate = ($playlist->project_id == $project->id);

        if ($isDuplicate) {
            Playlist::where('project_id', $project->id)->where('order', '>', $playlist->order)->increment('order', 1);
        }
        $play_list_data = [
            'title' => ($isDuplicate) ? $playlist->title."-COPY" : $playlist->title,
            'order' => ($isDuplicate) ? $playlist->order + 1 : $playlist->order,
        ];

        $cloned_playlist = $project->playlists()->create($play_list_data);

        $activities = $playlist->activities;
        foreach ($activities as $activity) {
            $cloned_activity = $this->activityRepository->clone($cloned_playlist, $activity, $token);
        }
    }

    /**
     * Get Playlists for Preview
     *
     * @param Playlist $playlist
     * @return array
     */
    public function getPlaylistForPreview(Playlist $playlist)
    {
        $project = $playlist->project;

        $plist = [];
        $plist['id'] = $playlist->id;
        $plist['title'] = $playlist->title;
        $plist['project_id'] = $playlist->project_id;
        $plist['created_at'] = $playlist->created_at;
        $plist['updated_at'] = $playlist->updated_at;

        $plist['project'] = $project;
        $plist['activities'] = [];

        $count = 0;
        foreach ($project->playlists as $p) {
            $list = $this->find($p->id)->toArray();
            $plist['project']['playlists'][$count++] = $list;
        }

        foreach ($playlist->activities as $act) {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($act->h5p_content_id);
            $library = $content['library'] ? $content['library']['name'] : '';

            $plistActivity = [];
            $plistActivity['id'] = $act->id;
            $plistActivity['type'] = $act->type;
            $plistActivity['h5p_content_id'] = $act->h5p_content_id;
            $plistActivity['title'] = $act->title;
            $plistActivity['library_name'] = $library;
            $plistActivity['created_at'] = $act->created_at;
            $plistActivity['shared'] = isset($act->shared) ? $act->shared : false;
            $plistActivity['thumb_url'] = $act->thumb_url;

            $plist['activities'][] = $plistActivity;
        }

        return $plist;
    }

    /**
     * Get Playlists for Preview
     *
     * @param Playlist $playlist
     * @return array
     */
    public function getPlaylistWithProject(Playlist $playlist)
    {
        return $this->model::whereHas('project')
            ->where('id', $playlist->id)
            ->with('project')
            ->first();
    }

    /**
     * To Populate missing order number, One time script
     */
    public function populateOrderNumber()
    {
        $projects = Project::all();
        foreach($projects as $project) {
            $playlists = $project->playlists()->whereNull('order')->orderBy('created_at')->get();
            if(!empty($playlists)) {
                $order = 1;
                foreach($playlists as $playlist) {
                    $playlist->order = $order;
                    $playlist->save();
                    $order++;
                }
            }
        }
    }

    /**
     * To Import Playlist and associated activities
     *
     * @param Project $project
     * @param string $authUser
     * @param string $extracted_folder
     * @param string $playlist_dir
     */
    public function playlistImport(Project $project, $authUser, $extracted_folder, $playlist_dir="")
    {
        $playlist_json = file_get_contents(storage_path($extracted_folder . '/playlists/'.$playlist_dir.'/'.$playlist_dir.'.json'));
        
        $playlist = json_decode($playlist_json,true);

        unset($playlist['id'], $playlist['project_id']);
        
        $cloned_playlist = $project->playlists()->create($playlist); // create playlist
        if (file_exists(storage_path($extracted_folder . '/playlists/' . $playlist_dir . '/activities/'))) {
            $activitity_directories = scandir(storage_path($extracted_folder . '/playlists/' . $playlist_dir . '/activities/'));
        
            for ($j=0; $j<count($activitity_directories); $j++) { // loop through all activities
                if($activitity_directories[$j] == '.' || $activitity_directories[$j] == '..') continue;
                $cloned_activity = $this->activityRepository->importActivity($cloned_playlist, $authUser, $playlist_dir, $activitity_directories[$j], $extracted_folder);
            }
        }
    }
}
