<?php

namespace App\Repositories\UiModule;

use App\Repositories\EloquentRepositoryInterface;

interface UiModuleRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get a list of the top UI Modules.
     *
     * @return mixed
     */
    public function getTopUIModules();
}
