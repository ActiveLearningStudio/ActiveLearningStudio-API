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
     * @param $code string
     * @return string
     */
    public function getTokenViaCode($code);

    /**
     * @param $request object
     * @return array
     */ 

    public function getTokenOnBehalfOf($request);

    /**
     * @param $object
     * @return string
     */
    public function getSubmission($request);

    /**
     * @param $object
     * @return string
     */
    public function submitAssignment($request);

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
    * @return 
    */
    public function getUserProfile($token);

    /**
    * @param $token string 
    * @param $data array
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
    * @param $playlist Playlist
    * @param $aSyncUrl string
    */
    public function createMSTeamAssignmentPlaylist($token, $classId, $playlist, $aSyncUrl);

    /**
    * @param $token string
    * @param $classId string
    * @param $activity Activity
    * @param $aSyncUrl string
    */
    public function createMSTeamIndependentActivityAssignment($token, $classId, $activity, $aSyncUrl);
}
