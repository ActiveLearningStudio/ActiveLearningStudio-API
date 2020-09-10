<?php

namespace App\Repositories\Admin\Project;

use App\Exceptions\GeneralException;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\Admin\BaseRepository;
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
     * @return mixed
     */
    public function getProjects()
    {
        return $this->model->when(request()->q, function ($query) {
            return $query->where('name', 'ILIKE', '%' . request()->q . '%');
        })->where('is_public', true)->orderBy('created_at', 'desc')->paginate(100);
    }

    /************* IN PROGRESS ****************/
    /**
     * This function is not completed - [IN-PROGRESS]
     * @param $project_id
     * @throws GeneralException
     */
    public function clone($project_id)
    {
        try {
            $project = $this->model->find($project_id)->with(['playlists', 'playlists.activities']);
            if ($user = auth('api')->user()) {
                Log::info('here');
                // clone the project data
                $cloned_project = $user->projects()->create($project->except('mongo_userid', ['role' => 'owner']));
                Log::info($cloned_project);

                // loop through original project playlists
                foreach ($project->playlists as $playlist) {
                    // clone the playlist one by one
                    $cloned_playlist = $cloned_project->playlists()->create($playlist);
                    Log::info($cloned_playlist);
                    // loop through playlist activities
                    foreach ($playlist->activities as $activity) {
                        // clone the activity one by one
                        $cloned_playlist->activities()->create($activity);
                    }
                }
            }
            // update the user data
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
        throw new GeneralException('Cloning of the project failed!');
    }

    /**
     * Update Indexes for projects and related models
     * @param $projects
     * @return string
     * @throws GeneralException
     */
    public function updateIndexes($projects)
    {
        if ($projects) {
            try {
                // first de-index the all projects of the users
                $this->de_index_projects();

                // index the selected projects
                $this->index_projects($projects);

                return 'Indexes Updated Successfully!';
            } catch (\Exception $e) {
                Log::info($e->getMessage());
                throw new GeneralException('Unable to update indexes, please try again later!');
            }
        }
        throw new GeneralException('Projects not found!');
    }

    /**
     * @param $projects
     * De Indexing of the projects
     */
    public function de_index_projects($projects = []): void
    {
        $user = auth('api')->user();
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
