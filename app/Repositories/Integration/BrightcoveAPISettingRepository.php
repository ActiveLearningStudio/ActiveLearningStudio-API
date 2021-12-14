<?php
/**
 * @author        Asim Sarwar
 * Date           09-12-2021
 * Class          BrightcoveAPISettingRepository
 */
namespace App\Repositories\Integration;

use App\Exceptions\GeneralException;
use App\Models\Integration\BrightcoveAPISetting;
use App\Models\Organization;
use App\Repositories\BaseRepository;
use App\Repositories\Integration\BrightcoveAPISettingInterface;
use Illuminate\Support\Facades\Log;

class BrightcoveAPISettingRepository extends BaseRepository implements BrightcoveAPISettingInterface
{

    /**
     * BrightcoveAPISettingRepository constructor
     * @param BrightcoveAPISetting $model
     */
    public function __construct(BrightcoveAPISetting $model)
    {
        $this->model = $model;
    }

    /**
     * To get list of brightcove account/setting   
     * @param  id
     * @return mixed
     * @throws GeneralException
     */
    public function getAccountListByOrg($suborganization)
    {
        $query = $this->model->with(['user', 'organization']);
        $settings = $query->where('organization_id', $suborganization)->get();
        if (!$settings->isEmpty()) {
            return $settings;
        }
        throw new GeneralException('Brightcove account list not found.');
    }

    /**
     * To get row record of brightcove account/setting
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function getRowRecordByOrgId($suborganization, $id)
    {
        $setting = $this->model->where('organization_id', $suborganization)->find($id);
        if ($setting) {
            return $setting;
        }
        throw new GeneralException('Brightcove API setting not found.');
    }

}
