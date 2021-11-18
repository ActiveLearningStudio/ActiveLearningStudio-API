<?php

namespace App\Repositories\LtiTool;

use App\Repositories\EloquentRepositoryInterface;

interface LtiToolSettingInterface extends EloquentRepositoryInterface
{
    /**
     * To clone Lti Tool Setting
     * @param $ltiToolSetting
     * @param $subOrganization
     * @param $token
     */
    public function clone($ltiToolSetting, $subOrganization, $token);
}
