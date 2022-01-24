<?php
/**
 * @author        Asim Sarwar
 * Class          KalturaAPISettingInterface
 */
namespace App\Repositories\Integration;

use App\Models\Organization;
use App\Models\Integration\KalturaAPISetting;
use App\Repositories\EloquentRepositoryInterface;

interface KalturaAPISettingInterface extends EloquentRepositoryInterface
{

    /**
     * To get row record of kaltura account/setting
     * @param integer $suborganization, $id
     * @return mixed
     * @throws GeneralException
     */
    public function getRowRecordByOrgId($suborganization, $id);

    /**
     * To clone Kaltura API Setting
     * @param KalturaAPISetting $kalturaAPISetting
     * @param Organization $subOrganization
     * @param $token
     */
    public function clone(KalturaAPISetting $kalturaAPISetting, Organization $subOrganization, $token);
}
