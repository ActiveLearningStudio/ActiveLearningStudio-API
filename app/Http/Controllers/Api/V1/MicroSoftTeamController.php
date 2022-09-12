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
use App\Models\Playlist;
use App\Models\Project;
use App\Models\Activity;
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
     * @bodyParam code string  The stringified of the GAPI authorization token JSON object
     *
     * @response {
     *   "message": "Access token has been saved successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Validation error: Access token is required"
     *   ]
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
	 * Get List of Classes
	 *
	 * Get List of Microsoft Team Classes
	 
     * @response  200 {
     *   "classes": Array of classes
     * }
     
     *
     * @param MSTeamCreateClassRequest $createClassRequest
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
     * @urlParam project required The Id of a project. Example: 9
     * @bodyParam displayName required string Name of the class. Example: Test Class
     * @bodyParam access_token string|null The stringified of the GAPI access token JSON object
     
     * @response  200 {
     *   "message": [
     *     "Class have been created successfully"
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
        $statusCode = $this->microsoftTeamRepository->createMsTeamClass($token, $data);

        if($statusCode === 202) {
            return response([
                'message' => 'Class have been created successfully',
            ], 200);
        }
        
        return response([
            'errors' => 'Something wrong happened.',
        ], 500);
        
    }

    /**
	 * Publish project
	 *
	 * Publish the project activities as an assignment
	 *
     * @urlParam MSTeamCreateAssignmentRequest $createAssignmentRequest required The Id of a project. Example: 9
     * @bodyParam classId required string Id of the class. Example: Test Class
     
     * @response  200 {
     *   "message": [
     *     "Class have been created successfully"
     *   ]
     * }
     
     *
     * @param MSTeamCreateClassRequest $createClassRequest
     * @param Project $project
     * @return Response
	 */
    public function publishProject(MSTeamCreateAssignmentRequest $createAssignmentRequest, Project $project)
    {
        $authUser = auth()->user();
        $token = $authUser->msteam_access_token;
        $classId = $createAssignmentRequest->get('classId');

        $this->microsoftTeamRepository->createMSTeamAssignment($token, $classId, $project);
        
        return response([
            'message' => 'Project has been published successfully',
        ], 200);
    }

    

    

    

    

    

    

    
}
