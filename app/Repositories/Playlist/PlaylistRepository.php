<?php

namespace App\Repositories\Playlist;

use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Illuminate\Support\Collection;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Storage;
use Illuminate\Http\Request;

class PlaylistRepository extends BaseRepository implements PlaylistRepositoryInterface
{

    private $activityRepository;

    /**
     * PlaylistRepository constructor.
     *
     * @param Playlist $model
     */
    public function __construct(Playlist $model, ActivityRepositoryInterface $activityRepository)
    {
        parent::__construct($model);
        $this->activityRepository = $activityRepository;
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
        }
    }
    /**
     * To Clone Playlist and associated activites
     * @param Request $request
     * @param Project $project
     * @param Playlist $playlist
     */
    public function clone(Request $request, Project $project, Playlist $playlist)
    {
        $play_list_data = ['title' => $playlist->title,
            'order' => $playlist->order,
            'is_public' => $playlist->is_public
        ];
        $token =  $request->bearerToken();
        $cloned_playlist = $project->playlists()->create($play_list_data);

        $activites = $playlist->activities;
        foreach ($activites as $activity) {
            $h5P_res = null;
            if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0) {
                $h5P_res = $this->activityRepository->download_and_upload_h5p($token, $activity->h5p_content_id);
            }
                
            $new_thumb_url = config('app.default_thumb_url');
            if (Storage::disk('public')->exists('projects/' . basename($activity->thumb_url)) && is_file(storage_path("app/public/projects/" . basename($activity->thumb_url)))) {
                $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
                $new_image_name_mtd = uniqid() . '.' . $ext;
                ob_start();
                \File::copy(storage_path("app/public/projects/" . basename($activity->thumb_url)), storage_path("app/public/projects/" . $new_image_name_mtd));
                ob_get_clean();
                $new_thumb_url = "/storage/projects/" . $new_image_name_mtd;
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

}
