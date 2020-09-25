<?php

namespace App\Repositories\Admin\Organisation;

use App\Exceptions\GeneralException;
use App\Repositories\Admin\BaseRepository;
use App\Models\Organisation;
use Illuminate\Support\Facades\Log;

/**
 * Class OrganisationRepository.
 */
class OrganisationRepository extends BaseRepository
{
    /**
     * OrganisationRepository constructor.
     *
     * @param Organisation $model
     */
    public function __construct(Organisation $model)
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
            if ($organisation = $this->model->create($data)) {
                return ['message' => 'Organisation created successfully!', 'data' => $organisation];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create organisation, please try again later!');
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        $organisation = $this->find($id);
        try {
            if ($organisation->update($data)) {
                return ['message' => 'Organisation data updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update organisation, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($organisation = $this->model->whereId($id)->with('projects')->first()) {
            return $organisation;
        }
        throw new GeneralException('Organisation Not found.');
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
            return 'Organisation Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete organisation, please try again later!');
    }

    /**
     * Organisations basic report, projects, playlists and activities count
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
