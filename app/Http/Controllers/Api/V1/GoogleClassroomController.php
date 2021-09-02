<?php

/**
 * This File defines handlers for Google classroom.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GCCopyProjectRequest;
use App\Http\Requests\V1\GCSaveAccessTokenRequest;
use App\Http\Requests\V1\GCTurnInRequest;
use App\Http\Requests\V1\GCSummaryPageAccessRequest;
use App\Http\Requests\V1\GCGetStudentSubmissionRequest;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\GCCourseResource;
use App\Http\Resources\V1\GCStudentResource;
use App\Http\Resources\V1\GCTeacherResource;
use App\Http\Resources\V1\GCSubmissionResource;
use App\Models\Project;
use App\Models\Activity;
use App\Models\GcClasswork;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\GcClasswork\GcClassworkRepositoryInterface;
use App\Services\GoogleClassroom;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\App;
use App\Http\Resources\V1\ActivityResource;
use App\Http\Resources\V1\H5pOrganizationResource;
use App\Http\Resources\V1\PlaylistResource;

/**
 * @group 11. Google Classroom
 *
 * APIs for Google Classroom
 */
class GoogleClassroomController extends Controller
{
    /**
     * User repository object
     *
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Instantiate a GoogleClassroom instance.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
	 * Get Courses
	 *
	 * Get all existing Google Classroom Courses
     *
     * @responseFile responses/google-classroom/google-classroom-courses.json
     *
     * @response 500 {
     *   "errors": [
     *     "Service exception error"
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
	 */
    public function getCourses(Request $request)
    {
        $courses = [];
        try {
            $service = new GoogleClassroom();
            $params = array(
                // if pageSize is not set, then server will set the maximum limit
                // so we need to iterate through all pages
                'pageSize' => 100,
                'teacherId' => 'me', // only return courses that the user is enrolled as a teacher.
                'courseStates' => GoogleClassroom::COURSE_STATE_ACTIVE
            );
            $courses = $service->getCourses($params);
        } catch (Exception $e) {
            return response([
                'errors' => [$e->getMessage()],
            ], 500);
        }

        return response([
            'courses' => GCCourseResource::collection($courses)
        ], 200);
    }

