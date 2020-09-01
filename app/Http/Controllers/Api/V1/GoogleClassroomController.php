<?php

/**
 * This File defines handlers for Google classroom.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\GCCourseResource;
use App\Http\Resources\V1\GCTopicResource;
use App\Http\Resources\V1\GCCourseWorkResource;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Services\GoogleClassroom;
use Illuminate\Support\Facades\Gate;

/**
 * @group  Google Classroom
 * @authenticated
 * @author Aqeel
 *
 * APIs for sharing projects in Google Classroom
 */
class GoogleClassroomController extends Controller
{
    /**
     * $userRepository User repository object
     */
    private $userRepository;

    /**
     * Instantiate a GoogleClassroom instance.
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
     * @responseFile  responses/google-classroom.courses.get.json
     * 
     * @response  500 {
     *  "errors": "Service exception error"
     * }
     * 
	 */
    public function getCourses(Request $request)
    {
        $courses = [];
        try {
            $service = new GoogleClassroom();
            $params = array(
                // if pageSize is not set, then server will set the maximum limit, so we need to iterate through all pages
                'pageSize' => 100, 
                'courseStates' => 'ACTIVE'
            );
            $courses = $service->getCourses($params);
        } catch (Exception $e) {
            return response([
                'errors' => [$e->getMessage()],
            ], 500);
        }
        
        return response([
            'status' => 'success',
            'data' => GCCourseResource::collection($courses)
        ], 200);
    }
    
    /**
	 * Save Access Token
	 *
	 * Save GAPI access token in the database.
	 *
     * @bodyParam  access_token string required The stringified JSON of the GAPI access token object
     * @response  {
     *   "message":"Access Token Saved successfully"
     * }
     * 
     * @response  500 {
     *  "errors": "Validation error: Access token is required"
     * }
     * 
     * @response  500 {
     *  "errors": "Failed to save the token."
     * }
	 */
    public function saveAccessToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Validation error: Access token is required'],
            ], 500);
        }

        $authUser = auth()->user();
        $isUpdated = $this->userRepository->update([
            'gapi_access_token' => $request->input('access_token')
        ], $authUser->id);
        
        if ($isUpdated) {
            return response([
                'message' => 'Access Token Saved successfully',
            ], 200);
        }

        return response([
            'errors' => ['Failed to save the token.'],
        ], 500);
    }

    /**
	 * Copy project to Google Classroom
	 *
	 * Copy whole project to google classroom either as a new course
     * or into an existing course.
	 *
     * @urlParam    project required The ID of the project. Example: 9
     * @bodyParam   course_id string ID of an existing Google Classroom course. Example: 123
     * @responseFile  responses/google-classroom.copyproject.json
     * 
     * @response  403 {
     *  "errors": "Forbidden. You are trying to share other user's project."
     * }
     * 
     * @response  500 {
     *  "errors": "Failed to save the token."
     * }
	 */
    public function copyProject(Project $project, Request $request)
    {
        $authUser = auth()->user();
        if (Gate::forUser($authUser)->denies('publish-to-lms', $project)) {
            return response([
                'errors' => ['Forbidden. You are trying to share other user\'s project.'],
            ], 403);
        }
       
        try {
            $return = [];
            // Classroom ID - if available.
            $courseId = $request->input('course_id');
            
            $service = new GoogleClassroom();
            // If course already exists 
            $course = NULL;
            if ($courseId) {
                $course = $service->getCourse($courseId);
            } else {
                $courseData = [
                    'name' => $project->name,
                    'descriptionHeading' => $project->description,
                    'description' => $project->description,
                    'room' => '1', // optional
                    'ownerId' => 'me',
                    'courseState' => 'PROVISIONED'
                ];
                $course = $service->createCourse($courseData);
            }
            
            $return['course'] = GCCourseResource::make($course)->resolve();
            // inserting playlists/topics to Classroom
            $playlists = $project->playlists;
            $count = 0;
            $return['course']['topics'] = [];

            // Existing topics that the course has.
            $existingTopics = $service->getTopics($course->id);
            foreach ($playlists as $playlist) {
                if (empty($playlist->title)) {
                    continue;
                }

                // Check for duplicate topic here...
                $topic = null;
                if (!empty($existingTopics)) {
                    // Find a duplicate..
                    foreach ($existingTopics as $oneTopic) {
                        if ($oneTopic->name === $playlist->title) {
                            $topic = $oneTopic;
                            break;
                        }
                    }
                }
                if (!$topic) {
                    $topicData = [
                        'courseId' => $course->id,
                        'name' => $playlist->title
                    ];
                    $topic = $service->createTopic($topicData);
                    // Pushing to existing topics
                    $existingTopics[] = $topic;
                }

                $return['course']['topics'][$count] = GCTopicResource::make($topic)->resolve();

                // Iterate over activities
                $activities = $playlist->activities;
                $courseWorkCount = 0;
                foreach ($activities as $activity) {
                    if (empty($activity->title)) {
                        continue;
                    }
                    $activity->shared = true;
                    $activity->save();

                    $courseWorkData = [
                        'course_id' => $course->id,
                        'topic_id' => $topic->topicId,
                        'activity_id' => $activity->id,
                        'activity_title' => $activity->title
                    ];        
                    $courseWork = $service->createCourseWork($courseWorkData);
                    
                    $return['course']['topics'][$count]['course_work'][] = GCCourseWorkResource::make($courseWork)->resolve();
                    $courseWorkCount++;
                }
                $count++;
            }
            
            return response([
                'data' => $return,
                'status' => 'success'
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
