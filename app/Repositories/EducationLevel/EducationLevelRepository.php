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
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        $query = $this->model;
        $q = $data['query'] ?? null;

        if ($q) {
            $query = $query->where('name', 'iLIKE', '%' .$q. '%');
        }

        return $query->orderBy('order', 'ASC')->paginate($perPage)->withQueryString();
    }
}
