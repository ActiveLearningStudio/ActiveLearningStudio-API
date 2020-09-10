<?php

namespace App\Repositories\Admin\Project;

use App\Exceptions\GeneralException;
use App\Models\Project;
use App\Repositories\Admin\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository.
 */
class ProjectRepository extends BaseRepository
{
    /**
     * ProjectRepository constructor.
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getProjects()
    {
        return $this->model->when(request()->q, function ($query) {
            return $query->where('name', 'ILIKE', '%' . request()->q . '%');
        })->where('is_public', true)->orderBy('created_at', 'desc')->paginate(100);
    }

}
