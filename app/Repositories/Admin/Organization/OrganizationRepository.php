<?php

namespace App\Repositories\Admin\Organization;

use App\Exceptions\GeneralException;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Admin\Project\ProjectRepository;
use App\Repositories\Admin\User\UserRepository;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

/**
 * Class OrganizationRepository.
 */
class OrganizationRepository extends BaseRepository
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * OrganizationRepository constructor.
     *
     * @param Organization $model
     * @param ProjectRepository $projectRepository
     * @param UserRepository $userRepository
     */
    public function __construct(Organization $model, ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->model = $model;
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $this->setDtParams($data);
        $this->query = $this->model->when($data['q'] ?? null, function ($query) use ($data) {
            $query->search(['name'], $data['q']);
            return $query;
        });
        return $this->getDtPaginated(['parent']);
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        $user = $this->userRepository->find($data['admin_id']);

        try {
            // choosing this store path because old data is being read from this path
            if (isset($data['image'])) {
                $data['image'] = \Storage::url($data['image']->store('/public/organizations'));
            }

            if ($organization = $user->organizations()->create(Arr::except($data, ['admin_id']), ['organization_role_type_id' => 1])) {
                return ['message' => 'Organization created successfully!', 'data' => $organization];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create organization, please try again later!');
    }

    /**
     * @param $id
     * @param $data
     * @param $clone_project_id
     * @param $member_id
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data, $clone_project_id, $member_id)
    {
        $organization = $this->find($id);
        try {
            // choosing this store path because old data is being read from this path
            if (isset($data['image'])) {
                $data['image'] = \Storage::url($data['image']->store('/public/uploads'));
            }

            // update the organization data
            if ($organization->update($data)) {
                if ($member_id) {
                    $organization->users()->attach($member_id, ['organization_role_type_id' => 3]);
                }

                if ($clone_project_id) {
                    $user = $organization->users()->wherePivot('organization_role_type_id', 1)->first();
                    // if clone project id provided - clone the project
                    $this->projectRepository->clone($user, $clone_project_id, $organization->id);
                    return ['message' => 'Organization data updated and project is being cloned in background!', 'data' => $this->find($id)];
                }
            }
            return ['message' => 'Organization data updated successfully!', 'data' => $this->find($id)];
        } catch (\Exception $e) {
            Log::critical('Clone Project ID: ' . $clone_project_id);
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update organization, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($organization = $this->model->whereId($id)->with(['projects', 'parent', 'users'])->first()) {
            return $organization;
        }
        throw new GeneralException('Organization Not found.');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        try {
            $this->model->find($id)->delete();
            return 'Organization Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete organization, please try again later!');
    }

    /**
     * Organizations basic report, projects, playlists and activities count
     * @param $data
     * @return mixed
     */
    public function reportBasic($data)
    {
        $this->setDtParams($data);
        $this->query = $this->model->select(['id', 'name', 'description', 'parent_id'])->withCount(['projects', 'playlists', 'activities']);
        return $this->getDtPaginated();
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function getParentOptions($data, $id)
    {
        if ($organization = $this->model->whereId($id)->first()) {
            $notInIds = $this->getIds($organization);

            $this->query = $this->model->when($data['q'] ?? null, function ($query) use ($data) {
                $query->search(['name'], $data['q']);
                return $query;
            });

            return $this->query->whereNotIn('id', $notInIds)->paginate();
        }

        throw new GeneralException('Organization Not found.');
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function getMemberOptions($data, $id)
    {
        if ($organization = $this->model->whereId($id)->first()) {
            $notInIds = $organization->users->modelKeys();

            $this->query = $this->userRepository->model->when($data['q'] ?? null, function ($query) use ($data) {
                $query->search(['email'], $data['q']);
                return $query;
            });

            return $this->query->whereNotIn('id', $notInIds)->orderBy('first_name', 'asc')->paginate();
        }

        throw new GeneralException('User Not found.');
    }

    /**
     * @param $organization
     * @return array
     * @throws GeneralException
     */
    public function getIds($organization)
    {
        $ids =  [$organization->id];
        foreach ($organization->children as $child) {
            $ids = array_merge($ids, $this->getIds($child));
        }
        return $ids;
    }

    /**
     * Delete the specified user in a particular suborganization
     *
     * @param $orgId
     * @param $usrId
     * @return mixed
     * @throws GeneralException
     */
    public function deleteUser($orgId, $usrId)
    {
        try {
            $this->model->find($orgId)->users()->detach($usrId);
            return 'Organization User Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete organization user, please try again later!');
    }
}
