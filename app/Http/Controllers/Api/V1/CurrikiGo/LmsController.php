<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\OrganizationSearchRequest;
use App\Http\Requests\V1\CurrikiGo\TeamsSearchRequest;
use App\Http\Resources\V1\ProjectPublicResource;
use App\Http\Resources\V1\TeamResource;
use App\Http\Resources\V1\SearchResource;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Http\Resources\V1\OrganizationResource;
use App\Models\CurrikiGo\LmsSetting;
use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\SaveTeacherData;
use App\Http\Requests\V1\IndependentActivityForDeeplink;
use App\Http\Resources\V1\IndependentActivityResource;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Models\Project;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\SaveStudentdataService;
use App\User;
use stdClass;

/**
 * @group 9. LMS Settings
 *
 * APIs for LMS settings used for publishing
 */
class LmsController extends Controller
{
    private $lmsSettingRepository;
    private $projectRepository;
    private $activityRepository;
    private $independentActivityRepository;

    /**
     * LmsController constructor.
     *
     * @param $lmsSettingRepository LmsSettingRepositoryInterface
     * @param $projectRepository ProjectRepositoryInterface
     * @param $activityRepository ActivityRepositoryInterface
     * @param $independentActivityRepository IndependentActivityRepositoryInterface
     */
    public function __construct(
        LmsSettingRepositoryInterface $lmsSettingRepository,
        ProjectRepositoryInterface $projectRepository,
        ActivityRepositoryInterface $activityRepository,
        IndependentActivityRepositoryInterface $independentActivityRepository
    )
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->projectRepository = $projectRepository;
        $this->activityRepository = $activityRepository;
        $this->independentActivityRepository = $independentActivityRepository;
    }

    /**
     * Get Projects based on LMS/LTI settings
     *
     * Get a list of projects that belong to the same LMS/LTI settings
     *
     * @bodyParam lms_url string required The url of a lms Example: quo
     * @bodyParam lti_client_id int required The Id of a lti client Example: 12
     *
     * @responseFile responses/project/projects.json
     *
     * @param Request $request
     * @param GoogleClassroomRepositoryInterface $googleClassroomRepository
     * @param UserRepositoryInterface $userRepository
     * @return Response
     */
    // TODO: need to update
    public function projects(Request $request, GoogleClassroomRepositoryInterface $googleClassroomRepository, UserRepositoryInterface $userRepository)
    {
        $validator = Validator::make($request->all(), [
            'lti_client_id' => 'required',
            'user_email' => 'required|email',
            'course_id' => 'required',
            'api_domain_url' => 'required',
            'course_name' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response(['error' => $messages], 400);
        }
        // format data to make compatible with saveData function
        $data = new stdClass();
        $data->issuerClient = $request->lti_client_id;
        $data->courseId = $request->course_id;
        $data->customApiDomainUrl = $request->api_domain_url;

        if (config('student-data.save_student_data') && $request->isLearner) {
            $data->studentId = $request->studentId;
            $data->customPersonNameGiven = $request->customPersonNameGiven;
            $data->customPersonNameFamily = $request->customPersonNameFamily;
            $service = new SaveStudentdataService();
            $service->saveStudentData($data);
        }

        $lmsSetting = $this->lmsSettingRepository->findByField('lti_client_id', $data->issuerClient);
        $lms_organization_id = null;

        if ($lmsSetting) {
            $lms_organization_id = $lmsSetting->organization_id;
            $duplicateRecord = $googleClassroomRepository->duplicateRecordValidation($data->courseId, $request->user_email);
            $userExists = $userRepository->findByField('email', $request->user_email);
            if (!$userExists) {
                $userExists = $userRepository->getFirstUser();
            }
            if (!$duplicateRecord) {
                $teacherInfo = new \stdClass();
                $teacherInfo->user_id = $userExists->id;
                $teacherInfo->id = $data->courseId;
                $teacherInfo->name = $request->course_name;
                $teacherInfo->alternateLink = $data->customApiDomainUrl . '/' . $data->courseId;
                $teacherInfo->curriki_teacher_email = $request->user_email;
                $teacherInfo->curriki_teacher_org = $lmsSetting->organization_id;
                $response[] = $googleClassroomRepository->saveCourseShareToGcClass($teacherInfo);
            }
        }

        //For new deeplink UI
        $searchTerm = $request->search_keyword ?? null;
        $projects = $this->projectRepository->fetchByLtiClientAndEmail(
            $request->input('lti_client_id'),
            $request->input('user_email'),
            $searchTerm,
            $lms_organization_id
        );

        return response([
            'projects' => SearchResource::collection($projects),
        ], 200);
    }

    public function project(Project $project) {
            return response([
                'project' => new ProjectPublicResource($project),
            ], 200);
    }

    public function activities(Request $request)
    {
        $request->validate([
            'query' => 'string|max:255',
            'from' => 'integer',
            'subject' => 'string|max:255',
            'level' => 'string|max:255',
            'start' => 'string|max:255',
            'end' => 'string|max:255',
            'author' => 'string|max:255',
            'private' => 'integer',
            'userEmail' => 'string|required|max:255',
            'ltiClientId' => 'string|required',
        ]);

        return response([
            'activities' => $this->activityRepository->ltiSearchForm($request),
        ], 200);
    }

    /**
     * Get organizations based on LMS/LTI settings
     *
     * Get a list of organizations that belong to the same LMS/LTI settings
     *
     * @bodyParam userEmail required The email of a user: quo
     * @bodyParam lti_client_id required The Id of a lti client Example: 12
     *
     * @responseFile responses/organization/organizations.json
     *
     * @param OrganizationSearchRequest $request
     * @return Response
     */
    public function organizations(OrganizationSearchRequest $request)
    {
        $verifyValidCall = LmsSetting::where('lti_client_id', $request->ltiClientId)->where('lms_login_id', 'ilike', $request->userEmail)->count();
        if ($verifyValidCall) {
            $user = User::where('email', $request->input('userEmail'))->first();
            $organizations = OrganizationResource::collection($user->organizations()->with('parent')->get());
            
            return response([
                'organizations' => $organizations,
            ], 200);
        }
        return response([
            'organizations' => [],
        ], 400);
    }
    
    /**
     * Get teams based on LMS/LTI settings
     *
     * Get a list of teams that belong to the same LMS/LTI settings
     *
     * @bodyParam user_email required The email of a user Example: somebody@somewhere.com
     * @bodyParam lti_client_id required The Id of a lti client Example: 12
     *
     * @responseFile responses/team/teams.json
     *
     * @param TeamsSearchRequest $request
     * @return Response
     */
    public function teams(TeamsSearchRequest $request)
    {
        $verifyValidCall = LmsSetting::where('lti_client_id', $request->lti_client_id)->where('lms_login_id', 'ilike', $request->user_email)->count();
        if ($verifyValidCall) {
            $user = User::where('email', $request->input('user_email'))->first();
            $teams = TeamResource::collection($user->teams()->get());
            
            return response([
                'teams' => $teams,
            ], 200);
        }
        return response([
            'teams' => [],
        ], 400);
    }

    /**
     * Get independent Activity based on user_id of a user who launched the deeplink
     *
     * @bodyParam user_email required The email of a user Example: somebody@somewhere.com
     * @bodyParam query is search-term Example: activity title
     * @bodyParam size is for pagination
     *
     * @responseFile 200 responses/independent-activity/independent-activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Could not find any independent activity. Please try again later."
     *   ]
     * }
     * 
     * @param Request $request
     */
    public function independentActivities(IndependentActivityForDeeplink $request)
    {
        $user = User::where('email', $request->user_email)->first();
        if($user){
            return  IndependentActivityResource::collection($this->independentActivityRepository->independentActivities($request, $user->id));
        }
        return response([
            'data' => ['Could not find any independent activity. Please try again later.'],
        ], 400);
    }

}
