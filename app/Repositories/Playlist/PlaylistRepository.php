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
            foreach($playlist['activities'] as $activity){
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
     * @param Request $request
     * @param Project $project
     * @param Playlist $playlist
     */
    public function clone(Request $request, Project $project, Playlist $playlist)
    {
        $play_list_data = [
            'title' => $playlist->title,
            'order' => $playlist->order,
            'is_public' => $playlist->is_public
        ];
        $token = $request->bearerToken();
        $cloned_playlist = $project->playlists()->create($play_list_data);

        $activities = $playlist->activities;
        foreach ($activities as $activity) {
            $h5P_res = null;
            if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0) {
                $h5P_res = $this->activityRepository->download_and_upload_h5p($token, $activity->h5p_content_id);
            }

            $new_thumb_url = config('app.default_thumb_url');
            if (Storage::disk('public')->exists('projects/' . basename($activity->thumb_url)) && is_file(storage_path('app/public/projects/' . basename($activity->thumb_url)))) {
                $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
                $new_image_name_mtd = uniqid() . '.' . $ext;
                ob_start();
                \File::copy(storage_path('app/public/projects/' . basename($activity->thumb_url)), storage_path('app/public/projects/' . $new_image_name_mtd));
                ob_get_clean();
                $new_thumb_url = '/api/storage/projects/' . $new_image_name_mtd;
            }
            $activity_data = [
                'title' => $activity->title,
                'type' => $activity->type,
                'content' => $activity->content,
                'playlist_id' => $cloned_playlist->id,
                'order' => $activity->order,
                'h5p_content_id' => $h5P_res === null ? 0 : $h5P_res->id,
                'thumb_url' => $new_thumb_url,
                'subject_id' => $activity->subject_id,
                'education_level_id' => $activity->education_level_id,
            ];

            $cloned_activity = $this->activityRepository->create($activity_data);
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
        return $this->model->where('id', $playlist->id)
            ->with('project')
            ->first();
    }
}
