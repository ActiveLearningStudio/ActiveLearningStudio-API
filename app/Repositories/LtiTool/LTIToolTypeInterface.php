<?php

namespace App\Repositories\LtiTool;

use App\Repositories\EloquentRepositoryInterface;

interface LTIToolTypeInterface extends EloquentRepositoryInterface
{
    /**
     * To get lti tool type id by name
     *
     * @param $ltiToolTypeName string
     * @return int
     */
    public function getLTIToolTypeIdByName($ltiToolTypeName);
}
