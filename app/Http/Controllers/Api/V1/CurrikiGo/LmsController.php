<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectPublicResource;
use App\Http\Resources\V1\SearchResource;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Models\Project;

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
        if ($request->mode === 'browse') {
            $validator = Validator::make($request->all(), [
                'lti_client_id' => 'required',
                'user_email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response(['error' => $messages], 400);
            }

            $projects = $this->projectRepository->fetchByLtiClientAndEmail(
                $request->input('lti_client_id'),
                $request->input('user_email')
            );

            return response([
                'projects' => SearchResource::collection($projects),
            ], 200);
        }

        if ($request->mode === 'search') {
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
                'projects' => $this->activityRepository->ltiSearchForm($request),
            ], 200);            
        }
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
}
