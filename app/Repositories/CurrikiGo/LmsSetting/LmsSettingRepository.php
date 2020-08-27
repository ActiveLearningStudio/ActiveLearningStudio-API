<?php

namespace App\Repositories\CurrikiGo\LmsSetting;

use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\BaseRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Support\Collection;

class LmsSettingRepository extends BaseRepository implements LmsSettingRepositoryInterface
{
    /**
     * LmsSettingRepository constructor.
     *
     * @param LmsSetting $model
     */
    public function __construct(LmsSetting $model)
    {
        parent::__construct($model);
    }

    /**
     * LmsSettingRepository constructor.
     *
     * @param LmsSetting $model
     */
    public function fetchAllByUserId($user_id)
    {
        return $this->model->where(['user_id' => $user_id])->get();
    }
}
