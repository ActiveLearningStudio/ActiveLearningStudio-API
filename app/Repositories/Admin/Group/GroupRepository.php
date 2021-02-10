<?php

namespace App\Repositories\Admin\Group;

use App\Exceptions\GeneralException;
use App\Repositories\Admin\BaseRepository;
use App\Models\Group;
use Illuminate\Support\Facades\Log;

/**
 * Class GroupRepository.
 */
class GroupRepository extends BaseRepository
{
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * GroupRepository constructor.
     *
     * @param Group $model
     */
    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        //$this->setDtParams($data);
        //print_r($data);die;
        $this->model->get();

        // $this->query = $this->model->when($data['q'] ?? null, function ($query) use ($data) {
        //     $query->search(['name', 'description'], $data['q']);
        //     $query->name($data['q']);
        //     return $query;
        // });

        return $this->getDtPaginated();
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            $data['deleted_at'] = null; // if soft deleted group add again
            // with trashed is added so soft-deletes records are also checked before creating new one
            if ($group = $this->model->withTrashed()->updateOrCreate(['name' => $data['name']], $data)) {
                return ['message' => 'Group created successfully!', 'data' => $group];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create group, please try again later!');
    }

    /**
     * @param $id
     * @param $data
     * @param $clone_project_id
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data, $clone_project_id)
    {
        $user = $this->find($id);
        try {
            // update the user data
            if ($user->update($data) && $clone_project_id) {
                // if clone project id provided - clone the project
                $this->projectRepository->clone($user, $clone_project_id);
                return ['message' => 'User data updated and project is being cloned in background!', 'data' => $this->find($id)];
            }
            return ['message' => 'User data updated successfully!', 'data' => $this->find($id)];
        } catch (\Exception $e) {
            Log::critical('Clone Project ID: ' . $clone_project_id);
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update user, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($user = $this->model->whereId($id)->first()) {
            return $user;
        }
        throw new GeneralException('User not found.');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        try {
            $this->find($id)->delete();
            return 'Group Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete group, please try again later!');
    }

}
