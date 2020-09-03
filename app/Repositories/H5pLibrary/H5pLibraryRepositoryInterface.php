<?php

namespace App\Repositories\H5pLibrary;

use Djoudi\LaravelH5p\Eloquents\H5pLibrary;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface H5pLibraryRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Get the libraries's fields semantics.
     *
     * @param Object $h5pLibraryObject
     * @return array
     */
    public function getFieldSemantics($h5pLibraryObject);
}
