<?php

/*namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Metadata;
use App\Models\Playlist;
use App\Models\GoogleClassroom;
use App\User;
use Validator;
use Storage;*/

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\GCCourseResource;
use App\Http\Resources\V1\GCTopicResource;
use App\Http\Resources\V1\GCCourseWorkResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Validator;
//use App\Models\GoogleClassroom;
use App\Services\GoogleClassroom;

class GoogleClassroomController extends Controller
{
    private $client;
    //private $googleClassroom;
    private $userRepository;
    private $projectRepository;
    private $playlistRepository;

    /**
     * Instantiate a Activities instance.
     */
    public function __construct(UserRepositoryInterface $userRepository, ProjectRepositoryInterface $projectRepository, PlaylistRepositoryInterface $playlistRepository)
    {
        $this->client = new Client();
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->playlistRepository = $playlistRepository;
        //$this->googleClassroom = $googleClassroom;
    }
/*
    public function getCourses__(Request $request){
        $authenticated_user = auth()->user();
        
        //dd($authenticated_user->gapi_access_token);
        $client = $this->googleClassroom->gapiClient($authenticated_user->gapi_access_token); //$request->get('currentuser')['user']['_id']
        

        $service = new \Google_Service_Classroom($client);
        //dd($service->getClient());
        $pageToken = NULL;
        $courses = array();

        do {
            $params = array(
                // if pageSize is not set, then server will set the maximum limit, so we need to iterate through all pages
                'pageSize' => 100, 
                'pageToken' => $pageToken,
                'courseStates' => 'ACTIVE'
            );
            $response = $service->courses->listCourses($params);
            $courses = array_merge($courses, $response->getCourses());
            $pageToken = $response->nextPageToken;
        } while (!empty($pageToken));
        
        return response([
            'status'=> 'success',
            'data' => GCCourseResource::collection($courses)
        ], 200);
    }*/

    public function getCourses(Request $request){
        $authenticated_user = auth()->user();
        
        //dd($authenticated_user->gapi_access_token);
        //$client = $this->googleClassroom->gapiClient($authenticated_user->gapi_access_token); //$request->get('currentuser')['user']['_id']
        $courses = [];
        try {
            $service = new GoogleClassroom();
            $params = array(
                // if pageSize is not set, then server will set the maximum limit, so we need to iterate through all pages
                'pageSize' => 100, 
                'courseStates' => 'ACTIVE'
            );
            $courses = $service->getCourses($params);
        } catch(Exception $e) {

        }
        
        return response([
            'status'=> 'success',
            'data' => GCCourseResource::collection($courses)
        ], 200);
    }
    
    public function saveAccessToken(Request $request){
        $validator = Validator::make($request->all(), [
            // remove 'currentuser' argument.
            // 'currentuser' => 'required', 
            'access_token'=> 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Validation errors occured'],
            ], 500);
        }

        $authenticated_user = auth()->user();

        $is_updated = $this->userRepository->update([
            'gapi_access_token' => $request->input('access_token')
        ], $authenticated_user->id);
        
        if ($is_updated) {
            return response([
                'message'=> 'Access Token Saved successfully',
            ], 200);
        }

        return response([
            'errors' => ['Failed to save the token.'],
        ], 500);
    }

    /**
     * Copy whole project to google classroom
     */
    public function copyProject(Project $project, Request $request){
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to share other user\'s project.'],
            ], 403);
        }

        try {
           
            $authenticated_user = auth()->user();
            
            $return = [];
            
            //dd($project);
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
            //$return['course'] = (array) $course;
            
            $return['course'] = GCCourseResource::make($course)->resolve();
            // inserting playlists/topics to Classroom
            $playlists = $project->playlists;
            $count = 0;
            $return['course']['topics'] = [];
            foreach($playlists as $playlist){
                if (empty($playlist->title)) {
                    continue;
                }
                // Check for duplicate topic here...
                $topicData = [
                    'courseId' => $course->id,
                    'name'=> $playlist->title
                ];
                $topic = $service->getOrCreateTopic($topicData);
                
                $return['course']['topics'][$count] = GCTopicResource::make($topic)->resolve();

                // activities

                $activities = $playlist->activities;
                $courseWorkCount = 0;
                foreach($activities as $activity) {
                    if (empty($activity->title)) {
                        continue;
                    }
                    $activity->shared = true;
                    $activity->save();

                    //$updated_activity = Activity::where(['_id'=>$activity->_id])->update(['shared'=> true]);

                    $courseWorkData = [
                        'course_id' => $course->id,
                        'topic_id' => $topic->topicId,
                        'activity_id' => $activity->id,
                        'activity_title' => $activity->title
                    ];        
                    $courseWork = $service->createCourseWork($courseWorkData);
                    
                    $return['course']['topics'][$count]['course_work'] = [];
                    $return['course']['topics'][$count]['course_work'][$courseWorkCount] = GCCourseWorkResource::make($courseWork)->resolve();
                    $courseWorkCount++;
                }
                $count++;
            }
            
            return response([
                'data'=> $return,
                'status' => 'success'
            ], 200);

        } catch(\Google_Service_Exception $ex){
            return response([
                'errors' => [$ex->getMessage()],
            ], 500);
        } catch (\Exception $ex){
            return response([
                'errors' => [$ex->getMessage()],
            ], 500);
        }
    }

    private function checkPermission(Project $project)
    {
        $authenticated_user = auth()->user();

        $allowed = $authenticated_user->role === 'admin';
        if (!$allowed) {
            $project_users = $project->users;
            foreach ($project_users as $user) {
                if ($user->id === $authenticated_user->id) {
                    $allowed = true;
                }
            }
        }

        return $allowed;
    }
}
