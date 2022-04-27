<?php

namespace App\Repositories\CurrikiGo\LmsSetting;

use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\BaseRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Playlist;
use App\Models\Activity;
use App\Http\Resources\V1\ActivityResource;


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

    /**
     * @param integer $projectId
     * @param string $activityParam
     * 
     * @return string
     */
    public function getActivityGrade($projectId, $activityParam)
    {
        $playlistId = Playlist::where('project_id', $projectId)->orderBy('order','asc')->limit(1)->first();
        
        $activity = Activity::where('playlist_id', $playlistId->id)->orderBy('order','asc')->limit(1)->first();
        
        $resource = new ActivityResource($activity);

        if ($resource->$activityParam->first()) {
            return $resource->$activityParam->first()->value('name');
        }
        return null;

    }
}
