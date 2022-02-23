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
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        $query = $this->model;
        $q = $data['query'] ?? null;

        if ($q) {
            $query = $query->where('name', 'iLIKE', '%' .$q. '%');
        }

        return $query->where('organization_id', $suborganization->id)->orderBy('order', 'ASC')->paginate($perPage)->withQueryString();
    }
}
