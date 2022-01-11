<?php

namespace App\Repositories\AuthorTag;

use App\Models\AuthorTag;
use App\Repositories\BaseRepository;

class AuthorTagRepository extends BaseRepository implements AuthorTagRepositoryInterface
{
    /**
     * AuthorTagRepository constructor.
     *
     * @param AuthorTag $model
     */
    public function __construct(AuthorTag $model)
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
