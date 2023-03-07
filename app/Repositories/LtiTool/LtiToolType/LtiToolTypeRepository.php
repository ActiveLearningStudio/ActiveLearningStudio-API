<?php

namespace App\Repositories\LtiTool\LtiToolType;

use App\Repositories\BaseRepository;
use App\Models\LtiTool\LtiToolType;

class LtiToolTypeRepository extends BaseRepository implements LTIToolTypeInterface
{

    /**
     * LTIToolTypeRepository constructor
     * @param LtiToolType $model
     */
    public function __construct(LtiToolType $model)
    {
        $this->model = $model;
    }

    /**
     * To get lti tool type id by name
     *
     * @param $ltiToolTypeName string
     * @return int
     */
    public function getLtiToolTypeIdByName($ltiToolTypeName)
    {
        $ltiToolTypeRow = $this->model->where('name', $ltiToolTypeName)->first();
        $ltiToolTypeId = !empty($ltiToolTypeRow) ? $ltiToolTypeRow->id : 0;
        return $ltiToolTypeId;
    }
    
}
