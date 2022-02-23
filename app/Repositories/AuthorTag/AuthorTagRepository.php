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
