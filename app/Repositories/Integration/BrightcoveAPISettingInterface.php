<?php
/**
 * @author        Asim Sarwar
 * Date           09-12-2021
 * Class          BrightcoveAPISettingInterface
 */
namespace App\Repositories\Integration;

use App\Models\Integration\BrightcoveAPISetting;
use App\Repositories\EloquentRepositoryInterface;

interface BrightcoveAPISettingInterface extends EloquentRepositoryInterface
{

    /**
     * To get list of brightcove account/setting   
     * @param  id
     * @return mixed
     * @throws GeneralException
     */
    public function getAccountListByOrg($suborganization);

    /**
     * To get row record of brightcove account/setting
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function getRowRecordByOrgId($suborganization, $id);
}
