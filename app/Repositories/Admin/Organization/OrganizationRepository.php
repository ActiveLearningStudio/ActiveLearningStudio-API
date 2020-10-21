<?php

namespace App\Repositories\Admin\Organization;

use App\Exceptions\GeneralException;
use App\Repositories\Admin\BaseRepository;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;

/**
 * Class OrganizationRepository.
 */
class OrganizationRepository extends BaseRepository
{
    /**
     * OrganizationRepository constructor.
     *
     * @param Organization $model
     */
    public function __construct(Organization $model)
    {
        $this->model = $model;
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
            if ($organization = $this->model->create($data)) {
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
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        $organization = $this->find($id);
        try {
            if ($organization->update($data)) {
                return ['message' => 'Organization data updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
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
        if ($organization = $this->model->whereId($id)->with('projects')->first()) {
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
}
