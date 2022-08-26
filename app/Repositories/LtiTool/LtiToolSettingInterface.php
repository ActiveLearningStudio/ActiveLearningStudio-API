<?php

namespace App\Repositories\LtiTool;

use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Repositories\EloquentRepositoryInterface;

interface LtiToolSettingInterface extends EloquentRepositoryInterface
{
    /**
     * @param $userId integer, $orgId integer $mediaSourceId int
     * @return mixed
     */
    public function getRowRecordByUserOrgAndToolType($userId, $orgId, $mediaSourceId);
}
