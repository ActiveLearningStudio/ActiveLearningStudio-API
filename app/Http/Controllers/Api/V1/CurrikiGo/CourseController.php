<?php

/**
 * This file contains handlers for searching LMS courses.
 */

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrikiGo\Moodle\Course as MoodleCourse;
use App\CurrikiGo\Canvas\Course as CanvasCourse;
use Validator;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\CurrikiGo\Canvas\Client;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CurrikiGo\FetchCourseRequest;

/**
 * @group  CurrikiGo
 * @authenticated
 * 
 * APIs for fetching courses from LMSs
 */
class CourseController extends Controller
{
    /**
     * $lmsSettingRepository
     */
    private $lmsSettingRepository;

    /**
     * CourseController constructor.
     *
     * @param LmsSettingRepositoryInterface $lmsSettingRepository
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
    }

    /**
     * Fetch a course from Canvas
     * 
     * @urlParam  project required The ID of the project.
     * @bodyParam  setting_id int The id of the LMS setting.
     * 
     * @responseFile  responses/fetchfromcanvas.post.json
     * @response  400 {
     *  "errors": "Validation error"
     * }
     * @response  403 {
     *  "errors": "You are not authorized to perform this action."
     * }
     * 
     * @param Project $project The project model object
     * @param FetchCourseRequest $fetchRequest The request object
     * @return Response
     */
    public function fetchFromCanvas(Project $project, FetchCourseRequest $fetchRequest)
    {                
        $authUser = auth()->user();
        if (Gate::forUser($authUser)->allows('fetch-lms-course', $project)) {
            $data = $fetchRequest->validated();
            $lmsSettings = $this->lmsSettingRepository->find($data['setting_id']);
            $canvasClient = new Client($lmsSettings);
            $canvasCourse = new CanvasCourse($canvasClient);
            $outcome = $canvasCourse->fetch($project);

            return response([
                'project' => $outcome,
            ], 200);
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }

    /**
     * Fetch course from Moodle
     * 
     * @param Request $request
     * @return Response
     */
    public function fetchFromMoodle(Request $request)
    {                
        $validator = Validator::make($request->all(), ['setting_id' => 'required', 'project_id' => 'required']);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response([
                'errors' => [$messages],
            ], 400);
        }
        
        $moodleCourse = new MoodleCourse($request->setting_id);
        $response = $moodleCourse->fetch(['project_id' => $request->project_id]);
        if (is_null($response)) {
            return response([
                'errors' => ["No Project Found"],
            ], 400);
        }
        
        $code = $response->getStatusCode();
        if ($code == 200) {
            $outcome = $response->getBody()->getContents();
            return response([
                'data' => json_decode($outcome),
            ], 200);
        } else {
            return response([
                'errors' => ["Moodle API Request Failed"],
            ], 500);
        }
    }
}
