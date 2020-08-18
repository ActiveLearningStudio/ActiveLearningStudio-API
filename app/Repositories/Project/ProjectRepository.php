<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Support\Collection;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }
}
