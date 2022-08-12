<?php

namespace App\Repositories\Project;

use App\Exceptions\GeneralException;
use App\Models\Activity;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Playlist;
use App\Models\Project;
use App\User;
use App\Models\Organization;
use App\Models\Team;
use App\Repositories\BaseRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\App;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\V1\ActivityResource;

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
        $projectObj = $this->model->find($id);

        if (
            isset($attributes['organization_visibility_type_id']) &&
            $projectObj->organization_visibility_type_id !== (int)$attributes['organization_visibility_type_id']
        ) {
            $attributes['indexing'] = config('constants.indexing-requested');
            $attributes['status'] = config('constants.status-finished');

            if ((int)$attributes['organization_visibility_type_id'] === config('constants.private-organization-visibility-type-id')) {
                $attributes['status'] = config('constants.status-draft');
            }
        }

        $is_updated = $this->model->where('id', $id)->update($attributes);

        if ($is_updated) {
            $project = $this->model->find($id);

            $project->searchable();

            foreach ($project->playlists as $playlist)
            {
                $playlist->searchable();

                foreach ($playlist->activities as $activity)
                {
                    $activity->searchable();
                }
            }
        }

        return $is_updated;
    }

    /**
     * Update shared for project and its playlists and activities
     *
     * @param Project $project
     * @param bool $shared
     * @return bool
     */
    public function updateShared(Project $project, bool $shared)
    {
        $project->shared = $shared;
        $is_updated = $project->save();

        if ($is_updated) {
            foreach ($project->playlists as $playlist)
            {
                $playlist->shared = $shared;
                $is_playlist_updated = $playlist->save();

                if ($is_playlist_updated) {
                    foreach ($playlist->activities as $activity)
                    {
                        $activity->shared = $shared;
                        $activity->save();
                    }
                }
            }
        }

        return $is_updated;
    }

    /**
     * Get latest order of project for User
     * @param $authenticated_user
     * @return int
     */
    public function getOrder($authenticated_user)
    {
        return $authenticated_user->projects()->orderBy('order','desc')
            ->value('order') ?? 0;
    }

    /**
     * To clone project and associated playlists
     *
     * @param $authUser
     * @param Project $project
     * @param string $token
     * @param int $organization_id
     * @param $team
     * @return Response
     * @throws GeneralException
     */
    public function clone($authUser, Project $project, $token, $organization_id = null, $team = null)
    {
        try {
            $new_image_url = clone_thumbnail($project->thumb_url, "projects");
            $isDuplicate = $this->checkIsDuplicate($authUser, $project->id, $organization_id);

            if ($isDuplicate) {
                $authUser->projects()->where('order', '>', $project->order)->increment('order', 1);
            }

            $data = [
                'name' => ($isDuplicate) ? $project->name . "-COPY" : $project->name,
                'description' => $project->description,
                'thumb_url' => $new_image_url,
                'shared' => $project->shared,
                'order' => ($isDuplicate) ? $project->order + 1 : $this->getOrder($authUser) + 1,
                'starter_project' => false, // this is for global starter project
                'is_user_starter' => (bool) $project->starter_project, // this is for user level starter project (means cloned by global starter project)
                'cloned_from' => $project->id,
            ];

            if ($organization_id) {
                $data['organization_id'] = $organization_id;
                $data['organization_visibility_type_id'] = config('constants.private-organization-visibility-type-id');
            }

            return DB::transaction(function () use ($authUser, $data, $project, $team, $token) {
                $cloned_project = $authUser->projects()->create($data, ['role' => 'owner']);
                if (!$cloned_project) {
                    return 'Could not create project. Please try again later.';
                }

                $playlists = $project->playlists;
                foreach ($playlists as $playlist) {
                    $this->playlistRepository->clone($cloned_project, $playlist, $token);
                }

                if ($team) {
                    $team->projects()->attach($cloned_project);
                    $cloned_project->team_id = $team->id;
                    $cloned_project->save();
                }
                $project->clone_ctr = $project->clone_ctr + 1;
                $project->save();

                return "Project cloned successfully";
            });

        } catch (\Exception $e) {
            DB::rollBack();
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
     * To fetch project based on LMS settings lti client id only
     *
     * @param $lti_client_id
     * @return Project $project
     */
    public function fetchByLtiClient($lti_client_id)
    {
        return $this->model->whereHas('users', function ($query_user) use ($lti_client_id) {
            $query_user->whereHas('lmssetting', function ($query_lmssetting) use ($lti_client_id) {
                $query_lmssetting->where('lti_client_id', $lti_client_id);
            });
        })->get();
    }

    /**
     * To fetch project based on LMS settings lti client id and user email
     *
     * @param $lti_client_id
     * @param $user_email
     * @param $searchTerm
     * @param $lms_organization_id
     * @return Project $project
     */
    public function fetchByLtiClientAndEmail($lti_client_id, $user_email, $searchTerm, $lms_organization_id)
    {
        return $this->model->where('organization_id', $lms_organization_id)->where('name', 'iLIKE', '%' . $searchTerm . '%')->whereHas('users', function ($query_user) use ($lti_client_id, $user_email) {
            $query_user->whereHas('lmssetting', function ($query_lmssetting) use ($lti_client_id, $user_email) {
                $query_lmssetting->where('lti_client_id', $lti_client_id);
                $query_lmssetting->where('lms_login_id', 'ilike', $user_email);
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
            ->with(['playlists' => function ($query) {
                $query->orderBy('order');
            },
            'playlists.activities' => function ($query) {
                $query->orderBy('order');
            }])
            ->first();

        $proj = [];
        $proj["id"] = $project['id'];
        $proj["name"] = $project['name'];
        $proj["description"] = $project['description'];
        $proj["thumb_url"] = $project['thumb_url'];
        $proj["shared"] = $project['shared'] ?? false;
        $proj["indexing"] = $project['indexing'];
        $proj["indexing_text"] = $project['indexing_text'];
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
                $h5pContent = DB::table('h5p_contents')
                    ->select(['h5p_contents.title', 'h5p_libraries.name as library_name'])
                    ->where(['h5p_contents.id' => $activity->h5p_content_id])
                    ->join('h5p_libraries', 'h5p_contents.library_id', '=', 'h5p_libraries.id')->first();

                $plistActivity = [];
                $plistActivity['id'] = $activity->id;
                $plistActivity['type'] = $activity->type;
                $plistActivity['title'] = $activity->title;
                $plistActivity['order'] = $activity->order;
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
     * @param $organization_id
     * @return Project $projects
     */
    public function fetchRecentPublic($limit, $organization_id)
    {
        $authenticated_user = auth()->user();
        // 3 is for indexing approved - see Project Model @indexing property
        return $this->model->where('indexing', 3)->where('organization_id', $organization_id)->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * To fetch default projects
     *
     * @param $default_email
     * @return Project $projects
     */
    public function fetchDefault($default_email, $data)
    {
        $query = $this->model;

        if (isset($data['query']) && $data['query'] != '') {
            $query = $query->where('name', 'iLIKE', '%' . $data['query'] . '%')
                ->orwhere('description', 'iLIKE', '%' . $data['query'] . '%');
        }
        $query = $query->whereHas('users', function ($query_user) use ($default_email) {
            $query_user->where('email', $default_email);
        });

        if (!isset($data['size'])) {
            return $query->orderBy('order', 'ASC')->get();
        }

        if (isset($data['order_by_column'])) {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->paginate($data['size'])->withQueryString();
    }

    /**
     * To Populate missing order number, One time script
     */
    public function populateOrderNumber()
    {
        $users = User::all();
        foreach ($users as $user) {
            $projects = $user->projects()->orderBy('created_at')->get();
            if (!empty($projects)) {
                $order = 1;
                foreach ($projects as $project) {
                   $project->order = $order;
                   $project->save();
                   $order++;
                }
            }
        }
    }

    /**
     * To reorder Projects
     *
     * @param array $newProjectsOrder
     * @param array $existingProjectsOrder
     */
    public function saveList(array $newProjectsOrder, array $existingProjectsOrder)
    {
        foreach ($newProjectsOrder as $project) {
            if (isset($existingProjectsOrder[$project['id']]) && ($existingProjectsOrder[$project['id']] !== $project['order'])) {
                $this->update([
                    'order' => $project['order'],
                ], $project['id']);
            }
        }
    }

    /**
     * Update Project's Order
     *
     * @param $authenticatedUser
     * @param Project $project
     * @param int $order
     * @return int
     */
    public function updateOrder($authenticatedUser, Project $project, int $order)
    {
        $authenticatedUserOrgProjectIdsString = $this->getUserProjectIdsInOrganization($authenticatedUser, $project->organization);

        $existingOrder = $project->order;// order of project whose position we want to change
        $newOrder = $order;// new order for project

        // get id of project whose position we want to change
        $projectId = $project->id;

        // Now update all order between $existingOrder and $newOrder
        if ($existingOrder < $newOrder) {
            // if $existingOrder is less than $newOrder then
            $set = '"order" = "order" - 1';
            $where = '"order" > ' . $existingOrder . ' AND "order" <= ' . $newOrder;
        } else {
            $set = '"order" = "order" + 1';
            $where = '"order" < ' . $existingOrder . ' AND "order" >= ' . $newOrder;
        }

        return DB::transaction(function () use ($set, $where, $authenticatedUserOrgProjectIdsString, $newOrder, $projectId) {
            // update order's
            $query = 'UPDATE "projects" SET ' . $set . ' WHERE ' . $where . ' AND "id" IN (' . $authenticatedUserOrgProjectIdsString . ')';
            $affectedProjectsCount = DB::update($query);

            // now update order for $projectId
            $query = 'UPDATE "projects" SET "order" = ' . $newOrder . ' WHERE id = ' . $projectId;
            $affectedProject = DB::update($query);

            return $affectedProject;
        });
    }

    /**
     * @param $authenticated_user
     * @param $project_id
     * @param $organization_id
     * @return bool
     */
    public function checkIsDuplicate($authenticated_user, $project_id, $organization_id)
    {
        $userProjectIds = $authenticated_user->projects()->where('organization_id', '=', $organization_id)->pluck('id')->toArray();
        return in_array($project_id, $userProjectIds);
    }

    /**
     * @param $authenticated_user
     * @param $project
     * @param $organization_id
     * @return bool
     */
    public function favoriteUpdate($authenticated_user, $project, $organization_id)
    {
        if ($authenticated_user->favoriteProjects()->where('id', $project->id)->wherePivot('organization_id', $organization_id)->first()) {
            return $authenticated_user->favoriteProjects()->wherePivot('organization_id', $organization_id)->detach($project->id);
        } else {
            return $authenticated_user->favoriteProjects()->attach($project->id, ['organization_id' => $organization_id]);
        }
    }

    /**
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        $query = $this->model;
        $q = $data['query'] ?? null;

        // if simple request for getting project listing with search
        if ($q) {
            $query = $query->where(function($qry) use ($q) {
                $qry->where('name', 'iLIKE', '%' .$q. '%')
                    ->orWhereHas('users', function ($qry) use ($q) {
                        $qry->where('email', 'iLIKE', '%' .$q. '%');
                    });
            });
        }

        // exclude users those projects which were clone from global starter project
        if (isset($data['exclude_starter']) && $data['exclude_starter']) {
            $query = $query->whereHas('users');
            $query = $query->where('is_user_starter', false);
        }

        // if all indexed projects requested
        if (isset($data['indexing']) && $data['indexing'] === '0') {
            $query = $query->whereIn('indexing', [1, 2, 3]);
        }

        // if specific index projects requested
        if (isset($data['indexing']) && $data['indexing'] !== '0') {
            $query = $query->where('indexing', $data['indexing']);
        }

        // if starter projects requested
        if (isset($data['starter_project'])) {
            $query = $query->where('starter_project', $data['starter_project']);
        }

        // filter by author
        if (isset($data['author_id'])) {
            $query = $query->where(function($qry) use ($data) {
                        $qry->WhereHas('users', function ($qry) use ($data) {
                            $qry->where('id', $data['author_id']);
                        });
                     });
        }

        // filter by shared status
        if (isset($data['shared'])) {
            $query = $query->where('shared', $data['shared']);
        }

        // filter by date created
        if (isset($data['created_from']) && isset($data['created_to'])) {
            $query = $query->whereBetween('created_at', [$data['created_from'], $data['created_to']]);
        }

        if (isset($data['created_from']) && !isset($data['created_to'])) {
            $query = $query->whereDate('created_at', '>=', $data['created_from']);
        }

        if (isset($data['created_to']) && !isset($data['created_from'])) {
            $query = $query->whereDate('created_to', '<=', $data['created_to']);
        }

        // filter by date updated
        if (isset($data['updated_from']) && isset($data['updated_to'])) {
            $query = $query->whereBetween('updated_at', [$data['updated_from'], $data['updated_to']]);
        }

        if (isset($data['updated_from']) && !isset($data['updated_to'])) {
            $query = $query->whereDate('updated_at', '>=', $data['updated_from']);
        }

        if (isset($data['updated_to']) && !isset($data['updated_from'])) {
            $query = $query->whereDate('updated_at', '<=', $data['updated_to']);
        }

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        }

        return $query->where('organization_id', $suborganization->id)->paginate($perPage)->withQueryString();
    }

    /**
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getTeamProjects($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $auth_user = auth()->user();

        $query = $this->model;
        $q = $data['query'] ?? null;

        // if simple request for getting project listing with search
        if ($q) {
            $query = $query->where(function($qry) use ($q) {
                $qry->where('name', 'iLIKE', '%' .$q. '%')
                    ->orWhereHas('team', function ($qry) use ($q) {
                        $qry->where('name', 'iLIKE', '%' .$q. '%');
                });
            });
        }

        $query = $query->whereHas('users', function ($qry) use ($auth_user) {
                    $qry->where('user_id', $auth_user->id);
                 });

        $query = $query->whereNotNull('team_id');

        return $query->where('organization_id', $suborganization->id)->paginate($perPage)->appends(request()->query());
    }

    /**
     * Update Indexes for projects and related models
     * @param $project
     * @param $index
     * @return string
     * @throws GeneralException
     */
    public function updateIndex($project, $index): string
    {
        if (! isset($this->model::$indexing[$index])){
            throw new GeneralException('Invalid Library value provided.');
        }
        $project->update(['indexing' => $index]);
        $this->indexProjects([$project->id]);
        return 'Library status changed successfully!';
    }

    /**
     * @param $projects
     * Indexing of the projects
     */
    public function indexProjects($projects): void
    {
        if (empty($projects)) {
            return;
        }
        // first get the collection of playlists - needed in activities update
        $playlists = Playlist::whereIn('project_id', $projects)->get('id');

        // search-able is needed as on collections update observer will not get fired
        // so searchable will updated the elastic search index via scout package
        // update directly on query builder
        $this->model->whereIn('id', $projects)->searchable();

        // to fire observer update should be done on each single instance of models
        // $projects->each->update(); can do for firing observers on each model object - might need for elastic search
        // prepare the query builder from collections and perform update
        Playlist::whereIn('project_id', $projects)->searchable();

        // update related activities by getting keys of parent playlists
        Activity::whereIn('playlist_id', $playlists->modelKeys())->searchable();
    }

    /**
     * @param $projects
     * @param $flag
     * @return string
     * @throws GeneralException
     */
    public function toggleStarter($projects, $flag): string
    {
        if (empty($projects)) {
            throw new GeneralException('Choose at-least one project.');
        }
        $this->model->whereIn('id', $projects)->update(['starter_project' => (bool)$flag]);
        return 'Starter Projects status updated successfully!';
    }

    /**
     * To export project and associated playlists
     *
     * @param $authUser
     * @param Project $project
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function exportProject($authUser, Project $project)
    {
        $zip = new ZipArchive;

        $project_dir_name = 'projects-'.uniqid();

        // Add Grade level of first activity on project manifest
        $organizationName = Organization::where('id', $project->organization_id)->value('name');


        $project['org_name'] = $organizationName;
        $project['grade_name'] = $this->getActivityGrade($project->id, 'educationLevels');
        $project['subject_name'] = $this->getActivityGrade($project->id, 'subjects');
        Storage::disk('public')->put('/exports/'.$project_dir_name.'/project.json', $project);

        $project_thumbanil = "";
        if (!empty($project->thumb_url) && filter_var($project->thumb_url, FILTER_VALIDATE_URL) == false) {
            $project_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $project->thumb_url)));
            $ext = pathinfo(basename($project_thumbanil), PATHINFO_EXTENSION);
            if(file_exists($project_thumbanil)) {
                Storage::disk('public')
                            ->put('/exports/'.$project_dir_name.'/'.basename($project_thumbanil), file_get_contents($project_thumbanil));
            }
        }

        $playlists = $project->playlists;

        foreach ($playlists as $playlist) {

            $title = str_replace('/', '-', $playlist->title);
            Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/'.$title.'.json', $playlist);
            $activites = $playlist->activities;
            ;
            foreach($activites as $activity) {

                $activityTitle = str_replace('/', '-', $activity->title);

                $activity_json_file = '/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                                $activityTitle . '/' . $activityTitle . '.json';
                Storage::disk('public')->put($activity_json_file, $activity);

                // Export Subject
                $activitySubjectJsonFile = '/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                                    $activityTitle . '/activity_subject.json';

                Storage::disk('public')->put($activitySubjectJsonFile, $activity->subjects);

                // Export Education level

                $activityEducationLevelJsonFile = '/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                                    $activityTitle . '/activity_education_level.json';

                Storage::disk('public')->put($activityEducationLevelJsonFile, $activity->educationLevels);

                // Export Author

                $activityAuthorTagJsonFile = '/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                                    $activityTitle . '/activity_author_tag.json';

                Storage::disk('public')->put($activityAuthorTagJsonFile, $activity->authorTags);

                $decoded_content = json_decode($activity->h5p_content,true);

                $decoded_content['library_title'] = DB::table('h5p_libraries')
                                                                    ->where('id', $decoded_content['library_id'])->value('name');
                $decoded_content['library_major_version'] = DB::table('h5p_libraries')
                                                                        ->where('id', $decoded_content['library_id'])
                                                                        ->value('major_version');
                $decoded_content['library_minor_version'] = DB::table('h5p_libraries')
                                                                        ->where('id', $decoded_content['library_id'])
                                                                        ->value('minor_version');

                $content_json_file = '/exports/'.$project_dir_name.'/playlists/' . $title . '/activities/' .
                                                                $activityTitle.'/' . $activity->h5p_content_id . '.json';
                Storage::disk('public')->put($content_json_file, json_encode($decoded_content));

                if (!empty($activity->thumb_url) && filter_var($activity->thumb_url, FILTER_VALIDATE_URL) == false) {
                    $activity_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $activity->thumb_url)));
                    $ext = pathinfo(basename($activity_thumbanil), PATHINFO_EXTENSION);
                    if(!is_dir($activity_thumbanil) && file_exists($activity_thumbanil)) {
                        $activity_thumbanil_file = '/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                                            $activityTitle . '/' . basename($activity_thumbanil);
                        Storage::disk('public')->put($activity_thumbanil_file, file_get_contents($activity_thumbanil));
                    }
                }
                $exported_content_dir_path = 'app/public/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                                                    $activityTitle . '/' . $activity->h5p_content_id;
                $exported_content_dir = storage_path($exported_content_dir_path);
                \File::copyDirectory( storage_path('app/public/h5p/content/'.$activity->h5p_content_id), $exported_content_dir );
            }
        }

        // Get real path for our folder
        $rootPath = storage_path('app/public/exports/'.$project_dir_name);

        // Initialize archive object
        $zip = new ZipArchive();
        $fileName = $project_dir_name.'.zip';
        $zip->open(storage_path('app/public/exports/'.$fileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        // Remove project folder after creation of zip
        $this->rrmdir(storage_path('app/public/exports/'.$project_dir_name));

        // Remove project folder after creation of zip
        $this->rrmdir(storage_path('app/public/exports/'.$project_dir_name));

        return storage_path('app/public/exports/'.$fileName);
    }


    /**
     * To import project and associated playlists
     *
     * @param $authUser
     * @param Project $path
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function importProject($authUser, $path, $suborganization_id, $method_source="API")
    {
        try {

            $zip = new ZipArchive;
            $source_file = storage_path("app/public/" . (str_replace('/storage/', '', $path)));

            if ($method_source === "command") {
                $source_file = $path;
            }

            if ($zip->open($source_file) === TRUE) {
                $extracted_folder_name = "app/public/imports/project-".uniqid();
                $zip->extractTo(storage_path($extracted_folder_name.'/'));
                $zip->close();
            }else {
                $return_res = [
                    "success"=> false,
                    "message" => "Unable to import Project."
                ];
                return json_encode($return_res);
            }
            return DB::transaction(function () use ($extracted_folder_name, $suborganization_id, $authUser, $source_file, $method_source) {
                if (file_exists(storage_path($extracted_folder_name.'/project.json'))) {
                    $project_json = file_get_contents(storage_path($extracted_folder_name.'/project.json'));

                    $project = json_decode($project_json,true);
                    unset($project['id'], $project['organization_id'],
                                            $project['organization_visibility_type_id'], $project['created_at'], $project['updated_at'], $project['team_id']);

                    $project['organization_id'] = $suborganization_id;
                    $project['organization_visibility_type_id'] = 1;
                    if (!empty($project['thumb_url']) && filter_var($project['thumb_url'], FILTER_VALIDATE_URL) === false) {  // copy thumb url

                        if (file_exists(storage_path($extracted_folder_name.'/'.basename($project['thumb_url'])))) {

                            $ext = pathinfo(basename($project['thumb_url']), PATHINFO_EXTENSION);
                            $new_image_name = uniqid() . '.' . $ext;
                            $destination_file = storage_path('app/public/projects/'.$new_image_name);

                            \File::copy(storage_path($extracted_folder_name.'/'.basename($project['thumb_url'])), $destination_file);
                            $project['thumb_url'] = "/storage/projects/" . $new_image_name;
                        }
                    }

                    $cloned_project = $authUser->projects()->create($project, ['role' => 'owner']);
                    if (file_exists(storage_path($extracted_folder_name . '/playlists'))) {
                        $playlist_directories = scandir(storage_path($extracted_folder_name . '/playlists'));

                        for ($i=0; $i<count($playlist_directories); $i++) { // loop through all playlists
                            if ($playlist_directories[$i] == '.' || $playlist_directories[$i] == '..') continue;
                            $this->playlistRepository->playlistImport($cloned_project, $authUser, $extracted_folder_name, $playlist_directories[$i]);
                        }
                    }

                    $this->rrmdir(storage_path($extracted_folder_name)); // Deleted the storage extracted directory

                    if ($method_source !== "command") {
                        unlink($source_file); // Deleted the storage zip file
                    } else {

                        $return_res = [
                            "success"=> true,
                            "message" => "Project has been imported successfully",
                            "project_id" => $cloned_project->id
                        ];
                        return json_encode($return_res);
                    }

                    return $project['name'];
                }
            });


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            if ($method_source === "command") {
                $return_res = [
                    "success"=> false,
                    "message" => "Unable to import the project, please try again later!"
                ];
                return(json_encode($return_res));
            }

            throw new GeneralException('Unable to import the project, please try again later!');
        }
    }

    /**
     * To Deleted the directory recurcively
     *
     * @param $dir
     */
    private function rrmdir($dir) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
              if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
            }
          }
          reset($objects);
          rmdir($dir);
        }
     }

     /**
     * To export project and associated playlists
     *
     * @param $authUser
     * @param Project $project
     * @throws GeneralException
     */
    public function exportNoovoProject($authUser, Project $project)
    {
        $zip = new ZipArchive;

        $project_dir_name = 'projects-' . uniqid();
        Storage::disk('public')->put('/exports/' . $project_dir_name . '/project.json', $project);

        $project_thumbanil = "";
        if (!empty($project->thumb_url) && filter_var($project->thumb_url, FILTER_VALIDATE_URL) == false) {
            $project_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $project->thumb_url)));
            $ext = pathinfo(basename($project_thumbanil), PATHINFO_EXTENSION);
            if (file_exists($project_thumbanil)) {
                Storage::disk('public')->put('/exports/' . $project_dir_name . '/' . basename($project_thumbanil), file_get_contents($project_thumbanil));
            }
        }

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $interface = $h5p::$interface;


        $playlists = $project->playlists;

        foreach ($playlists as $playlist) {

            $title = str_replace('/', '-', $playlist->title);
            Storage::disk('public')->put('/exports/' . $project_dir_name . '/playlists/' . $title. '/' . $title . '.json', $playlist);
            $activites = $playlist->activities;
            ;
            foreach($activites as $activity) {
                $destination_playlist_json = '/exports/' . $project_dir_name . '/playlists/' . $title .
                                                '/activities/' . $activity->title . '/' . $activity->title . '.json';
                Storage::disk('public')->put($destination_playlist_json, $activity);
                $decoded_content = json_decode($activity->h5p_content, true);

                $decoded_content['library_title'] = DB::table('h5p_libraries')
                                                            ->where('id', $decoded_content['library_id'])
                                                            ->value('name');
                $decoded_content['library_major_version'] = DB::table('h5p_libraries')
                                                                ->where('id', $decoded_content['library_id'])
                                                                ->value('major_version');
                $decoded_content['library_minor_version'] = DB::table('h5p_libraries')
                                                                ->where('id', $decoded_content['library_id'])
                                                                ->value('minor_version');
                $destination_activity_json = '/exports/' . $project_dir_name . '/playlists/' . $title .
                                                '/activities/' . $activity->title . '/' . $activity->h5p_content_id . '.json';

                Storage::disk('public')->put($destination_activity_json, json_encode($decoded_content));

                if (!empty($activity->thumb_url) && filter_var($activity->thumb_url, FILTER_VALIDATE_URL) == false) {
                    $activity_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $activity->thumb_url)));
                    $ext = pathinfo(basename($activity_thumbanil), PATHINFO_EXTENSION);
                    if (file_exists($activity_thumbanil)) {
                        $destination_activity_thumbnail = '/exports/' . $project_dir_name . '/playlists/' . $title .
                                                            '/activities/' . $activity->title . '/' . basename($activity_thumbanil);
                        Storage::disk('public')->put($destination_activity_thumbnail, file_get_contents($activity_thumbanil));
                    }
                }

                $content_directory_source = storage_path('app/public/h5p/content/' . $activity->h5p_content_id);
                $content_directory_destination_path = 'app/public/exports/' . $project_dir_name .'/playlists/' . $title .
                                                        '/activities/' . $activity->title . '/' . $activity->h5p_content_id;
                $content_directory_destination = storage_path($content_directory_destination_path);
                \File::copyDirectory($content_directory_source, $content_directory_destination);
                $h5p = App::make('LaravelH5p');
                $core = $h5p::$core;
                $interface = $h5p::$interface;
                $content = $core->loadContent($activity->h5p_content_id);
                $content['filtered'] = '';
                $params = $core->filterParameters($content);
                if (file_exists(storage_path('app/public/h5p/exports/' . $content['slug'] . '-' . $activity->h5p_content_id . '.h5p'))) {
                    $h5p_source = storage_path('app/public/h5p/exports/' . $content['slug'] . '-' . $activity->h5p_content_id . '.h5p');
                    $h5p_destination_path = 'app/public/exports/' . $project_dir_name . '/playlists/' . $title . '/activities/' .
                                                $activity->title . '/' . $content['slug'] . '-' . $activity->h5p_content_id . '.h5p';
                    $h5p_destination = storage_path($h5p_destination_path);
                    @copy($h5p_source, $h5p_destination);
                }


            }
        }

        // Get real path for our folder
        $rootPath = storage_path('app/public/exports/' . $project_dir_name);

        // Initialize archive object
        $zip = new ZipArchive();
        $fileName = $project_dir_name . '.zip';
        $zip->open(storage_path('app/public/exports/' . $fileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        return storage_path('app/public/exports/' . $fileName);
    }

    /**
     * Create model in storage
     *
     * @param $authenticatedUser
     * @param $suborganization
     * @param $data
     * @param $role
     * @return Model
     */
    public function createProject($authenticatedUser, $suborganization, $data, $role)
    {
        $data['order'] = 0;
        $data['organization_id'] = $suborganization->id;

        $authenticatedUserOrgProjectIdsString = $this->getUserProjectIdsInOrganization($authenticatedUser, $suborganization);

        return DB::transaction(function () use ($authenticatedUser, $data, $role, $authenticatedUserOrgProjectIdsString) {

            if (!empty($authenticatedUserOrgProjectIdsString)) {
                // update order's
                $query = 'UPDATE "projects" SET "order" = "order" + 1 WHERE "id" IN (' . $authenticatedUserOrgProjectIdsString . ')';
                $affectedProjectsCount = DB::update($query);
            }

            $project = $authenticatedUser->projects()->create($data, $role);

            // to attach a project directly from team
            if (isset($data['team_id'])) {
                $team = Team::findOrFail($data['team_id']);
                $team->projects()->attach($project);
            }

            if ($project) {
                $playlistData['title'] = 'playlist1';
                $playlistData['order'] = 1;

                $playlist = $project->playlists()->create($playlistData);
            }

            return $project;
        });
    }

    /**
     * Get user project ids in org
     *
     * @param $authenticatedUser
     * @param $organization
     * @return array
     */
    public function getUserProjectIdsInOrganization($authenticatedUser, $organization) {
        $authenticatedUserOrgProjectIds = $authenticatedUser
                                        ->projects()
                                        ->where('organization_id', $organization->id)
                                        ->pluck('id')
                                        ->all();

        $authenticatedUserOrgProjectIdsString = implode(",", $authenticatedUserOrgProjectIds);

        return $authenticatedUserOrgProjectIdsString;
    }

    /**
     * Get Activity Grade
     *
     * @param $projectId
     * @param $activityParam
     * @return response
     */
    private function getActivityGrade($projectId, $activityParam)
    {
        $playlistId = Playlist::where('project_id', $projectId)->orderBy('order','asc')->limit(1)->first();

        $activity = Activity::where('playlist_id', $playlistId->id)->orderBy('order','asc')->limit(1)->first();

        $resource = new ActivityResource($activity);

        // Get first Category
        if ($resource->$activityParam->isNotEmpty()) {
            return $resource->$activityParam[0]->name;
        }
        return null;

    }

    /**
     * Get login user Projects
     *
     * @param $suborganization
     * @param $data
     * @return response
     */
    public function getProjects($suborganization, $data) {

        $authenticated_user = auth()->user();
        $query = $authenticated_user->projects();

        if (isset($data['query']) && $data['query'] != '') {
            $query = $query->where(function($qry) use ($data) {
                $qry->where('name', 'iLIKE', '%' . $data['query'] . '%')
                ->orwhere('description', 'iLIKE', '%' . $data['query'] . '%');
            });
        }

        $query = $query->whereNull('team_id')->where('organization_id', $suborganization->id);

        if (!isset($data['size'])) {
            return $query->orderBy('order', 'ASC')->get();
        }

        if (isset($data['order_by_column'])) {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->paginate($data['size'])->withQueryString();
    }

    /**
     * Get Favorite Projects
     *
     * @param $suborganization
     * @param $data
     * @return response
     */
    public function getFavoriteProjects($suborganization, $data) {

        $authenticated_user = auth()->user();
        $query = $authenticated_user->favoriteProjects();

        if (isset($data['query']) && $data['query'] != '') {
            $query = $query->where('name', 'iLIKE', '%' . $data['query'] . '%')
                ->orwhere('description', 'iLIKE', '%' . $data['query'] . '%');
        }

        $query = $query->wherePivot('organization_id', $suborganization->id);

        if (!isset($data['size'])) {
            return $query->orderBy('order', 'ASC')->get();
        }

        if (isset($data['order_by_column'])) {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->paginate($data['size'])->withQueryString();
    }
}
