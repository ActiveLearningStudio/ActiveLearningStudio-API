<?php

namespace App\Repositories\OrganizationRoleType;

use App\Models\OrganizationRoleType;
use App\Repositories\OrganizationRoleType\OrganizationRoleTypeRepositoryInterface;
use App\Repositories\BaseRepository;

class OrganizationRoleTypeRepository extends BaseRepository implements OrganizationRoleTypeRepositoryInterface
{
    /**
     * Organization role type repository constructor.
     *
     * @param OrganizationRoleType $model
     */
    public function __construct(OrganizationRoleType $model)
    {
        parent::__construct($model);
    }
}
