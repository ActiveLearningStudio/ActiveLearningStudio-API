<?php

namespace App\Repositories\H5pElasticsearchField;

use App\Models\H5pElasticsearchField;
use App\Repositories\BaseRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use Illuminate\Support\Collection;

class H5pElasticsearchFieldRepository extends BaseRepository implements H5pElasticsearchFieldRepositoryInterface
{
    /**
     * H5pElasticsearchFieldRepository constructor.
     *
     * @param H5pElasticsearchField $model
     */
    public function __construct(H5pElasticsearchField $model)
    {
        parent::__construct($model);
    }
}
