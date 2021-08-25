<?php

namespace App\Services;

use App\Repositories\GoogleClassroom\GoogleClassroomRepository;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Services\GoogleClassroomInterface;
use App\Http\Resources\V1\GCCourseResource;
use App\Http\Resources\V1\GCTopicResource;
use App\Http\Resources\V1\GCCourseWorkResource;
use App\Repositories\GcClasswork\GcClassworkRepositoryInterface;
use App\Exceptions\GeneralException;
use App\Models\Project;
use Illuminate\Http\Request;

/**
 * Google Classroom Service class
 */
class GoogleClassroom implements GoogleClassroomInterface
{
    /**
     * Google Classroom Service object
     *
     * @var \Google_Service_Classroom
     */
    protected $service;

    /**
     * Google Classwork repository  object
     *
     * @var GcClassworkInterface
     */
    protected $gc_classwork;

    /**
     * Creates an instance of the class
     *
     * @param $accessTokenStr
     *
     * @return void
     */
    function __construct($accessTokenStr = null)
    {
        $client = new \Google_Client();
        $client->setApplicationName(config('google.gapi_application_name'));
        $client->setScopes(
            [
                \Google_Service_Classroom::CLASSROOM_COURSES_READONLY,
                \Google_Service_Classroom::CLASSROOM_COURSES,
                \Google_Service_Classroom::CLASSROOM_TOPICS,
                \Google_Service_Classroom::CLASSROOM_COURSEWORK_ME,
                \Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS,
                \Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
                \Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS,
            ]
        );
        $credentials = config('google.gapi_class_credentials');

        $client->setAuthConfig(json_decode($credentials, true));
        // $client->setAuthConfig(public_path().'/googleapi/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // If token is provided, then prefer that.
        if ($accessTokenStr) {
            $accessToken = json_decode($accessTokenStr, true);
            $client->setAccessToken($accessToken);
        } elseif (auth()->user()) {
            // @Todo - at the moment, we're taking token from the database for the authenticated user.
            $accessToken = json_decode(auth()->user()->gapi_access_token, true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                throw new GeneralException('Did not get a proper Google Classroom token, '.
                'either you have no access to Classroom, '.
                'or you may need to revoke the permission for this app. ');
                // @TODO - this flow doesn't actually kick in.
                // Request authorization from the user.
                // The flow below maybe implemented later on.

                /* $authUrl = $client->createAuthUrl();
                // header("Location: $authUrl");
                // printf("Open the following link in your browser:\n%s\n", $authUrl);
                // print 'Enter verification code: ';
                $authCode = $_GET['code'];

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new GeneralException(join(', ', $accessToken));
                } */
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
     * Set GcClasswork repository object
     *
     * @param GcClassworkRepositoryInterface $gcClassworkRepository
     *
     * @return void
     */
    public function setGcClassworkObject(GcClassworkRepositoryInterface $gcClassworkRepository)
    {
        return $this->gc_classwork = $gcClassworkRepository;
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
                'url' => $data['activity_link']
            ]
        ]);
        $courseWork->setMaxpoints(100);
        $courseWork->setState(self::COURSEWORK_STATE_DRAFT);

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
     * @param Project $project
     * @param int|null $courseId The id of the course
     * @param GoogleClassroomRepositoryInterface $googleClassroomRepository
     * @return array
     * @throws GeneralException
     */
    public function createProjectAsCourse(Project $project, $courseId = null, GoogleClassroomRepositoryInterface $googleClassroomRepository)
    {
        if (!$this->gc_classwork) {
            throw new GeneralException("GcClasswork repository object is required");
        }
        $frontURL = $this->getFrontURL();
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

        $googleClassroomData = $googleClassroomRepository->saveCourseShareToGcClass($course);
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
            foreach ($activities as $activity) {
                if (empty($activity->title)) {
                    continue;
                }
                $activity->shared = true;
                $activity->save();

                // Make an assignment URL with context of
                // classroom id, user id (teacher), and the h5p activity id
                $userId = auth()->user()->id;
                $activityLink = '/gclass/launch/' . $userId . '/' . $course->id . '/' . $activity->id;

                // We need to save the classwork id in the database, and also need to retrieve it later.
                // So we make a dummy insertion in the database, and append the id in the link.
                // We have to do this way because, we cannot update the 'Materials' link for classwork
                $classworkItem = $this->gc_classwork->create([
                    'classwork_id' => uniqid(),
                    'path' => $activityLink,
                    'course_id' => $course->id
                ]);

                if ($classworkItem) {
                    // Now, we append the activity link with our database id.
                    $activityLink .= '/' . $classworkItem->id;

                    $courseWorkData = [
                        'course_id' => $course->id,
                        'topic_id' => $topic->topicId,
                        'activity_id' => $activity->id,
                        'activity_title' => $activity->title,
                        'activity_link' => $frontURL . $activityLink
                    ];
                    $courseWork = $this->createCourseWork($courseWorkData);

                    // Once coursework id is generated, we'll update that in our database.
                    $this->gc_classwork->update([
                        'classwork_id' => $courseWork->id,
                        'path' => $activityLink
                    ], $classworkItem->id);

                    $return['topics'][$count]['course_work'][] = GCCourseWorkResource::make($courseWork)->resolve();
                }
            }
            $count++;
        }

        return $return;
    }

