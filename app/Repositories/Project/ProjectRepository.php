<?php

namespace App\Repositories\Project;

use App\Exceptions\GeneralException;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{

    private $activityRepository;
    private $playlistRepository;

    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     * @param PlaylistRepositoryInterface $playlistRepository
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(Project $model, PlaylistRepositoryInterface $playlistRepository, ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->playlistRepository = $playlistRepository;
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
     *
     * @param User $authenticated_user
     * @param Project $project
     * @param string $token
     * @return Response
     */
    public function clone($authenticated_user, Project $project, $token)
    {
        try {
            $new_image_url = clone_thumbnail($project->thumb_url, "projects");

            $data = [
                'name' => $project->name,
                'description' => $project->description,
                'thumb_url' => $new_image_url,
                'shared' => $project->shared,
                'starter_project' => false,
                'cloned_from' => $project->id,
            ];

            return \DB::transaction(function () use ($authenticated_user, $data, $project, $token) {
                $cloned_project = $authenticated_user->projects()->create($data, ['role' => 'owner']);
                if (!$cloned_project) {
                    return 'Could not create project. Please try again later.';
                }

                $playlists = $project->playlists;
                foreach ($playlists as $playlist) {
                    $cloned_activity = $this->playlistRepositroy->clone($cloned_project, $playlist, $token);
                }

                $project->clone_ctr = $project->clone_ctr + 1;
                $project->save();

                return "Project cloned successfully";
            });

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error($e->getMessage());
            throw new GeneralException('Unable to clone the project, please try again later!');
        }
    }

    /**
     * To fetch project based on LMS settings
     *
     * @param $lms_url
     * @param $lti_client_id
     * @return Project $project
     */
    public function fetchByLmsUrlAndLtiClient($lms_url, $lti_client_id)
    {
        return $this->model->whereHas('users', function ($query_user) use ($lms_url, $lti_client_id) {
            $query_user->whereHas('lmssetting', function ($query_lmssetting) use ($lms_url, $lti_client_id) {
                $query_lmssetting->where('lms_url', $lms_url)->where('lti_client_id', $lti_client_id);
            });
        })->get();
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
        $proj["name"] = $project['name'];
        $proj["description"] = $project['description'];
        $proj["thumb_url"] = $project['thumb_url'];
        $proj["shared"] = isset($project['shared']) ? $project['shared'] : false;
        $proj["elasticsearch"] = $project['elasticsearch'];
        $proj["created_at"] = $project['created_at'];
        $proj["updated_at"] = $project['updated_at'];

        $proj["playlists"] = [];
        foreach ($project['playlists'] as $playlist) {
            $plist = [];
            $plist["id"] = $playlist['id'];
            $plist["title"] = $playlist['title'];
            $plist["project_id"] = $playlist->project->id;
            $plist["created_at"] = $playlist['created_at'];
            $plist["updated_at"] = $playlist['updated_at'];
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
     *
     * @param $limit
     * @return Project $projects
     */
    public function fetchRecentPublic($limit)
    {
        return $this->model->where('is_public', true)->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * To fetch default projects
     *
     * @param $default_email
     * @return Project $projects
     */
    public function fetchDefault($default_email)
    {
        return $this->model->whereHas('users', function ($query_user) use ($default_email) {
            $query_user->where('email', $default_email);
        })->get();
    }

}
