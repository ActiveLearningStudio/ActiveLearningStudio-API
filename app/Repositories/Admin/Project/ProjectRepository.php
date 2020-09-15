<?php

namespace App\Repositories\Admin\Project;

use App\Exceptions\GeneralException;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Admin\User\UserRepository;
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
     * @var User
     */
    private $userModel;

    /**
     * ProjectRepository constructor.
     * @param Project $model
     * @param Playlist $playlistModel
     * @param Activity $activityModel
     * @param User $userModel
     */
    public function __construct(Project $model, Playlist $playlistModel, Activity $activityModel, User $userModel)
    {
        $this->model = $model;
        $this->playlistModel = $playlistModel;
        $this->activityModel = $activityModel;
        $this->userModel = $userModel;
    }

    /**
     * @return mixed
     */
    public function getProjects()
    {
        return $this->model->when(request()->q, function ($query) {
            return $query->where('name', 'ILIKE', '%' . request()->q . '%');
        })->where('is_public', true)->orderBy('created_at', 'desc')->paginate(100);
    }

    /**
     * @param User $user
     * @param $project_id
     * @return string
     * @throws GeneralException
     */
    public function clone(User $user, $project_id)
    {
        $project = $this->model->find($project_id);
        $pivot_data = $project->users->find($user->id);
        $linked_user_id = $pivot_data ? $pivot_data->pivot->value('user_id') : 0;
        if ((int)$user->id === $linked_user_id) {
            throw new GeneralException('Project already linked to this user');
        }
        try {
            // adding user model to request - because use existing clone method
            request()->request->add(['clone_user' => $user]);
            // resolving this object one-time
            // as it might only needed here - so no dependency injection in constructor
            resolve(ProjectRepositoryInterface::class)->clone(request(), $project);
            return 'User data updated and Project cloning successful!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * Update Indexes for projects and related models
     * @param $projects
     * @param $user_id
     * @return string
     * @throws GeneralException
     */
    public function updateIndexes($projects, $user_id)
    {
        try {
            // first de-index the all projects of the users
            $this->de_index_projects([], $user_id);

            if ($projects) {
                // index the selected projects
                $this->index_projects($projects);
            }
            return 'Indexes Updated Successfully!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException('Unable to update indexes, please try again later!');
        }
    }

    /**
     * @param array $projects
     * De Indexing of the projects
     * @param string $user_id
     * @throws GeneralException
     */
    public function de_index_projects($projects = [], $user_id = ''): void
    {
        $user = $user_id ? $this->userModel->find($user_id) : auth()->user();

        // get current user projects - if specific project IDs are not provided
        $user_all_projects = !empty($projects) ? $projects : $user->projects->modelKeys();
        $playlists = $this->playlistModel->whereIn('project_id', $user_all_projects)->get('id');

        // elasticsearch set false for projects and related models
        $this->model->whereIn('id', $user_all_projects)->update(['elasticsearch' => false]);
        $playlists->toQuery()->update(['elasticsearch' => false]);
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->update(['elasticsearch' => false]);

        // scout searchable update the indexes
        $this->model->whereIn('id', $user_all_projects)->searchable();
        $this->playlistModel->whereIn('project_id', $user_all_projects)->searchable();
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->searchable();
    }

    /**
     * @param $projects
     * Indexing of the projects
     */
    public function index_projects($projects): void
    {
        // search-able is needed as on collections update observer will not get fired
        // so searchable will updated the elastic search index via scout package
        // update directly on query builder
        $this->model->whereIn('id', $projects)->update(['elasticsearch' => true]);
        $this->model->whereIn('id', $projects)->searchable();
        // to fire observer update should be done on each single instance of models
        // $projects->each->update(); can do for firing observers on each model object - might need for elastic search

        // first get the collection of playlists - needed in activities update
        $playlists = $this->playlistModel->whereIn('project_id', $projects)->get('id');

        // prepare the query builder from collections and perform update
        $playlists->toQuery()->update(['elasticsearch' => true]);
        $this->playlistModel->whereIn('project_id', $projects)->searchable();

        // update related activities by getting keys of parent playlists
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->update(['elasticsearch' => true]);
        $this->activityModel->whereIn('playlist_id', $playlists->modelKeys())->searchable();
    }

}
