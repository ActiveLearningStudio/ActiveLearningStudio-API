<?php
/**
 * @author        Asim Sarwar
 * Class          BrightcoveAPISettingInterface
 */
namespace App\Repositories\Integration;

use App\Models\Organization;
use App\Models\Integration\BrightcoveAPISetting;
use App\Repositories\EloquentRepositoryInterface;

interface BrightcoveAPISettingInterface extends EloquentRepositoryInterface
{

    /**
     * To get list of brightcove account/setting   
     * @param  integer $suborganization
     * @return mixed
     * @throws GeneralException
     */
    public function getAccountListByOrg($suborganization);

    /**
     * To get row record of brightcove account/setting
     * @param integer $suborganization, $id
     * @return mixed
     * @throws GeneralException
     */
    public function getRowRecordByOrgId($suborganization, $id);

}
