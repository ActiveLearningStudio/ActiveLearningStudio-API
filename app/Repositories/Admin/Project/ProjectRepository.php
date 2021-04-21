<?php

namespace App\Repositories\Admin\Project;

use App\Exceptions\GeneralException;
use App\Jobs\CloneProject;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository.
 */
class ProjectRepository extends BaseRepository
{
    /**
     * @var Playlist
     */
    private $playlistModel;

    /**
     * @var Activity
     */
    private $activityModel;

    /**
     * ProjectRepository constructor.
     * @param Project $model
     * @param Playlist $playlistModel
     * @param Activity $activityModel
     */
    public function __construct(Project $model, Playlist $playlistModel, Activity $activityModel)
    {
        $this->model = $model;
        $this->playlistModel = $playlistModel;
        $this->activityModel = $activityModel;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $mode = $data['mode'] ?? 'all';
        $q = $data['q'] ?? null;

        // if data tables parameters present in request
        if ($this->isDtRequest($data)) {
            $this->setDtParams($data)->enableRelDtSearch(["email"], $this->dtSearchValue);
        }

        // if simple request for getting project listing with search
        if ($q) {
            $this->enableDtSearch(['name'], $q)->enableRelDtSearch(['email'], $q);
        }

        $this->query = $this->model->when($mode !== 'all', function ($query) use ($mode) {
            return $query->where(function ($query) use ($mode) {
                return $query->where('starter_project', (bool)$mode);
            });
        });

        // exclude users those projects which were clone from global starter project
        if (isset($data['exclude_starter']) && $data['exclude_starter']){
            $this->query = $this->query->where('is_user_starter', false);
        }

        // if specific index projects requested
        if (isset($data['indexing']) && $data['indexing']){
            $this->query = $this->query->where('indexing', $data['indexing']);
        }
        return $this->getDtPaginated(['users']);
    }

    /**
     * @param User $user
     * @param $project_id
     * @param $organization_id
     * @return string
     * @throws GeneralException
     */
    public function clone(User $user, $project_id, $organization_id = null): string
    {
        $project = $this->model->find($project_id);

        try {
            // resolving this object one-time
            // as it might only needed here - so no dependency injection in constructor
            CloneProject::dispatch($user, $project, $user->createToken('auth_token')->accessToken, $organization_id)->delay(now()->addSecond());
            // pushed cloning of project in background
            // resolve(ProjectRepositoryInterface::class)->clone($user, $project, request()->bearerToken());
            return 'User data updated and project is being cloned in background!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return 'Cloning failed.';
        }
    }

    /**
     * Update Indexes for projects and related models
     * @param $data
     * @return string
     * @throws GeneralException
     */
    public function updateIndexes($data): string
    {
        try {
            // get and update the projects indexing state
            $projects = $this->model->whereIn('id', $data['index_projects']);
            $projects->update(['indexing' => $data['index']]);

            // index the selected projects
            $this->indexProjects($data['index_projects'] ?? []);
            return 'Indexes updated successfully!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new GeneralException('Unable to update indexes, please try again later!');
        }
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
        $playlists = $this->playlistModel->whereIn('project_id', $projects)->get('id');

        // search-able is needed as on collections update observer will not get fired
        // so searchable will updated the elastic search index via scout package
        // update directly on query builder
        $this->model->whereIn('id', $projects)->searchable();

        // to fire observer update should be done on each single instance of models
        // $projects->each->update(); can do for firing observers on each model object - might need for elastic search
        // prepare the query builder from collections and perform update
        $this->playlistModel->whereIn('project_id', $projects)->searchable();

        // update related activities by getting keys of parent playlists
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->searchable();
    }

    /**
     * @return mixed
     */
    public function getStarterProjects()
    {
        return $this->model->where('starter_project', true)->get();
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
        $project->update(['indexing' => $index]);
        $this->indexProjects([$project->id]);
        return 'Index status changed successfully!';
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
     * @return string
     * @throws GeneralException
     */
    public function updateUserStarterFlag(): string
    {
        $starterProjects = $this->getStarterProjects(); // get all the starter projects
        if (!count($starterProjects)) {
            throw new GeneralException('Please first set at-least 1 starter project!');
        }
        // set the user starter flag to true if was cloned from global starter project
        $this->model->whereIn('cloned_from', $starterProjects->modelKeys())->update(['is_user_starter' => true]);
        return 'Existing records are updated successfully for user starter project flag.';
    }
}
