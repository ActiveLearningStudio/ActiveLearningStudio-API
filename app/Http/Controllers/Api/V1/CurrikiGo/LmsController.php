<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CurrikiGo\LmsSettingCollection;
use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Http\Resources\V1\ProjectPublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Redirect;

/**
 * @group  LMS Settings
 * @authenticated
 *
 * APIs for LMS settings used for publishing
 */
class LmsController extends Controller
{
    private $lmsSettingRepository;
    private $projectRepository;
    
    /**
     * LmsController constructor.
     *
     * @param $lmsSettingRepository LmsSettingRepositoryInterface
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository, ProjectRepositoryInterface $projectRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Projects based on LMS settings LTI client_id 
     * 
     * Display a listing of user Projects that blong it same LMS/LTI settings
     *
     * @param Request $request
     * @return Response
     */
    public function projects(Request $request)
    {
        $validator = Validator::make($request->all(), ['lms_url' => 'required', 'lti_client_id' => 'required']);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response(['error' => $messages], 400);
        }
        
        $lms_url = $request->input('lms_url');
        $lti_client_id = $request->input('lti_client_id');
        $projects = $this->projectRepository->fetchByLmsUrlAndLtiClient($lms_url, $lti_client_id);
        
        return response([
            'projects' => ProjectPublicResource::collection($projects),
        ], 200);
        
    }
}
