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
        
        $this->activityRepository->download_and_upload_h5p('30'); die('26');
        $authenticated_user = auth()->user();
        $data = [
            'name' => $project->name,
            'description' => $project->description,
            'thumb_url' => $project->thumb_url,
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
                
                
                
                $h5p = App::make('LaravelH5p');
                $core = $h5p::$core;
                $editor = $h5p::$h5peditor;
                $content = $h5p->load_content($id);
                
                $activity_data = [
                    'title' => $activity->title,
                    'type' => $activity->type,
                    'content' => $activity->content,
                    'playlist_id' => $cloned_playlist->id,
                    'order' => $activity->order,
                    'h5p_content_id' => $activity->h5p_content_id,
                    'thumb_url' => $activity->thumb_url,
                    'subject_id' => $activity->subject_id,
                    'education_level_id' => $activity->education_level_id,
                ];

                $cloned_activity = $this->activityRepository->create($activity_data);
            }
        }
    }

}
