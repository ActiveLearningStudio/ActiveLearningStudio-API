<?php

namespace App\Repositories\LRSStatementsData;

use App\Models\LRSStatement;
use App\Repositories\BaseRepository;
use App\Repositories\LRSStatementsData\LRSStatementsDataRepositoryInterface;
use Illuminate\Support\Collection;

class LRSStatementsDataRepository extends BaseRepository implements LRSStatementsDataRepositoryInterface
{
    /**
     * LRSStatementsDataRepository constructor.
     *
     * @param LRSStatement $model
     */
    public function __construct(LRSStatement $model)
    {
        parent::__construct($model);
    }

    /**
     * Find Max value by field
     *
     * @param string $field
     * @return mixed
     */
    public function findMaxByField($field)
    {
        return $this->model->max($field);
    }
}