    /**
	 * Save Access Token
	 *
	 * Save GAPI access token in the database.
	 *
     * @bodyParam access_token string required The stringified of the GAPI access token JSON object
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
     * @param GCSaveAccessTokenRequest $accessTokenRequest
     * @return Response
	 */
    public function saveAccessToken(GCSaveAccessTokenRequest $accessTokenRequest)
    {
        $data = $accessTokenRequest->validated();

        $authUser = auth()->user();
        $isUpdated = $this->userRepository->update([
            'gapi_access_token' => $data['access_token']
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
	 * Copy project to Google Classroom
	 *
	 * Copy whole project to google classroom either as a new course or into an existing course.
	 *
     * @urlParam project required The Id of a project. Example: 9
     * @bodyParam course_id string Id of an existing Google Classroom course. Example: 123
     * @bodyParam access_token string|null The stringified of the GAPI access token JSON object
     *
     * @responseFile responses/google-classroom/google-classroom-project.json
     *
     * @response  403 {
     *   "errors": [
     *     "Forbidden. You are trying to share other user's project."
     *   ]
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "Failed to copy project."
     *   ]
     * }
     *
     * @param Project $project
     * @param GCCopyProjectRequest $copyProjectRequest
     * @param GcClassworkRepositoryInterface $gcClassworkRepository
     * @param GoogleClassroomRepositoryInterface $googleClassroomRepository
     * @return Response
	 */
    public function copyProject(Project $project, GCCopyProjectRequest $copyProjectRequest, GcClassworkRepositoryInterface $gcClassworkRepository,
        GoogleClassroomRepositoryInterface $googleClassroomRepository)
    {
        $authUser = auth()->user();
        if (Gate::forUser($authUser)->denies('publish-to-lms', $project)) {
            return response([
                'errors' => ['Forbidden. You are trying to share other user\'s project.'],
            ], 403);
        }

        try {
            $data = $copyProjectRequest->validated();
            $accessToken = (isset($data['access_token']) && !empty($data['access_token']) ? $data['access_token'] : null);
            $courseId = $data['course_id'] ?? 0;
            $service = new GoogleClassroom($accessToken);
            $service->setGcClassworkObject($gcClassworkRepository);
            $course = $service->createProjectAsCourse($project, $courseId, $googleClassroomRepository);

            return response([
                'course' => $course,
            ], 200);
        } catch (\Google_Service_Exception $ex) {
            return response([
                'errors' => [$ex->getMessage()],
            ], 500);
        } catch (\Exception $ex) {
            return response([
                'errors' => [$ex->getMessage()],
            ], 500);
        }
    }

    /**
     * Get student's submission against a classwork
     *
     * Identifies student's submission on a classwork assignment.
     *
     * @urlParam classwork required The Id of a classwork. Example: 9
     * @bodyParam access_token string required The stringified of the GAPI access token JSON object
     * @bodyParam course_id string required The Google Classroom course id
     *
     * @responseFile responses/google-classroom/google-classroom-submission.json
     *
     * @response  500 {
     *   "errors": [
     *     "You are not enrolled in this class."
     *   ]
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "Could not retrieve submission for this assignment."
     *   ]
     * }
     *
     * @param GcClasswork $classwork
     * @param GCGetStudentSubmissionRequest $studentSubmissionRequest
     * @return Response
     */
    public function getStudentSubmission(GcClasswork $classwork, GCGetStudentSubmissionRequest $studentSubmissionRequest)
    {
        $data = $studentSubmissionRequest->validated();
        $courseId = $data['course_id'];
        $accessToken = $data['access_token'];
        try {
            // Should we validate if the student is in the course?
            $service = new GoogleClassroom($accessToken);
            $studentRes = $service->getEnrolledStudent($courseId);
            if (isset($studentRes->courseId)) {
                try {
                    // Get the student's submission...
                    $submissions = $service->getStudentSubmissions($courseId, $classwork->classwork_id);
                    $firstSubmission = $submissions[0];
                    return response([
                        'submission' => GCSubmissionResource::make($firstSubmission)->resolve()
                    ], 200);
                } catch (\Google_Service_Exception $ex) {
                    // Could obtain error message like that.
                    // $response = json_decode($ex->getMessage());
                    // $response->error->message
                    return response([
                        'errors' => ['Student\'s submission could not be retrieved.'],
                    ], 500);
                } catch (\Exception $ex) {
                    return response([
                        'errors' => [$ex->getMessage()],
                    ], 500);
                }
            } else {
                $response = json_decode($studentRes);
                return response([
                    'errors' => [$response->error->message],
                ], 500);
            }
        } catch (\Google_Service_Exception $ex) {
            // Could obtain error message like that.
            // $response = json_decode($ex->getMessage());
            // $response->error->message
            return response([
                'errors' => ['You are not enrolled in this class.'],
            ], 500);
        } catch (\Exception $ex) {
            return response([
                'errors' => [$ex->getMessage()],
            ], 500);
        }
    }

    /**
     * TurnIn a student's submission
     *
     * Identifies student's submission on a classwork assignment.
     * Attaches a summary page link to the assignment, and turns it in.
     *
     * @urlParam classwork required The Id of a classwork. Example: 9
     * @bodyParam access_token string required The stringified of the GAPI access token JSON object
     * @bodyParam course_id string required The Google Classroom course id
     *
     * @response  200 {
     *   "message": "The assignment has been turned in successfully."
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "You are not enrolled in this class."
     *   ]
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "Could not retrieve submission for this assignment."
     *   ]
     * }
     *
     * @param GcClasswork $classwork
     * @param GCTurnInRequest $turnInRequest
     * @return Response
     */
    public function turnIn(GcClasswork $classwork, GCTurnInRequest $turnInRequest)
    {
        $data = $turnInRequest->validated();
        $courseId = $data['course_id'];
        $accessToken = $data['access_token'];
        try {
            // Should we validate if the student is in the course?
            $service = new GoogleClassroom($accessToken);
            $studentRes = $service->getEnrolledStudent($courseId);
            if (isset($studentRes->courseId)) {
                try {
                    // Get the student's submission...
                    $firstSubmission = $service->getFirstStudentSubmission($courseId, $classwork->classwork_id);

                    if ($firstSubmission && $firstSubmission->state !== GoogleClassroom::ASSIGNMENT_STATE_TURNED_IN) {
                        $firstSubmissionId = $firstSubmission->id;
                        // Submission obtained...
                        // Now make a link.
                        $pathArr = explode("/", trim($classwork->path, "/"));
                        // format of Summary link: /gclass/summary/:userid/:courseid/:activityid/:gc_classworkid/:gc_submission_id;
                        $summaryLink = '/gclass/summary/' . $pathArr[2] . '/' . $pathArr[3] . '/' . $pathArr[4] . '/' . $classwork->classwork_id . '/' . $firstSubmissionId;
                        $updatedSubmission = $service->modifySubmissionAttachment($courseId, $classwork->classwork_id, $firstSubmissionId, $summaryLink);

                        // Turn in
                        $updatedSubmission = $service->turnIn($courseId, $classwork->classwork_id, $firstSubmissionId);

                        return response([
                            'message' => "The assignment has been turned in successfully.",
                        ], 200);
                    } else {
                        return response([
                            'errors' => ['Could not retrieve submission for this assignment id=' . $classwork->classwork_id],
                        ], 500);
                    }
                } catch (\Google_Service_Exception $ex) {
                    // Could obtain error message like that.
                    $response = json_decode($ex->getMessage());
                    return response([
                        'errors' => [$response->error->message],
                    ], 500);
                } catch (Exception $e) {
                    return response([
                        'errors' => ['Could not retrieve submission for this assignment id=' . $studentRes->id],
                    ], 500);
                }
            } else {
                $response = json_decode($studentRes);
                return response([
                    'errors' => [$response->error->message],
                ], 500);
            }
        } catch (\Google_Service_Exception $ex) {
            // Could obtain error message like that.
            // $response = json_decode($ex->getMessage());
            // $response->error->message
            return response([
                'errors' => ['You are not enrolled in this class.'],
            ], 500);
        } catch (\Exception $ex) {
            return response([
                'errors' => [$ex->getMessage()],
            ], 500);
        }
    }

    /**
     * Verify whether Google Classroom user has access to a student's submission
     *
     * If the user is a teacher, validate if he's one of the teachers in the class
     * If the user is authenticated and is a student, validate if the submission is his.
     *
     * @bodyParam access_token string required The stringified of the GAPI access token JSON object
     * @bodyParam student_id the google user id for the student
     * @bodyParam course_id string required The Google Classroom course id
     * @bodyParam gc_classwork_id string required The Id of the classwork
     * @bodyParam gc_submission_id string required The Id of the student's submission
     *
     * @responseFile responses/google-classroom/google-classroom-student.json
     *
     * @response  404 {
     *   "errors": [
     *     "Either the entity was not found or you do not have permission to view it."
     *   ]
     * }
     *
     *
     * @param GCSummaryPageAccessRequest $summaryPageRequest
     * @return Response
     */
    public function validateSummaryPageAccess(GCSummaryPageAccessRequest $summaryPageRequest)
    {
        $data = $summaryPageRequest->validated();
        $accessToken = $data['access_token'];
        $courseId = $data['course_id'];
        $gcClassworkId = $data['gc_classwork_id'];
        $gcSubmissionId = $data['gc_submission_id'];

        try {
            $service = new GoogleClassroom($accessToken);
        } catch (\Google_Service_Exception $ex) {
            return response([
                'student' => null,
                'teacher' => null,
                'errors' => [['code' => 1, 'msg' => 'Failed to initialize gAPI service.']],
            ], 500);
        } catch (\Exception $ex) {
            return response([
                'student' => null,
                'teacher' => null,
                'errors' => [['code' => 1, 'msg' => $ex->getMessage()]],
            ], 500);
        }

        // Check if we're a teacher
        // We return the teacher information only if this is true
        try{
            $teacher = $service->getCourseTeacher($courseId);
        } catch (\Google_Service_Exception $ex) {
            $teacher = false;
        }

        // Get submission
        try {
            $submissionRes = $service->getStudentSubmissionById($courseId, $gcClassworkId, $gcSubmissionId);
            $studentId = $submissionRes->userId;
        } catch (\Google_Service_Exception $ex) {
            return response([
                'teacher' => ($teacher) ? GCTeacherResource::make($teacher)->resolve() : false,
                'student' => null,
                'errors' => [['code' => 3, 'msg' => 'Student submission is not available.']],
            ], 404);
        }

        // Get student and check enrollment
        try {
            $student = $service->getEnrolledStudent($courseId, $studentId);
        } catch (\Google_Service_Exception $ex) {
            return response([
                'student' => null,
                'teacher' => null,
                'errors' => [['code' => 2, 'msg' => 'Student is not enrolled in this course.']],
            ], 404);
        }

        // Check if the submission is turned In or not.
        if (!$service->isAssignmentSubmitted($submissionRes->state)) {
            return response([
                'teacher' => ($teacher) ? GCTeacherResource::make($teacher)->resolve() : false,
                'student' => GCStudentResource::make($student)->resolve(),
                'errors' => [['code' => 3, 'msg' => 'The summary page is unavailable as the assignment is not turned in yet.']],
            ], 404);
        }

        return response([
            'student' => GCStudentResource::make($student)->resolve(),
            'teacher' => ($teacher) ? GCTeacherResource::make($teacher)->resolve() : false,
        ], 200);
    }

    /**
     * Get H5P Resource Settings For Google Classroom
     *
     *
     * @urlParam activity required The Id of a activity
     *
     * @responseFile responses/h5p/h5p-resource-settings-open.json
     *
     * @response 400 {
     *   "errors": [
     *     "Activity not found."
     *   ]
     * }
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettings(Activity $activity)
    {
        // 3 is for indexing approved - see Project Model @indexing property
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = $h5p->load_content($activity->h5p_content_id);
        $content['disable'] = config('laravel-h5p.h5p_preview_flag');
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];
        $user_data = null;
        $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];

        return response([
            'h5p' => $h5p_data,
            'activity' => new ActivityResource($activity),
            'playlist' => new PlaylistResource($activity->playlist),
            'organization' => new H5pOrganizationResource($activity->playlist->project->organization),
        ], 200);
    }
}
