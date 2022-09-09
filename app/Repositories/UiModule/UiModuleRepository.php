<?php

namespace App\Repositories\UiModule;

use App\Models\UiModule;
use App\Repositories\UiModule\UiModuleRepositoryInterface;
use App\Repositories\BaseRepository;

class UiModuleRepository extends BaseRepository implements UiModuleRepositoryInterface
{
    /**
     * UI Module Repository constructor.
     *
     * @param UiModule $model
     */
    public function __construct(UiModule $model)
    {
        parent::__construct($model);
    }

    /**
     * Get a list of the top UI Modules.
     *
     * @return mixed
     */
    public function getTopUIModules()
    {
        return $this->model->whereNull('parent_id')->get();
    }
}
