<?php

namespace App\Repositories\CurrikiGo\ContentUserDataGo;

use App\Models\CurrikiGo\ContentUserDataGo;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface ContentUserDataGoRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * find record based on composite key.
     *
     * @param ContentUserDataGo $model
     */
    public function fetchByCompositeKey($content_id, $user_id, $sub_content_id, $data_id, $submissionId);
}
