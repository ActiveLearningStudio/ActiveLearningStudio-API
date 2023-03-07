<?php

namespace App\Repositories\LtiTool;

use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Repositories\EloquentRepositoryInterface;

interface LtiToolSettingInterface extends EloquentRepositoryInterface
{
    
    /**
     * To get row record by org and tool type match
     *
     * @param $orgId integer
     * @param $mediaSourcesId int
     * @return object
     * @throws GeneralException
     */
    public function getRowRecordByOrgAndToolType($orgId, $mediaSourcesId);

    /**
     * To get row record by org and lti_tool_type_id match
     *
     * @param $orgId integer
     * @param $ltiToolTypeId int
     * @return object
     * @throws GeneralException
     */
    public function getRowRecordByColumnMatch($orgId, $ltiToolTypeId);
}
