<?php

namespace App\Repositories\EducationLevel;

use App\Models\EducationLevel;
use App\Repositories\BaseRepository;

class EducationLevelRepository extends BaseRepository implements EducationLevelRepositoryInterface
{
    /**
     * EducationLevelRepository constructor.
     *
     * @param EducationLevel $model
     */
    public function __construct(EducationLevel $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $suborganization
     * @param $data
     *
     * @return mixed
     */
    public function getAll($suborganization, $data)
    {
        $query = $this->model;
        $q = $data['query'] ?? null;

        if ($q) {
            $query = $query->where('name', 'iLIKE', '%' .$q. '%');
        }

        if (isset($data['skipPagination']) && $data['skipPagination'] === 'true') {
            return $query->where('organization_id', $suborganization->id)->orderBy('order', 'ASC')->get();
        }

        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        if (isset($data['order_by_column'])) {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->where('organization_id', $suborganization->id)->orderBy('order', 'ASC')->paginate($perPage)->withQueryString();
    }

    /**
     * @param $educationLevelIds
     *
     * @return mixed
     */
    public function getEducationLevelIdsWithMatchingName($educationLevelIds)
    {
        $educationLevelNames = $this->model->whereIn('id', $educationLevelIds)->pluck('name');
        return $this->model->whereIn('name', $educationLevelNames)->pluck('id')->toArray();
    }
}
