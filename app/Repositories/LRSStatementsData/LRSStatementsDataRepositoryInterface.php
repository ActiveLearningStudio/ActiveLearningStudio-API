<?php

namespace App\Repositories\LRSStatementsData;

use App\Models\LRSStatement;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface LRSStatementsDataRepositoryInterface extends EloquentRepositoryInterface
{
    
    /**
     * @param string $field
     * @return mixed
     */
    public function findMaxByField($field);
}
