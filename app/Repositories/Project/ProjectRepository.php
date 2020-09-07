<?php

namespace App\Repositories\Project;

use App\Models\Project;
use Storage;
use App\Repositories\BaseRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Support\Collection;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\CurrikiGo\LmsSetting;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface 
{
    
    private $activityRepository;
    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     */
    public function __construct(Project $model, ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
        parent::__construct($model);
    }
    
    /**
     * To clone project and associated playlists
     * @param Request $request
     * @param Project $project
     * @return type
     */
    public function clone(Request $request, Project $project)
    {
        $authenticated_user = auth()->user();
        $token =  $request->bearerToken();
        $new_image_url = config('app.default_thumb_url');
        if (Storage::disk('public')->exists( 'projects/'.basename($project->thumb_url) ) && is_file(storage_path("app/public/projects/".basename($project->thumb_url)))) {
                $ext = pathinfo(basename($project->thumb_url), PATHINFO_EXTENSION);
                $new_image_name = uniqid().'.'.$ext;
                ob_start();                
                \File::copy(storage_path("app/public/projects/".basename($project->thumb_url)), storage_path("app/public/projects/".$new_image_name));                
                ob_get_clean();
                $new_image_url = "/storage/projects/".$new_image_name;
                
        } 
        $data = [
            'name' => $project->name,
            'description' => $project->description,
            'thumb_url' => $new_image_url,
            'shared' => $project->shared,
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
                $h5P_res = null;
                if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0) { 
                    $h5P_res = $this->activityRepository->download_and_upload_h5p($token, $activity->h5p_content_id);
                }
                $new_thumb_url = config('app.default_thumb_url');
                if (Storage::disk('public')->exists( 'projects/'.basename($activity->thumb_url) ) && is_file(storage_path("app/public/projects/".basename($activity->thumb_url)))) {
                        $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
                        $new_image_name_mtd = uniqid().'.'.$ext;
                        ob_start();
                        \File::copy(storage_path("app/public/projects/".basename($activity->thumb_url)) , storage_path("app/public/projects/".$new_image_name_mtd));                
                        ob_get_clean();
                        $new_thumb_url = "/storage/projects/".$new_image_name_mtd;                        
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


    /**
     * To fetch project based on LMS settings
     * @param $lms_url
     * @param $lti_client_id
     * @return Project $project
     */
    public function fetchByLmsUrlAndLtiClient($lms_url, $lti_client_id)
    {
        $projects = $this->model->whereHas('users', function ($query_user) use ($lms_url, $lti_client_id) {
                                    $query_user->whereHas('lmssetting', function($query_lmssetting) use ($lms_url, $lti_client_id){
                                        $query_lmssetting->where('lms_url', $lms_url)->where('lti_client_id', $lti_client_id);
                                    });
                                })->get();
        return $projects;
    }


    /**
     * To fetch project based on LMS settings
     * @param Project $project
     */
    public function getProjectForPreview(Project $project)
    {
        $project = Project::where(['id'=> $project->id])
            ->with('playlists.activities')
            ->first();

        $proj = [];
        $proj["id"] = $project['id'];
        $proj["created_at"] = $project['created_at'];
        $proj["description"] = $project['description'];
        $proj["name"] = $project['name'];
        $proj["thumb_url"] = $project['thumb_url'];
        $proj["updated_at"] = $project['updated_at'];
        $proj["shared"] = isset($project['shared']) ? $project['shared'] : false;

        $proj["playlists"] = [];

        foreach($project['playlists'] as $playlist){
            $plist = [];
            $plist["id"] = $playlist['id'];
            $plist["title"] = $playlist['title'];
            $plist["project_id"] = $playlist->project->id;
            $plist["updated_at"] = $playlist['updated_at'];
            $plist["created_at"] = $playlist['created_at'];
            $plist['title'] = $playlist['title'];
            $plist['activities'] = [];
            
            foreach($playlist['activities'] as $act){
                $activity = \DB::table('h5p_contents')
                ->select(['h5p_contents.title', 'h5p_libraries.name'])
                ->where(['h5p_contents.id' =>  $act->h5p_content_id])
                ->join('h5p_libraries', 'h5p_contents.library_id', '=', 'h5p_libraries.id')->first();
                if($activity == null){
                    continue;
                }
                $plistActivity = [];
                $plistActivity['id'] = $act['id'];
                $plistActivity['type'] = $act['type'];
                $plistActivity['title'] = $activity->title;
                $plistActivity['library_name'] = $activity->name;
                $plistActivity['thumb_url'] = $act->thumb_url;
                $plist['activities'][] = $plistActivity;
            }
            $proj["playlists"][] = $plist;
            
        }
        
        return $proj;
    }

}
