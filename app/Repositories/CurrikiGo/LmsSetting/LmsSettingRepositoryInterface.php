<?php

namespace App\Repositories\CurrikiGo\LmsSetting;

use App\Models\CurrikiGo\LmsSetting;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface LmsSettingRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Fetch all settings by User ID
     *
     * @param int $user_id
     */
    public function fetchAllByUserId($user_id);
}