    /**
     * Check if student is enrolled in a class
     *
     * @param int $courseId
     * @param string $userId
     * @return \Google_Service_Classroom_Student
     */
    public function getEnrolledStudent($courseId, $userId = "me")
    {
        return $this->service->courses_students->get($courseId, $userId);
    }

    /**
     * Get first student's submission for a classwork in a course.
     *
     * @param int $courseId
     * @param string $classworkId
     * @param mixed $userId
     * @return \Google_Service_Classroom_ListStudentSubmissionsResponse
     */
    public function getFirstStudentSubmission($courseId, $classworkId, $userId = "me")
    {
        $submissions = $this->getStudentSubmissions($courseId, $classworkId, $userId);
        // Grab the first submission
        $first = $submissions[0];

        return $first;
    }

    /**
     * Get student's submissions for a classwork in a course.
     *
     * @param int $courseId
     * @param string $classworkId
     * @param mixed $userId
     * @return \Google_Service_Classroom_ListStudentSubmissionsResponse
     */
    public function getStudentSubmissions($courseId, $classworkId, $userId = "me")
    {
        $studentSubmissions = $this->service->courses_courseWork_studentSubmissions;
        // Submissions associated with this courseWork for this student
        $retval = $studentSubmissions->listCoursesCourseWorkStudentSubmissions(
            $courseId,
            $classworkId,
            array('userId' => $userId)
        );
        // Grab the first submission
        return $retval->studentSubmissions;
    }

    /**
     * Adds/modifies submission with an attachment
     *
     * @param int $courseId The course id
     * @param string $classworkId The classwork id
     * @param string $id The submission id
     * @param string $attachmentLink The URL for attachment
     * @return string
     */
    public function modifySubmissionAttachment($courseId, $courseWorkId, $id, $attachmentLink)
    {
        $studentSubmissions = $this->service->courses_courseWork_studentSubmissions;
        $frontURL = $this->getFrontURL();
        $postBody = [
            'link' => [
                'url' => $frontURL . $attachmentLink
            ]
        ];
        $requestBody = new \Google_Service_Classroom_ModifyAttachmentsRequest;
        $requestBody->setAddAttachments($postBody);
        return $studentSubmissions->modifyAttachments($courseId, $courseWorkId, $id, $requestBody);
    }

    /**
     * TurnIn an assignment
     *
     * @param int $courseId The course id
     * @param string $classworkId The classwork id
     * @param string $id The submission id
     * @return \Google_Service_Classroom_ClassroomEmpty
     */
    public function turnIn($courseId, $courseWorkId, $id)
    {
        $studentSubmissions = $this->service->courses_courseWork_studentSubmissions;
        $requestBody = new \Google_Service_Classroom_TurnInStudentSubmissionRequest;
        return $studentSubmissions->turnIn($courseId, $courseWorkId, $id, $requestBody);
    }

    /**
     * Check if user is a teacher in a class
     *
     * @param int $courseId
     * @param string $userId
     * @param array $optParams
     * @return \Google_Service_Classroom_Teacher
     */
    public function getCourseTeacher($courseId, $userId = "me", $optParams = [])
    {
        return $this->service->courses_teachers->get($courseId, $userId, $optParams);
    }

    /**
     * Get student's submission for a classwork in a course by submission id
     *
     * @param int $courseId The Google classroom course id
     * @param string $classworkId The classwork id
     * @param string $id The submission id
     * @return \Google_Service_Classroom_StudentSubmission
     */
    public function getStudentSubmissionById($courseId, $classworkId, $id)
    {
        $studentSubmissions = $this->service->courses_courseWork_studentSubmissions;
        return $studentSubmissions->get(
            $courseId,
            $classworkId,
            $id
        );
    }

    /**
     * Get user profile by id
     *
     * @param string $userId The Google Classroom user id
     * @return \Google_Service_Classroom_StudentSubmission
     */
    public function getUserProfile($userId)
    {
        // GET https://classroom.googleapis.com/v1/userProfiles/{userId}
        $userProfiles = $this->service->userProfiles;
        return $userProfiles->get(
            $userId
        );
    }

    /**
     * Check if the assignment is in a submitted state
     * Turned in and 'returned' (by teacher) are both considered submitted for our use case.
     *
     * @param string $state The state of the assignment.
     * @return boolean
     */
    public function isAssignmentSubmitted($state)
    {
        $submittedStates = [self::ASSIGNMENT_STATE_TURNED_IN, self::ASSIGNMENT_STATE_RETURNED];
        return (in_array($state, $submittedStates) ? true : false);
    }

    /**
     * Determine front URL of the application.
     *
     * @todo Move it to a helper class.
     *
     * @return string
     */
    private function getFrontURL() {
        $front_url = config('constants.front-url');
        if (strpos($front_url,'://') === false) {
            // If not an absolute path, then get the origin.
            $front_url = request()->headers->get('origin');
            if (!$front_url) {
                // If nothing works, take the http host
                $front_url = request()->getSchemeAndHttpHost();
            }
        }
        return $front_url;
    }
}
