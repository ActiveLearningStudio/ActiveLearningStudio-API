<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\CreateAssignmentCommand;
use App\CurrikiGo\Canvas\Commands\CreateAssignmentGroupsCommand;
use App\CurrikiGo\Canvas\Commands\GetUsersCommand;
use App\CurrikiGo\Canvas\Commands\GetEnrollmentsCommand;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleItemCommand;
use App\CurrikiGo\Canvas\Commands\GetCourseEnrollmentCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseEnrollmentCommand;
use App\CurrikiGo\Canvas\Commands\GetAssignmentGroupsCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\CurrikiGo\Canvas\Helpers\Enrollment as EnrollmentHelper;
use App\Models\Playlist as PlaylistModel;
use App\CurrikiGo\Canvas\Course as CanvasCourse;
use App\Http\Resources\V1\CurrikiGo\CanvasPlaylistResource;
use Illuminate\Support\Facades\Auth;

/**
 * Playlist class for handling playlist publishing to Canvas LMS
 */
class Playlist
{
    /**
     * Canvas Client instance
     * 
     * @var \App\CurrikiGo\Canvas\Client
     */
    private $canvasClient;
    
    /**
     * Make an instance of the class
     * 
     * @param \App\CurrikiGo\Canvas\Client $client
     */
    public function __construct(Client $client)
    {
        $this->canvasClient = $client;
    }

    /**
     * Send a playlist to Canvas LMS
     * 
     * @param \App\Models\Playlist $playlist
     * @param array $data 
     * @return array
     */
    public function send(PlaylistModel $playlist, $data, $create_assignment)
    {
        $user = Auth::user();
        $projectNameSlug = CourseHelper::urlTitle($playlist->project->name);
        $sisId = $projectNameSlug . '-' . $user->id . '-' . $playlist->project->id;

        $lmsSettings = $this->canvasClient->getLmsSettings();
        $playlistItem = null;
        $moduleName = Client::CURRIKI_MODULE_NAME;
        $accountId = "self";

        $courseData = ['name' => $playlist->project->name];
        // Addig a date stamp to sis id
        $sisId .=  '-' . date('YmdHis');
        $course = $this->canvasClient->run(new CreateCourseCommand($accountId, $courseData, $sisId));

        if ($create_assignment == config('constants.canvas_creation_type.create_modules')) {
            $module = $this->canvasClient->run(new CreateModuleCommand($course->id, ["name" => $moduleName]));
            $moduleItem['title'] = $playlist->title . ($data['counter'] > 0 ? ' (' . $data['counter'] . ')' : '');
            $moduleItem['content_id'] = $playlist->id;
            $moduleItem['external_url'] = config('constants.curriki-tsugi-host') . "?playlist=" . $playlist->id;
            $playlistItem = $this->canvasClient->run(new CreateModuleItemCommand($course->id, $module->id, $moduleItem));
        } else {
            $activities = $playlist->activities;
            $createAssignmentGroup = $this->canvasClient->run(new CreateAssignmentGroupsCommand($course->id, $playlist->title));

            if ($createAssignmentGroup) {
                foreach ($activities as $activity) {
                    $createAssignment = $this->canvasClient->run(new CreateAssignmentCommand($course->id, $createAssignmentGroup->id, $activity->title, $activity->id));
                }
            }
        }

        // enroll user to course as teacher
        $enrollments = $this->canvasClient->run(new GetCourseEnrollmentCommand($course->id, '?type[]=TeacherEnrollment'));
        if ($lmsSettings->lms_login_id && !EnrollmentHelper::isEnrolled($lmsSettings->lms_login_id, $enrollments)) {
            $users = $this->canvasClient->run(new GetUsersCommand($accountId, '?search_term=' . $lmsSettings->lms_login_id));
            $userIndex = array_search($lmsSettings->lms_login_id, array_column($users, 'login_id'));
            $user = $userIndex !== false ? $users[$userIndex] : null;
            if ($user) {
                $enrollmentData = ['enrollment' => ['user_id' => $user->id, 'type' => 'TeacherEnrollment', 'enrollment_state' => 'active', 'notify' => true]];
                $this->canvasClient->run(new CreateCourseEnrollmentCommand($course->id, $enrollmentData));
            }
        }

        return $course;
    }
}
