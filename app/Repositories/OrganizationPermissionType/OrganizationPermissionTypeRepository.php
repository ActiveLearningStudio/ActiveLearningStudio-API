<?php

namespace App\Repositories\OrganizationPermissionType;

use App\Models\OrganizationPermissionType;
use App\Repositories\OrganizationPermissionType\OrganizationPermissionTypeRepositoryInterface;
use App\Repositories\BaseRepository;

class OrganizationPermissionTypeRepository extends BaseRepository implements OrganizationPermissionTypeRepositoryInterface
{
    /**
     * Organization Repository constructor.
     *
     * @param OrganizationPermissionType $model
     */
    public function __construct(OrganizationPermissionType $model)
    {
        parent::__construct($model);
    }

    /**
     * To fetch organization permission types
     *
     * @param $data
     * @return Model
     */
    public function fetchOrganizationPermissionTypes($data)
    {
        return$this->model
            ->when($data['query'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['query'] . '%');
                return $query;
            })
            ->orderBy('name', 'asc')
            ->get();
    }
}
