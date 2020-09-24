<?php

namespace App\Repositories\Admin\User;

use App\Exceptions\GeneralException;
use App\Jobs\AssignStarterProjects;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Admin\Project\ProjectRepository;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $this->setDtParams($data);
        $this->query = $this->model->when($data['q'] ?? null, function ($query) use ($data) {
            $query->search(['email', 'name'], $data['q']);
            $query->name($data['q']);
            return $query;
        });
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
            $data['remember_token'] = Str::random(64);
            $data['email_verified_at'] = now();
            if ($user = $this->model->create($data)) {
                // $this->dispatch((new AssignStarterProjects($user))); For now - not needed
                return ['message' => 'User created successfully!', 'data' => $user];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create user, please try again later!');
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
                return ['message' => 'User data updated and project cloned successfully!', 'data' => $this->find($id)];
            }
            return ['message' => 'User data updated successfully!', 'data' => $this->find($id)];
        } catch (\Exception $e) {
            Log::critical('Clone Project ID: '. $clone_project_id);
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
    public function destroy($id)
    {
        if ((int)$id === auth()->id()) {
            throw new GeneralException('You cannot delete your own user');
        }
        try {
            $this->model->find($id)->delete();
            return 'User Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete user, please try again later!');
    }
}
