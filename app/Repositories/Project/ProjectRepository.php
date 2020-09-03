<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Support\Collection;
use App\Repositories\Activity\ActivityRepositoryInterface;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface {
    
    private $activityRepository;
    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     */
    public function __construct(Project $model,ActivityRepositoryInterface $activityRepository) {
        $this->activityRepository = $activityRepository;
        parent::__construct($model);
    }

    public function clone(Project $project) {
        
        
        $authenticated_user = auth()->user();
        
        
        if( Storage::disk('public')->exists( 'uploads/'.basename($project->thumb_url) ) && is_file(storage_path("app/public/uploads/".basename($project->thumb_url))) ){
                $ext = pathinfo(basename($project->thumb_url), PATHINFO_EXTENSION);
                $new_image_name = uniqid().'.'.$ext;
                ob_start();                
                \File::copy(storage_path("app/public/uploads/".basename($project->thumb_url)) , storage_path("app/public/uploads/".$new_image_name) );                
                ob_get_clean();
                $new_image_url = "/storage/uploads/".$new_image_name;
                
            } 
        $data = [
            'name' => $project->name,
            'description' => $project->description,
            'thumb_url' => $new_image_url,
            //'shared' => $project->shared,
            'starter_project' => $project->starter_project,
            'is_public' => $project->is_public,
        ];

        $clonned_project = $authenticated_user->projects()->create($data, ['role' => 'owner']);
        if (!$clonned_project) {
            return response([
                'errors' => ['Could not create project. Please try again later.'],
                    ], 500);
        }
        
        $playlists = $project->playlists;
        foreach ($playlists as $playlist) {
            $play_list_data = ['title' => $playlist->title,
                'order' => $playlist->order,
                'is_public' => $playlist->is_public
            ];
            $cloned_playlist = $clonned_project->playlists()->create($play_list_data);

            $activites = $playlist->activities;
            foreach ($activites as $activity) {
                
                $h5P_res = $this->activityRepository->download_and_upload_h5p('19356');
                $new_thumb_url = '';
                if( Storage::disk('public')->exists( 'uploads/'.basename($activity->thumb_url) ) && is_file(storage_path("app/public/uploads/".basename($activity->thumb_url))) ){
                        $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
                        $new_image_name_mtd = uniqid().'.'.$ext;
                        ob_start();
                        \File::copy(storage_path("app/public/uploads/".basename($activity->thumb_url)) , storage_path("app/public/uploads/".$new_image_name_mtd) );                
                        ob_get_clean();
                        $new_thumb_url = "/storage/uploads/".$new_image_name_mtd;                        
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

}
