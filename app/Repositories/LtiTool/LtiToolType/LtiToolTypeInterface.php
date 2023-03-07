<?php

namespace App\Repositories\LtiTool\LtiToolType;

use App\Repositories\EloquentRepositoryInterface;

interface LtiToolTypeInterface extends EloquentRepositoryInterface
{
    /**
     * To get lti tool type id by name
     *
     * @param $ltiToolTypeName string
     * @return int
     */
    public function getLTIToolTypeIdByName($ltiToolTypeName);
}
