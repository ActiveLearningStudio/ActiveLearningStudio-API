<?php

namespace App\Repositories\MicrosoftTeam;

use App\User;
use App\Models\Organization;
use App\Repositories\EloquentRepositoryInterface;

interface MicrosoftTeamRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $code string
     * @return string
     */
    public function getToken($code);

    /**
    * @param $gid int 
    * @return string
    */
   public function getLoginUrl($gid);

   /**
    * @param $token string 
    * @param $data array
    *
    * @return 
    */
    public function getClassesList($token);

    /**
    * @param $token string 
    * @param $data array
    *
    * @return int
    */
    public function createMsTeamClass($token, $data);

    /**
    * @param $token string
    * @param $classId string
    * @param $project Project
    * @param $aSyncUrl string
    */
    public function createMSTeamAssignment($token, $classId, $project, $aSyncUrl);

    /**
    * @param $token string
    * @param $classId string
    * @param $activity Activity
    * @param $aSyncUrl string
    */
    public function createMSTeamIndependentActivityAssignment($token, $classId, $activity, $aSyncUrl);
}
