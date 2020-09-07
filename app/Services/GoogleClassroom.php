<?php

namespace App\Services;

use App\Services\GoogleClassroomInterface;
use App\Http\Resources\V1\GCCourseResource;
use App\Http\Resources\V1\GCTopicResource;
use App\Http\Resources\V1\GCCourseWorkResource;
use App\Models\Project;

/**
 * Google Classroom Service class
 */
class GoogleClassroom implements GoogleClassroomInterface
{
    /**
     * Google Classroom Serivce object
     * 
     * @var \Google_Service_Classroom
     */
    protected $service;

    /**
     * Creates an instance of the class 
     * 
     * @return void
     */
    function __construct() 
    {
        $client = new \Google_Client();
        $client->setApplicationName(config('google.gapi_application_name'));
        $client->setScopes([\Google_Service_Classroom::CLASSROOM_COURSES_READONLY, \Google_Service_Classroom::CLASSROOM_COURSES, \Google_Service_Classroom::CLASSROOM_TOPICS, \Google_Service_Classroom::CLASSROOM_COURSEWORK_ME, \Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS]);
        $credentials = config('google.gapi_class_credentials');
        
        $client->setAuthConfig(json_decode($credentials, true));
        // $client->setAuthConfig(public_path().'/googleapi/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // @Todo - at the moment, we're taking token from the database for the authenticated user.
        if (auth()->user()) {
            $accessToken = json_decode(auth()->user()->gapi_access_token, true);
            $client->setAccessToken($accessToken);
        }
        
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // @TODO - this flow doesn't actually kick in.
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                // header("Location: $authUrl");
                // printf("Open the following link in your browser:\n%s\n", $authUrl);
                // print 'Enter verification code: ';
                $authCode = $_GET['code'];
    
                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);
    
                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // // Save the token to a file.
            // if (!file_exists(dirname($tokenPath))) {
            //     mkdir(dirname($tokenPath), 0700, true);
            // }
            // file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        $this->service = new \Google_Service_Classroom($client);
    }

    /**
     * Get Google Client object
     * 
     * @return \Google_Client
     */
    public function getClient()
    {
        return $this->service->getClient();
    }

    /**
     * Get Google Classroom courses
     * 
     * @param array $params
     * @return array
     */
    public function getCourses($params)
    {
        $pageToken = NULL;
        $courses = array();

        do {
            $params['pageToken'] = $pageToken;
            $response = $this->service->courses->listCourses($params);
            $courses = array_merge($courses, $response->getCourses());
            $pageToken = $response->nextPageToken;
        } while (!empty($pageToken));

        return $courses; 
    }

    /**
     * Get a course by id
     * 
     * @param int $courseId
     * @return \Google_Service_Classroom_Course
     */
    public function getCourse($courseId)
    {
        return $this->service->courses->get($courseId);
    }

    /**
     * Create a course in Google Classroom
     * 
     * @param array $data The course data
     * @return \Google_Service_Classroom_Course
     */
    public function createCourse($data)
    {
        $course = new \Google_Service_Classroom_Course($data);
        return $this->service->courses->create($course);
    }

    /**
     * Create a topic in Google Classroom
     * 
     * @param array $data The topic data
     * @return \Google_Service_Classroom_Topic
     */
    public function createTopic($data)
    {
        $topic = new \Google_Service_Classroom_Topic($data);
        return $this->service->courses_topics->create($data['courseId'], $topic);
    }

    /**
     * Create a course work in Google Classroom
     * 
     * @param array $data The course work data
     * @return \Google_Service_Classroom_CourseWork
     */
    public function createCourseWork($data)
    {
        $courseWork = new \Google_Service_Classroom_CourseWork();
        $courseWork->setCourseId($data['course_id']);
        $courseWork->setTopicId($data['topic_id']);
        $courseWork->setTitle($data['activity_title']);
        $courseWork->setWorkType(self::COURSEWORK_TYPE);
        $courseWork->setMaterials([
            'link'=> [
                'url' => config('constants.front-url') . '/activity/' . $data['activity_id'] . '/shared'
            ]
        ]);
        $courseWork->setState(self::COURSEWORK_STATE_PUBLISHED);

        return $this->service->courses_courseWork->create($data['course_id'], $courseWork);
    }

    /**
     * Get Topics by course id
     * 
     * @param int $courseId The id of the course
     * @return array
     */
    public function getTopics($courseId)
    {
        $pageToken = NULL;
        $topics = array();

        do {
            $params['pageToken'] = $pageToken;
            $response = $this->service->courses_topics->listCoursesTopics($courseId, $params);
            $topics = array_merge($topics, $response->getTopic());
            $pageToken = $response->nextPageToken;
        } while (!empty($pageToken));

        return $topics; 
    }

    /**
     * Get or Create a topic in a course.
     * 
     * @param array $data
     * @return \Google_Service_Classroom_Topic
     */
    public function getOrCreateTopic($data)
    {
        $topics = $this->getTopics($data['courseId']);
        $foundTopic = null;
        if ($topics) {
            // Find a duplicate..
            foreach ($topics as $topic) {
                if ($topic->name === $data['name']) {
                    $foundTopic = $topic;
                    break;
                }
            }
        }
        return ($foundTopic ? $foundTopic : $this->createTopic($data));
    }

    /**
     * Get whole project as a course in Google Classroom
     * It will create playlists as topics, and activities as assignments.
     * If a course already exists, then playlists and activities will be created in that.
     * 
     * @param Project $project The project model object
     * @param int|null $courseId The id of the course
     * @return array
     */
    public function createProjectAsCourse(Project $project, $courseId = null) {
        $return = [];
        // If course already exists 
        $course = NULL;
        if ($courseId) {
            $course = $this->getCourse($courseId);
        } else {
            $courseData = [
                'name' => $project->name,
                'descriptionHeading' => $project->description,
                'description' => $project->description,
                'room' => '1', // optional
                'ownerId' => 'me',
                'courseState' => self::COURSE_CREATE_STATE
            ];
            $course = $this->createCourse($courseData);
        }
        
        $return = GCCourseResource::make($course)->resolve();
        
        // inserting playlists/topics to Classroom
        $playlists = $project->playlists;
        $count = 0;
        $return['topics'] = [];

        // Existing topics that the course has.
        $existingTopics = $this->getTopics($course->id);
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
                $topic = $this->createTopic($topicData);
                // Pushing to existing topics
                $existingTopics[] = $topic;
            }

            $return['topics'][$count] = GCTopicResource::make($topic)->resolve();

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
                $courseWork = $this->createCourseWork($courseWorkData);
                
                $return['topics'][$count]['course_work'][] = GCCourseWorkResource::make($courseWork)->resolve();
                $courseWorkCount++;
            }
            $count++;
        }
        
        return $return;
    }
}
