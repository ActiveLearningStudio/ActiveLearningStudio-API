<?php

namespace App\Repositories\Admin\User;

use App\Exceptions\GeneralException;
use App\Jobs\AssignStarterProjects;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Admin\Project\ProjectRepository;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    use DispatchesJobs;

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     * @param ProjectRepository $projectRepository
     */
    public function __construct(User $model, ProjectRepository $projectRepository)
    {
        $this->model = $model;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param int $start
     * @param int $length
     * @return mixed
     */
    public function getUsers($start = 0, $length = 25)
    {
        // calculate page size if not present in request
        if (!request()->has('page')) {
            $page = empty($length) ? 0 : ($start / $length);
            request()->request->add(['page' => $page + 1]);
        }
        // search through each column and sort by - Needed for datatables calls
        return $this->model->when(isset(request()->order[0]['dir']), function ($query) {
            return $query->orderBy(request()->columns[request()->order[0]['column']]['name'], request()->order[0]['dir']);
        })->when(isset(request()->search['value']) && request()->search['value'], function ($query) {
            return $query->search(request()->columns, request()->search['value']);
        })->when(request()->q, function ($query) {
            $query->search(['email', 'name'], request()->q);
            $query->name( request()->q );
            return $query;
        })->paginate($length);
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function createUser($data)
    {
        try {
            $user = $this->model->create($data);
//            $this->dispatch((new AssignStarterProjects($user))); For now - not needed
            return $user;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function updateUser($id, $data, $clone_project_id)
    {
        $user = $this->find($id);
        // update the user data
        if ($user->update($data) && $clone_project_id) {
            // if clone project id provided - clone the project
            return $this->projectRepository->clone($user, $clone_project_id);
        }
        return 'User data updated!';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if ($user = $this->model->whereId($id)->with('projects')->first()) {
            return $user;
        }
        throw new GeneralException('User Not found.');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroyUser($id)
    {
        if ((int)$id === auth()->id()) {
            throw new GeneralException('You cannot delete your own user');
        }
        try {
            $this->model->find($id)->delete();
            return 'User Deleted!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }
}
