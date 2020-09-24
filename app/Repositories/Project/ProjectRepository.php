<?php

namespace App\Repositories\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
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
     * To clone project and associated playlists
     * @param $authenticated_user
     * @param Project $project
     * @param $token
     * @return type
     */
    public function clone($authenticated_user, Project $project, $token)
    {
        $new_image_url = config('app.default_thumb_url');
        $source_file = storage_path("app/public/".(str_replace('/storage/','',$project->thumb_url)));
        if (file_exists($source_file)) {
            $ext = pathinfo(basename($project->thumb_url), PATHINFO_EXTENSION);
            $new_image_name = uniqid() . '.' . $ext;
            ob_start();
            $destination_file = str_replace("uploads","projects",str_replace(basename($project->thumb_url),$new_image_name,$source_file));
            \File::copy($source_file, $destination_file);
            ob_get_clean();
            $new_image_url = "/storage/projects/" . $new_image_name;

        } 
        $data = [
            'name' => $project->name,
            'description' => $project->description,
            'thumb_url' => $new_image_url,
            'shared' => $project->shared,
            'starter_project' => false,
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
                // is_public will always be FALSE when cloning, so we don't need to cascade it.
                // 'is_public' => $playlist->is_public
            ];
            $cloned_playlist = $clonned_project->playlists()->create($play_list_data);

            $activites = $playlist->activities;
            foreach ($activites as $activity) {
                $h5P_res = null;
                if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0) {
                    $h5P_res = $this->activityRepository->download_and_upload_h5p($token, $activity->h5p_content_id);
                }
                $new_thumb_url = config('app.default_thumb_url');
                $activites_source_file = storage_path("app/public/".(str_replace('/storage/','',$activity->thumb_url)));
                if (file_exists($activites_source_file)) {
                    $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
                    $new_image_name_mtd = uniqid() . '.' . $ext;
                    ob_start();
                    $activites_destination_file = str_replace("uploads","activities",str_replace(basename($activity->thumb_url),$new_image_name_mtd,$activites_source_file));
                    \File::copy($activites_source_file, $activites_destination_file);
                    ob_get_clean();
                    $new_thumb_url = "/storage/activities/" . $new_image_name_mtd;
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
                    // is_public & elasticsearch will always be FALSE when cloning, so we don't need to cascade it.
                    // 'is_public' => $activity->is_public,
                    // 'elasticsearch' => $activity->elasticsearch,
                    'shared' => $activity->shared,
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
            $query_user->whereHas('lmssetting', function ($query_lmssetting) use ($lms_url, $lti_client_id) {
                $query_lmssetting->where('lms_url', $lms_url)->where('lti_client_id', $lti_client_id);
            });
        })->get();
        return $projects;
    }


    /**
     * To fetch project based on LMS settings
     *
     * @param Project $project
     * @return array
     */
    public function getProjectForPreview(Project $project)
    {
        $project = Project::where(['id' => $project->id])
            ->with('playlists.activities')
            ->first();

        $proj = [];
        $proj["id"] = $project['id'];
        $proj["created_at"] = $project['created_at'];
        $proj["description"] = $project['description'];
        $proj["name"] = $project['name'];
        $proj["thumb_url"] = $project['thumb_url'];
        $proj["updated_at"] = $project['updated_at'];
        $proj["elasticsearch"] = $project['elasticsearch'];
        $proj["shared"] = isset($project['shared']) ? $project['shared'] : false;

        $proj["playlists"] = [];

        foreach ($project['playlists'] as $playlist) {
            $plist = [];
            $plist["id"] = $playlist['id'];
            $plist["title"] = $playlist['title'];
            $plist["project_id"] = $playlist->project->id;
            $plist["updated_at"] = $playlist['updated_at'];
            $plist["created_at"] = $playlist['created_at'];
            $plist['title'] = $playlist['title'];
            $plist['activities'] = [];

            foreach ($playlist['activities'] as $activity) {
                $h5pContent = \DB::table('h5p_contents')
                    ->select(['h5p_contents.title', 'h5p_libraries.name as library_name'])
                    ->where(['h5p_contents.id' => $activity->h5p_content_id])
                    ->join('h5p_libraries', 'h5p_contents.library_id', '=', 'h5p_libraries.id')->first();

                $plistActivity = [];
                $plistActivity['id'] = $activity->id;
                $plistActivity['type'] = $activity->type;
                $plistActivity['title'] = $activity->title;
                $plistActivity['library_name'] = $h5pContent ? $h5pContent->library_name : null;
                $plistActivity['thumb_url'] = $activity->thumb_url;
                $plist['activities'][] = $plistActivity;
            }
            $proj["playlists"][] = $plist;
        }

        return $proj;
    }

    /**
     * To fetch recent public project
     * @return Project $projects
     */
    public function fetchRecentPublic($limit){
        return $this->model->where('is_public', true)->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * To fetch default projects
     * @return Project $projects
     */
    public function fetchDefault($defaultEmail){
        $projects = $this->model->whereHas('users', function ($query_user) use ($defaultEmail) {
            $query_user->where('email', $defaultEmail);
        })->get();
        return $projects;
    }

}
