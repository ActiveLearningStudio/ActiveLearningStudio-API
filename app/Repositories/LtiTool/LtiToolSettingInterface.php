<?php

namespace App\Repositories\LtiTool;

use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Repositories\EloquentRepositoryInterface;

interface LtiToolSettingInterface extends EloquentRepositoryInterface
{
    /**
     * To clone Lti Tool Setting
     * @param LtiToolSetting $ltiToolSetting
     * @param Organization $subOrganization
     * @param $userId
     */
    public function clone(LtiToolSetting $ltiToolSetting, Organization $subOrganization, $userId);
    /**
     * @param $userId integer, $orgId integer $mediaSourceId int
     * @return mixed
     */
    public function getRowRecordByUserOrgAndToolType($userId, $orgId, $mediaSourceId);
}
