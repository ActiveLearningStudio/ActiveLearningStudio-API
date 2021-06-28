<?php

/**
 * This file contains handlers for searching LMS courses.
 */

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Course as CanvasCourse;
use App\CurrikiGo\Moodle\Course as MoodleCourse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\FetchCourseRequest;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @group 10. CurrikiGo Course
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
     * Fetch a Course from Canvas
     *
     * @urlParam project required The Id of the project Example 1
     * @bodyParam setting_id int The Id of the LMS setting Example 1
     *
     * @responseFile responses/curriki-go/fetch-course.json
     *
     * @response 400 {
     *   "errors": [
     *     "Validation error"
     *   ]
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You are not authorized to perform this action."
     *   ]
     * }
     *
     * @param Project $project
     * @param FetchCourseRequest $fetchRequest
     * @return Response
     */
    public function fetchFromCanvas(FetchCourseRequest $fetchRequest, Project $project)
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
     * Fetch a Course from Moodle
     *
     * @urlParam project required The Id of the project Example 1
     * @bodyParam setting_id int The Id of the LMS setting Example 1
     *
     * @responseFile responses/fetch-course.json
     *
     * @response 400 {
     *   "errors": [
     *     "Validation error"
     *   ]
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You are not authorized to perform this action."
     *   ]
     * }
     *
     * @param FetchCourseRequest $fetchRequest
     * @param Project $project
     * @return Response
     */
    public function fetchFromMoodle(FetchCourseRequest $fetchRequest, Project $project)
    {
        $authUser = auth()->user();
        if (Gate::forUser($authUser)->allows('fetch-lms-course', $project)) {
            $data = $fetchRequest->validated();
            $lmsSetting = $this->lmsSettingRepository->find($data['setting_id']);
            $moodleCourse = new MoodleCourse($lmsSetting);
            $response = $moodleCourse->fetch($project);
            $outcome = $response->getBody()->getContents();
            return response([
                'project' => json_decode($outcome),
            ], 200);
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }

    /**
     * Fetch a Course from Generic
     *
     */
    public function fetchFromGeneric($lms, FetchCourseRequest $fetchRequest, Project $project)
    {
        return response([
            'project' => ['course' => null, 'playlists' => []],
        ], 200);
    }
}
