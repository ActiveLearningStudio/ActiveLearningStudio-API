<?php

/**
 * This File defines handlers for Google classroom.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GCCopyProjectRequest;
use App\Http\Requests\V1\GCSaveAccessTokenRequest;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\GCCourseResource;
use App\Models\Project;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\GoogleClassroom;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

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
     * @return Response
	 */
    public function copyProject(Project $project, GCCopyProjectRequest $copyProjectRequest)
    {
        $authUser = auth()->user();
        if (Gate::forUser($authUser)->denies('publish-to-lms', $project)) {
            return response([
                'errors' => ['Forbidden. You are trying to share other user\'s project.'],
            ], 403);
        }

        try {
            $data = $copyProjectRequest->validated();
            $courseId = $data['course_id'] ?? 0;
            $service = new GoogleClassroom();
            $course = $service->createProjectAsCourse($project, $courseId);

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
}
