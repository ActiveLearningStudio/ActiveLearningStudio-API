<?php

namespace App\Repositories\Admin\User;

use App\Exceptions\GeneralException;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Admin\Project\ProjectRepository;
use App\User;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * UserRepository constructor.
     *
     * @param User $model
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
        request()->request->add(['page' => ($start / $length) + 1]);
        return $this->model->when(isset(request()->order[0]['dir']), function ($query) {
            return $query->orderBy(request()->columns[request()->order[0]['column']]['name'], request()->order[0]['dir']);
        })->when(isset(request()->search['value']) && request()->search['value'], function ($query) {
            foreach (request()->columns as $column) {
                if ($column['searchable'] && $column['searchable'] !== 'false') {
                    $query->orWhere($column['name'], 'LIKE', '%' . request()->search['value'] . '%');
                }
            }
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
            return $this->model->create($data);
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
        if($user->update($data) && $clone_project_id){
            // if clone project id provided - clone the project
          return $this->projectRepository->clone($id, $clone_project_id);
        }
        return 'User data updated!';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if ($user = $this->model->whereId($id)->with('projects')->first()){
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
