<?php

/**
 * This File defines handlers for Microsoft Team classroom.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Repositories\MicrosoftTeam\MicrosoftTeamRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\V1\MSTeamCreateClassRequest;
use App\Http\Requests\V1\MSTeamCreateAssignmentRequest;
use App\Http\Requests\V1\MSSaveAccessTokenRequest;
use App\Models\IndependentActivity;
use App\Models\Playlist;
use App\Models\Project;
use App\Models\Activity;
use App\Jobs\PublishProject;
use App\Jobs\PublishIndependentActivity;
use App\User;
use Redirect;

/**
 *  Microsoft Team Classroom
 *
 * APIs for Microsoft Team Classroom
 */
class MicroSoftTeamController extends Controller
{
    /**
    * User repository object
    *
    * @var UserRepositoryInterface
    */
    private $userRepository;

    /**
     * MS Team repository object
     *
     * @var MicrosoftTeamRepositoryInterface
     */
    private $microsoftTeamRepository;

    /**
     * MicroSoftTeamController constructor.
     *
     * @param MicrosoftTeamRepositoryInterface $microsoftTeamRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(MicrosoftTeamRepositoryInterface $microsoftTeamRepository, UserRepositoryInterface $userRepository)
    {
        $this->microsoftTeamRepository = $microsoftTeamRepository;
        $this->userRepository = $userRepository;
    }

    /**
	 * Save Access Token
	 *
	 * Save MS Graph api access token in the database.
	 *
     * @urlParam gid string User id of current logged in user
     * @bodyParam code string The stringified of the GAPI authorization token JSON object
     * 
     * @response {
     *   "message": "Access token has been saved successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to save the token."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
	 */
    public function getAccessToken(Request $request)
    {
        if(!empty($request->get('code'))) {  
            $code = $request->get('code');
            $accessToken = $this->microsoftTeamRepository->getToken($code);
            
            $isUpdated = $this->userRepository->update([
                'msteam_access_token' => $accessToken
            ], $request->get('state'));
            
            if ($isUpdated) {
                return response([
                    'message' => 'Access token has been saved successfully.',
                ], 200);
            }
            return response([
                'errors' => ['Failed to save the token.'],
            ], 500);

        } else {
            $gid = $request->get('gid');
            $url = $this->microsoftTeamRepository->getLoginUrl($gid);
            
            return Redirect::to($url);
        }
   
    }

    /**
	 * Save Access Token
	 *
	 * Save GraphAPI access token in the database.
	 *
     * @bodyParam access_token string required The stringified of the GraphAPI access token JSON object
     *
     * @response {
     *   "message": "Access token has been saved successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to save the token."
     *   ]
     * }
     *
     * @param MSSaveAccessTokenRequest $accessTokenRequest
     * @return Response
	 */
    public function saveAccessToken(MSSaveAccessTokenRequest $accessTokenRequest)
    {
        $data = $accessTokenRequest->validated();
        $authUser = auth()->user();
        $isUpdated = $this->userRepository->update([
            'msteam_access_token' => $data['access_token']
        ], $authUser->id);

        if ($isUpdated) {
            return response([
                'message' => 'Access token has been saved successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to save the token.'],
        ], 500);
    }
    
    /**
	 * Get List of Classes
	 *
	 * Get List of Microsoft Team Classes
	 
     * @response  200 {
     *   "classes": Array of classes
     * }
     * @return Response
	 */
    public function getClasses(Request $request)
    {
        $authUser = auth()->user();
        $token = $authUser->msteam_access_token;
        
        return response([
            'classes' => $this->microsoftTeamRepository->getClassesList($token),
        ], 200);
    }

    /**
	 * Create a new Class
	 *
	 * Create a new Class/Team into Microsoft Team
	 *
     * @bodyParam displayName required string Name of the class. Example: Test Class
     * @bodyParam access_token string The stringified of the GAPI access token JSON object Example: 123
     * @response  200 {
     *   "message": [
     *     "Class have been created successfully"
     *   ]
     * }
     * * @response  500 {
     *   "errors": [
     *     "Something went wrong."
     *   ]
     * }
     *
     * @param MSTeamCreateClassRequest $createClassRequest
     * @return Response
	 */
    public function createMsTeamClass(MSTeamCreateClassRequest $createClassRequest)
    {
        $data = $createClassRequest->validated();
        $authUser = auth()->user();
        $token = $authUser->msteam_access_token;
        $response = json_decode($this->microsoftTeamRepository->createMsTeamClass($token, $data),true);
        
        if($response['code'] === 202) {
            return response([
                'message' => 'Class have been created successfully',
                'classId' => $response['classId'],
                'aSyncUrl'=> $response['aSyncURL'],
            ], 200);
        }
        
        return response([
            'errors' => 'Something went wrong.',
        ], 500);
        
    }

    /**
	 * Publish project
	 *
	 * Publish the project activities as an assignment
	 *
     * @urlParam Project $project required The Id of a project. Example: 9
     * @bodyParam classId optional string Id of the class. Example: bebe45d4-d0e6-4085-b418-e98a51db70c3
     *
     * @response  200 {
     *   "message": [
     *     "Your request to publish project [project->name] into MS Team has been received and is being processed.<br>You will be alerted in the notification section in the title bar when complete."
     *   ]
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "Project must be shared as we are temporarily publishing the shared link."
     *   ]
     * }
     * @param MSTeamCreateAssignmentRequest $createAssignmentRequest
     * @param Project $project
     * @return Response
	 */
    public function publishProject(MSTeamCreateAssignmentRequest $createAssignmentRequest, Project $project)
    {
        $data = $createAssignmentRequest->validated();

        if(!$project->shared) { // temporary check will remove it in future
            return response([
                'errors' => 'Project must be shared as we are temporarily publishing the shared link.',
            ], 500);
        }
        $classId = isset($data['classId']) ? $data['classId'] : '';
        
        // pushed publishing of project in background
        PublishProject::dispatch(auth()->user(), $project, $classId)->delay(now()->addSecond());

        return response([
            'message' =>  "Your request to publish project [$project->name] into MS Team has been received and is being processed. <br>
                            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
        
    }   

    /**
	 * Publish independent activity/activity
	 *
	 * Publish the independent activity or activity in playlist as an assignment
	 *
     * @urlParam Activity $activity required The Id of a activity. Example: 9
     * @bodyParam classId optional uuid Id of the class. Example: bebe45d4-d0e6-4085-b418-e98a51db70c3
     *
     * @response  200 {
     *   "message": [
     *     "Your request to publish activity [activity->title] into MS Team has been received and is being processed.<br>You will be alerted in the notification section in the title bar when complete."
     *   ]
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "Activity must be shared as we are temporarily publishing the shared link."
     *   ]
     * }
     * @param MSTeamCreateAssignmentRequest $createAssignmentRequest
     * @param Activity $activity
     * @return Response
	 */
    public function publishIndependentActivity(MSTeamCreateAssignmentRequest $createAssignmentRequest, Activity $activity)
    {
        $data = $createAssignmentRequest->validated();

        if(!$activity->shared) { // temporary check will remove it in future
            return response([
                'errors' => 'Activity must be shared as we are temporarily publishing the shared link.',
            ], 500);
        }
        $classId = isset($data['classId']) ? $data['classId'] : '';
        
        // pushed publishing of Activity in background
        PublishIndependentActivity::dispatch(auth()->user(), $activity, $classId)->delay(now()->addSecond());

        return response([
            'message' =>  "Your request to publish activity [$activity->title] into MS Team has been received and is being processed. <br>
                            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
        
    }
}
