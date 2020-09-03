<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ActivityRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get the search request
     *
     * @param  array  $data
     * @return Collection
     */
    public function searchForm($data);

    /**
     * Get the advance search request
     *
     * @param  array  $data
     * @return Collection
     */
    public function advanceSearchForm($data);

    /**
     * Get the H5P Elasticsearch Field Values.
     *
     * @param Object $h5pContent
     * @return array
     */
    public function getH5pElasticsearchFields($h5pContent);
}
