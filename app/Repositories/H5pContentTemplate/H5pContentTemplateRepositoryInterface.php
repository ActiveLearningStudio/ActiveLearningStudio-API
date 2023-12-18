<?php

namespace App\Repositories\H5pContentTemplate;

use App\Repositories\EloquentRepositoryInterface;

interface H5pContentTemplateRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Get the h5p content template by Library
     *
     * @param string $library library name
     * @return
     */
    public function getH5pContentTemplate($library);
}
