<?php

namespace App\Repositories\LRSStatementsSummaryData;

use App\Models\LrsStatementSummaryData;
use App\Repositories\BaseRepository;
use App\Repositories\LRSStatementsSummaryData\LRSStatementsSummaryDataRepositoryInterface;
use Illuminate\Support\Collection;

class LRSStatementsSummaryDataRepository extends BaseRepository implements LRSStatementsSummaryDataRepositoryInterface
{
    /**
     * LRSStatementsSummaryDataRepository constructor.
     *
     * @param LRSStatement $model
     */
    public function __construct(LrsStatementSummaryData $model)
    {
        parent::__construct($model);
    }
}
