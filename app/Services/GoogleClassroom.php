<?php
namespace App\Services;

/**
 * Google Classroom Service class
 */
class GoogleClassroom {

    protected $service;

    function __construct() {

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

    public function getClient() {
        return $this->service->getClient();
    }

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

    public function getCourse($courseId)
    {
        return $this->service->courses->get($courseId);
    }

    public function createCourse($data) {
        $course = new \Google_Service_Classroom_Course($data);
        return $this->service->courses->create($course);
    }

    public function createTopic($data) {
        $topic = new \Google_Service_Classroom_Topic($data);
        return $this->service->courses_topics->create($data['courseId'], $topic);
    }

    public function createCourseWork($data) {

        $courseWork = new \Google_Service_Classroom_CourseWork();
        $courseWork->setCourseId($data['course_id']);
        $courseWork->setTopicId($data['topic_id']);
        $courseWork->setTitle($data['activity_title']);
        $courseWork->setWorkType('ASSIGNMENT');
        $courseWork->setMaterials([
            'link'=> [
                'url' => config('constants.front-url').'/shared/activity/'.$data['activity_id']
            ]
        ]);
        $courseWork->setState('PUBLISHED');

        return $this->service->courses_courseWork->create($data['course_id'], $courseWork);
    }

    public function getTopics($courseId) {
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

    public function getOrCreateTopic($data) {
        $topics = $this->getTopics($data['courseId']);
        $foundTopic = null;
        if ($topics) {
            // Find a duplicate..
            foreach($topics as $topic) {
                if ($topic->name === $data['name'])  {
                    $foundTopic = $topic;
                    break;
                }
            }
        }

        return ($foundTopic ? $foundTopic : $this->createTopic($data));
    }
}