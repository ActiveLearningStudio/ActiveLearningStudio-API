<?php

namespace App\Repositories\Admin\Project;

use App\Exceptions\GeneralException;
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
        $q = $data['q'] ?? null;
        $mode = $data['mode'] ?? null;
        $this->setDtParams($data);
        $this->query = $this->model->when($q, function ($query) use ($q) {
            $query->where(function ($query) use ($q) {
                // get projects by name or email
                $query->orWhereHas('users', function ($query) use ($q) {
                    return $query->where('email', 'ILIKE', '%' . $q . '%');
                });
                return $query->orWhere('name', 'ILIKE', '%' . $q . '%');
            });
        })->when($mode && $mode !== 'all', function ($query) use ($mode) {
            return $query->where(function ($query) use ($mode) {
                return $query->where('starter_project', $mode);
            });
        });
        return $this->getDtPaginated(['users']);
    }

    /**
     * @param User $user
     * @param $project_id
     * @return string
     * @throws GeneralException
     */
    public function clone(User $user, $project_id): string
    {
        $project = $this->model->find($project_id);
        $pivot_data = $project->users->find($user->id);
        $linked_user_id = $pivot_data ? $pivot_data->pivot->value('user_id') : 0;
        if ((int)$user->id === $linked_user_id) {
            throw new GeneralException('Project already linked to this user.');
        }
        try {
            // resolving this object one-time
            // as it might only needed here - so no dependency injection in constructor
            resolve(ProjectRepositoryInterface::class)->clone($user, $project, request()->bearerToken());
            return 'User data updated and project cloning successful!';
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
            // first de-index the projects of the users
            $this->removeProjectsIndex($data['remove_index_projects'] ?? []);
            // index the selected projects
            $this->indexProjects($data['index_projects'] ?? []);

            return 'Indexes updated successfully!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new GeneralException('Unable to update indexes, please try again later!');
        }
    }

    /**
     * @param array $projects
     * De Indexing of the projects
     * @param string $key
     */
    public function removeProjectsIndex($projects, $key = 'elasticsearch'): void
    {
        if (empty($projects)) {
            return;
        }
        $playlists = $this->playlistModel->whereIn('project_id', $projects)->get('id');

        // elasticsearch set false for projects and related models
        $this->model->whereIn('id', $projects)->update([$key => false]);
        $this->playlistModel->whereIn('project_id', $projects)->update([$key => false]);
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->update([$key => false]);

        // scout searchable update the indexes
        $this->model->whereIn('id', $projects)->searchable();
        $this->playlistModel->whereIn('project_id', $projects)->searchable();
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->searchable();
    }

    /**
     * @param $projects
     * Indexing of the projects
     * @param string $key
     */
    public function indexProjects($projects, $key = 'elasticsearch'): void
    {
        if (empty($projects)) {
            return;
        }
        // search-able is needed as on collections update observer will not get fired
        // so searchable will updated the elastic search index via scout package
        // update directly on query builder
        $this->model->whereIn('id', $projects)->update([$key => true]);
        $this->model->whereIn('id', $projects)->searchable();
        // to fire observer update should be done on each single instance of models
        // $projects->each->update(); can do for firing observers on each model object - might need for elastic search

        // first get the collection of playlists - needed in activities update
        $playlists = $this->playlistModel->whereIn('project_id', $projects)->get('id');

        // prepare the query builder from collections and perform update
        $this->playlistModel->whereIn('project_id', $projects)->update([$key => true]);
        $this->playlistModel->whereIn('project_id', $projects)->searchable();

        // update related activities by getting keys of parent playlists
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->update([$key => true]);
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
     * @return string
     */
    public function updateIndex($project): string
    {
        if ($project->elasticsearch) {
            $this->removeProjectsIndex([$project->id]);
        } else {
            $this->indexProjects([$project->id]);
        }
        return 'Index status changed successfully!';
    }

    /**
     * @param $project
     * @return string
     */
    public function togglePublicStatus($project): string
    {
        if ($project->is_public) {
            $this->removeProjectsIndex([$project->id], 'is_public');
        } else {
            $this->indexProjects([$project->id], 'is_public');
        }
        return 'Public status toggled successfully!';
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
}
