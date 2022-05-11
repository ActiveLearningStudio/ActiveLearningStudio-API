<?php

namespace App\Repositories\Subject;

use App\Models\Subject;
use App\Repositories\BaseRepository;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    /**
     * SubjectRepository constructor.
     *
     * @param Subject $model
     */
    public function __construct(Subject $model)
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

        return $query->where('organization_id', $suborganization->id)->paginate($perPage)->withQueryString();
    }

    /**
     * @param $subjectIds
     *
     * @return mixed
     */
    public function getSubjectIdsWithMatchingName($subjectIds)
    {
        $subjectNames = $this->model->whereIn('id', $subjectIds)->pluck('name');
        return $this->model->whereIn('name', $subjectNames)->pluck('id')->toArray();
    }
}
