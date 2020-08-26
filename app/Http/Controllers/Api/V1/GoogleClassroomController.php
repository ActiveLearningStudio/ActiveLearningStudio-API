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
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\User;
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
    public function copyProject(Request $request){
        try {
            
            $validator = Validator::make($request->all(), [
                //'currentuser' => 'required',
                'projectId' => 'required',
            ]);
    
            if ($validator->fails()) {
                return responseError([], 'Validation errors occured');
            }
            $authenticated_user = auth()->user();
            
            $return = [];
            $projectId = $request->input('projectId');
            $project = $this->projectRepository->find($projectId);
            dd($project);
            // Classroom ID - if available.
            $courseId = $request->input('courseId');
            
            if(!$project){ 
                return false;
            }
            $client = $this->googleClassroom->gapiClient($authenticated_user->gapi_access_token);
            $service = new \Google_Service_Classroom($client);

            // If course already exists 
            $course = NULL;
            if ($courseId) {
                $course = $service->courses->get($courseId);
            } else {
                $course = new \Google_Service_Classroom_Course(array(
                    'name' => $project->name,
                    'descriptionHeading' => $project->description,
                    'description' => $project->description,
                    'room' => '1', // optional
                    'ownerId' => 'me',
                    'courseState' => 'PROVISIONED'
                ));
                
                $course = $service->courses->create($course);
            }
            
            $return['course'] = (array) $course;
            // inserting playlists/topics to Classroom
            //$playlists = Playlist::where(['projectid'=>$project->_id])->get();
            $playlists = $project->playlists;
            $count = 0;
            $return['course']['topics'] = [];
            foreach($playlists as $playlist){
                $topic = new \Google_Service_Classroom_Topic(array(
                    'courseId' => $course->id,
                    'name'=> $playlist->title
                ));
                $topic = $service->courses_topics->create($course->id, $topic);
                
                $return['course']['topics'][$count] = (array) $topic;

                //activities

                $activities = $playlist->activities;//Activity::where(['playlistid'=> $playlist->_id])->get();
                $courseWorkCount = 0;
                foreach($activities as $activity) {
                    //$updated_activity = Activity::where(['_id'=>$activity->_id])->update(['shared'=> true]);
                    $activity->shared = true;
                    $activity->save();

                    //$updated_activity = Activity::where(['_id'=>$activity->_id])->update(['shared'=> true]);


                    $courseWork = new \Google_Service_Classroom_CourseWork();
                    $courseWork->setCourseId($course->id);
                    $courseWork->setTopicId($topic->topicId);
                    /*$h5p_activity = \DB::connection('mysql')
                            ->table('h5p_contents')
                            ->select('title')
                            ->where(['id'=>$activity->mysqlid])->first();
                    $courseWork->setTitle($h5p_activity->title);*/
                    $courseWork->setTitle($activity->title);
                    $courseWork->setWorkType('ASSIGNMENT');
                    $courseWork->setMaterials([
                        'link'=> [
                            'url' => config('constants.front-url').'/shared/activity/'.$activity->id
                        ]
                    ]);
                    $courseWork->setState('PUBLISHED');

                    $courseWork = $service->courses_courseWork->create($course->id, $courseWork);
                    $return['course']['topics'][$count]['courseWork'] = [];
                    $return['course']['topics'][$count]['courseWork'][$courseWorkCount] = (array) $courseWork;
                    $courseWorkCount++;
                }
                $count++;
            }
            

            return responseSuccess($return);
            
        } catch(\Google_Service_Exception $ex){
            return responseFail(['message' => $ex->getMessage()]);
        } catch (\Exception $ex){
            return responseFail(['message' => $ex->getMessage()]);
        }
    }
}
