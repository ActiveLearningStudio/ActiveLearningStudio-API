<?php

/**
 * This file contains handlers for searching LMS courses.
 */

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Course as CanvasCourse;
use App\CurrikiGo\Moodle\Course as MoodleCourse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\CreateAssignmentGroupRequest;
use App\Http\Requests\V1\CurrikiGo\CreateCourseRequest;
use App\Http\Requests\V1\CurrikiGo\FetchCourseRequest;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;

/**
 * @group 11. CurrikiGo Course
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
     * Fetch a Course
     * 
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
     * Fetch all Courses from Canvas
     *
     * @bodyParam setting_id int The Id of the LMS setting Example 1
     *
     * @responseFile responses/curriki-go/fetch-all-courses.json
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
     * @return Response
     */
    public function fetchMyCoursesFromCanvas(FetchCourseRequest $fetchRequest)
    {
        $lmsSettings = $this->lmsSettingRepository->find($fetchRequest['setting_id']);
        $canvasClient = new Client($lmsSettings);
        $canvasCourse = new CanvasCourse($canvasClient);
        $outcome = $canvasCourse->fetchAllCourses();

        if (!is_int($outcome)) {
            return response([
                'response_code' => 200,
                'response_message' => 'Fetched all the courses',
                'data' => $outcome,
            ], 200);
        } elseif ($outcome === 401 || $outcome === 403) {
            return response([
                'response_code' => $outcome,
                'response_message' => 'Canvas token is invalid, expired or missing permission to create a course',
                'data' => $outcome,
            ], $outcome);
        }else {
            return response([
                'response_code' => $outcome,
                'response_message' => 'No Courses found',
                'data' => [],
            ], 200);
        }
    }

    /**
     * Fetch all Assignment groups of selected course from Canvas
     *
     * @bodyParam course_id int The Id of selected course
     *
     * @responseFile responses/curriki-go/fetch-assignment-groups.json
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
     * @param $courseId
     * @param FetchCourseRequest $request
     * @return Response $request
     */
    public function fetchAssignmentGroups($courseId, FetchCourseRequest $request)
    {
        $lmsSettings = $this->lmsSettingRepository->find($request['setting_id']);
        $canvasClient = new Client($lmsSettings);
        $canvasCourse = new CanvasCourse($canvasClient);
        $outcome = $canvasCourse->fetchAssignmentGroups($courseId);

        if ($outcome) {
            return response([
                'response_code' => 200,
                'response_message' => 'Fetched all assignment groups',
                'data' => $outcome,
            ], 200);
        } else {
            return response([
                'response_code' => 200,
                'response_message' => 'No Assignments found',
                'data' => $outcome,
            ], 200);
        }
    }

    /**
     * Create Assignment groups of selected course from Canvas
     *
     * @bodyParam assignment_group's name string
     *
     * @responseFile responses/curriki-go/create-assignment-groups.json
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
     * @param $courseId
     * @param CreateAssignmentGroupRequest $courseId
     * @return Response $request
     */
    public function createAssignmentGroups($courseId, CreateAssignmentGroupRequest $request)
    {
        $lmsSettings = $this->lmsSettingRepository->find($request['setting_id']);
        $canvasClient = new Client($lmsSettings);
        $canvasCourse = new CanvasCourse($canvasClient);
        $outcome = $canvasCourse->CreateAssignmentGroups($courseId, $request['assignment_group_name']);

        if ($outcome) {
            return response([
                'response_code' => 200,
                'response_message' => 'New assignment group has been created successfully!',
                'data' => $outcome,
            ], 200);
        } else {
            return response([
                'response_code' => 200,
                'response_message' => 'No Assignment Groups found',
                'data' => $outcome,
            ], 200);
        }
    }

    /**
     * Create new course in Canvas
     *
     * @bodyParam course name string
     *
     * @responseFile responses/curriki-go/create-course-groups.json
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
     * @return Response $request
     */
    public function createNewCourse(CreateCourseRequest $request)
    {
        $lmsSettings = $this->lmsSettingRepository->find($request->setting_id);
        $canvasClient = new Client($lmsSettings);
        $canvasCourse = new CanvasCourse($canvasClient);
        $outcome = $canvasCourse->createNewCourse($request->course_name);

        if (!is_int($outcome)) {
            return response([
                'response_code' => 200,
                'response_message' => 'New course has been created successfully!',
                'data' => $outcome,
            ], 200);
        } elseif ($outcome === 401 || $outcome === 403) {
            return response([
                'response_code' => $outcome,
                'response_message' => 'Canvas token is invalid, expired or missing permission to create a course',
                'data' => $outcome,
            ], $outcome);
        } else {
            return response([
                'response_code' => $outcome,
                'response_message' => 'course creation failed',
                'data' => $outcome,
            ], $outcome);
        }
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
