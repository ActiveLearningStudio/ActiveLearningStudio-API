<?php

namespace App\Repositories\Organization;

use App\Models\Organization;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\BaseRepository;

class OrganizationRepository extends BaseRepository implements OrganizationRepositoryInterface
{
    /**
     * Organization Repository constructor.
     *
     * @param Organization $model
     */
    public function __construct(Organization $model)
    {
        parent::__construct($model);
    }
}
