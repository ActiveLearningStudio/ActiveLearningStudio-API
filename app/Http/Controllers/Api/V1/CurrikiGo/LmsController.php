<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectPublicResource;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

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

    /**
     * LmsController constructor.
     *
     * @param $lmsSettingRepository LmsSettingRepositoryInterface
     * @param $projectRepository ProjectRepositoryInterface
     * @param $activityRepository ActivityRepositoryInterface
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository, ProjectRepositoryInterface $projectRepository, ActivityRepositoryInterface $activityRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->projectRepository = $projectRepository;
        $this->activityRepository = $activityRepository;
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
     * @return Response
     */
    // TODO: need to update
    public function projects(Request $request)
    {
        // TODO: need to update validation
        $validator = Validator::make($request->all(), [
            'lms_url' => 'required',
            'lti_client_id' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response(['error' => $messages], 400);
        }

        $lms_url = $request->input('lms_url');
        $lti_client_id = $request->input('lti_client_id');
        $projects = $this->projectRepository->fetchByLtiClient($lti_client_id);

        return response([
            'projects' => ProjectPublicResource::collection($projects),
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
            'ltiClientId' => 'integer|required',
        ]);

        return response([
            'activities' => $this->activityRepository->ltiSearchForm($request),
        ], 200);
    }
}
