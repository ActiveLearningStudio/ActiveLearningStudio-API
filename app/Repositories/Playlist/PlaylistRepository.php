<?php

namespace App\Repositories\Playlist;

use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Illuminate\Support\Collection;
use App\Repositories\Activity\ActivityRepositoryInterface;

class PlaylistRepository extends BaseRepository implements PlaylistRepositoryInterface {

    private $activityRepository;

    /**
     * PlaylistRepository constructor.
     *
     * @param Playlist $model
     */
    public function __construct(Playlist $model, ActivityRepositoryInterface $activityRepository) {
        $this->activityRepository = $activityRepository;
        parent::__construct($model);
    }

    /**
     * Get latest order of playlist for Project
     *
     * @param Project $project
     * @return int
     */
    public function getOrder(Project $project) {
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
    public function saveList(array $playlists) {
        foreach ($playlists as $playlist) {
            $this->update([
                'order' => $playlist['order'],
                    ], $playlist['id']);
        }
    }

    public function clone(Project $project, Playlist $playlist) 
    {
        
        $play_list_data = ['title' => $playlist->title,
            'order' => $playlist->order,
            'is_public' => $playlist->is_public
        ];
        $cloned_playlist = $project->playlists()->create($play_list_data);

        $activites = $playlist->activities;
        foreach ($activites as $activity) {
            $h5P_res = Null;
            if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0){
                $h5P_res = $this->activityRepository->download_and_upload_h5p($activity->h5p_content_id);
            }
                
            $new_thumb_url = '';
            if (Storage::disk('public')->exists('uploads/' . basename($activity->thumb_url)) && is_file(storage_path("app/public/uploads/" . basename($activity->thumb_url)))) {
                $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
                $new_image_name_mtd = uniqid() . '.' . $ext;
                ob_start();
                \File::copy(storage_path("app/public/uploads/" . basename($activity->thumb_url)), storage_path("app/public/uploads/" . $new_image_name_mtd));
                ob_get_clean();
                $new_thumb_url = "/storage/uploads/" . $new_image_name_mtd;
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
