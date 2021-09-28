<?php

namespace App\Repositories\Project;

use App\Exceptions\GeneralException;
use App\Models\Activity;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Playlist;
use App\Models\Project;
use App\User;
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

            return \DB::transaction(function () use ($authUser, $data, $project, $team, $token) {
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
     * @return Project $project
     */
    public function fetchByLtiClientAndEmail($lti_client_id, $user_email)
    {
        return $this->model->whereHas('users', function ($query_user) use ($lti_client_id, $user_email) {
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
    public function fetchDefault($default_email)
    {
        return $this->model->whereHas('users', function ($query_user) use ($default_email) {
            $query_user->where('email', $default_email);
        })->get();
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
     * @param array $projects
     */
    public function saveList(array $projects)
    {
        foreach ($projects as $project) {
            $this->update([
                'order' => $project['order'],
            ], $project['id']);
        }
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
     * @param $project
     * @return mixed
     * @throws GeneralException
     */
    public function indexing($project)
    {
        // if indexing status is already set
        if ($project->indexing) {
            throw new GeneralException('Indexing value is already set. Current indexing state of this project: ' . $project->indexing_text);
        }
        // if project is in draft
        if ($project->status === 1) {
            throw new GeneralException('Project must be finalized before requesting the indexing.');
        }
        $project->indexing = 1; // 1 is for indexing requested - see Project Model @indexing property
        resolve(\App\Repositories\Admin\Project\ProjectRepository::class)->indexProjects([$project->id]); // resolve dependency one time only
        return $project->save();
    }

    /**
     * @param $project
     * @return mixed
     */
    public function statusUpdate($project)
    {
        // see Project Model @status property for mapping
        $project->status = 3 - $project->status; // this will toggle status, if draft then it will be final or vice versa
        if ($project->status === 1){
            $project->indexing = null; // remove indexing if project is reverted to draft state
            $returnProject = $project->save();
            resolve(\App\Repositories\Admin\Project\ProjectRepository::class)->indexProjects([$project->id]); // resolve dependency one time only
        } else {
            $returnProject = $project->save();
        }

        return $returnProject;
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

        return $query->where('organization_id', $suborganization->id)->paginate($perPage)->appends(request()->query());
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
            throw new GeneralException('Invalid index value provided.');
        }
        $project->update(['indexing' => $index, 'shared' => true]);
        $this->indexProjects([$project->id]);
        return 'Index status changed successfully!';
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
        Storage::disk('public')->put('/exports/'.$project_dir_name.'/project.json', $project);

        $project_thumbanil = "";
        if (filter_var($project->thumb_url, FILTER_VALIDATE_URL) == false) {
            $project_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $project->thumb_url)));
            $ext = pathinfo(basename($project_thumbanil), PATHINFO_EXTENSION);
            if(file_exists($project_thumbanil)) {
                Storage::disk('public')->put('/exports/'.$project_dir_name.'/'.basename($project_thumbanil),file_get_contents($project_thumbanil));
            }
        }

        $playlists = $project->playlists;

        foreach ($playlists as $playlist) {

            $title = $playlist->title;
            Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/'.$title.'.json', $playlist);
            $activites = $playlist->activities;
            ;
            foreach($activites as $activity) {
                Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.$activity->title.'.json', $activity);
                //dd(json_decode($activity->h5p_content,true));
                $decoded_content = json_decode($activity->h5p_content,true);

                $decoded_content['library_title'] = \DB::table('h5p_libraries')->where('id', $decoded_content['library_id'])->value('name');
                $decoded_content['library_major_version'] = \DB::table('h5p_libraries')->where('id', $decoded_content['library_id'])->value('major_version');
                $decoded_content['library_minor_version'] = \DB::table('h5p_libraries')->where('id', $decoded_content['library_id'])->value('minor_version');
                Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.$activity->h5p_content_id.'.json', json_encode($decoded_content));

                if (filter_var($activity->thumb_url, FILTER_VALIDATE_URL) == false) {
                    $activity_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $activity->thumb_url)));
                    $ext = pathinfo(basename($activity_thumbanil), PATHINFO_EXTENSION);
                    if(file_exists($activity_thumbanil)) {
                        Storage::disk('public')->put('/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.basename($activity_thumbanil),file_get_contents($activity_thumbanil));
                    }
                }

                \File::copyDirectory( storage_path('app/public/h5p/content/'.$activity->h5p_content_id), storage_path('app/public/exports/'.$project_dir_name.'/playlists/'.$title.'/activities/'.$activity->title.'/'.$activity->h5p_content_id) );
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
    public function importProject($authUser, $path, $suborganization_id)
    {
        try {

            $zip = new ZipArchive;
            $source_file = storage_path("app/public/" . (str_replace('/storage/', '', $path)));

            if ($zip->open($source_file) === TRUE) {
                $extracted_folder_name = "app/public/imports/project-".uniqid();
                $zip->extractTo(storage_path($extracted_folder_name.'/'));
                $zip->close();
            }else {
                return "Unable to import Project";
            }
            return \DB::transaction(function () use ($extracted_folder_name, $suborganization_id, $authUser, $source_file) {
                if(file_exists(storage_path($extracted_folder_name.'/project.json'))) {
                    $project_json = file_get_contents(storage_path($extracted_folder_name.'/project.json'));

                    $project = json_decode($project_json,true);
                    unset($project['id'], $project['organization_id'], $project['organization_visibility_type_id'], $project['created_at'], $project['updated_at']);

                    $project['organization_id'] = $suborganization_id;
                    $project['organization_visibility_type_id'] = 1;
                    if (filter_var($project['thumb_url'], FILTER_VALIDATE_URL) === false) {  // copy thumb url

                        if(file_exists(storage_path($extracted_folder_name.'/'.basename($project['thumb_url'])))) {

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
                    unlink($source_file); // Deleted the storage zip file
                    $this->rrmdir(storage_path($extracted_folder_name)); // Deleted the storage extracted directory
                    
                    return $project['name'];
                }
            });


        }catch (\Exception $e) {
            \DB::rollBack();
            Log::error($e->getMessage());
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

}
