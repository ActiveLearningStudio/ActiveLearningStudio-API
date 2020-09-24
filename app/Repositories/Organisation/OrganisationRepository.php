<?php

namespace App\Repositories\Organisation;

use App\Models\Organisation;
use App\Repositories\Organisation\OrganisationRepositoryInterface;
use App\Repositories\BaseRepository;

class OrganisationRepository extends BaseRepository implements OrganisationRepositoryInterface
{
    /**
     * Organisation Repository constructor.
     *
     * @param Organisation $model
     */
    public function __construct(Organisation $model)
    {
        parent::__construct($model);
    }
}
