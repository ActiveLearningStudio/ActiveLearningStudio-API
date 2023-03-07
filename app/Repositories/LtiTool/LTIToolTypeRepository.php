<?php

namespace App\Repositories\LtiTool;

use App\Repositories\BaseRepository;
use App\Models\LtiTool\LTIToolType;

class LTIToolTypeRepository extends BaseRepository implements LTIToolTypeInterface
{

    /**
     * LTIToolTypeRepository constructor
     * @param LTIToolType $model
     */
    public function __construct(LTIToolType $model)
    {
        $this->model = $model;
    }

    /**
     * To get lti tool type id by name
     *
     * @param $ltiToolTypeName string
     * @return int
     */
    public function getLTIToolTypeIdByName($ltiToolTypeName)
    {
        $ltiToolTypeRow = $this->model->where('name', $ltiToolTypeName)->first();
        $ltiToolTypeId = !empty($ltiToolTypeRow) ? $ltiToolTypeRow->id : 0;
        return $ltiToolTypeId;
    }
    
}
