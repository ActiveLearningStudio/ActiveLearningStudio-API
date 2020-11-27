<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetUsersCommand;
use App\CurrikiGo\Canvas\Commands\GetEnrollmentsCommand;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleItemCommand;
use App\CurrikiGo\Canvas\Commands\GetCourseEnrollmentCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseEnrollmentCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\CurrikiGo\Canvas\Helpers\Enrollment as EnrollmentHelper;
use App\Models\Playlist as PlaylistModel;
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
    public function send(PlaylistModel $playlist, $data)
    {
        $user = Auth::user();
        $projectNameSlug = CourseHelper::urlTitle($playlist->project->name);
        $sisId = $projectNameSlug . '-' . $user->id . '-' . $playlist->project->id;
        
        $lmsSettings = $this->canvasClient->getLmsSettings();
        $playlistItem = null;
        $moduleName = Client::CURRIKI_MODULE_NAME;
        $accountId = "self";

        $courses = $this->canvasClient->run(new GetCoursesCommand($accountId, $playlist->project->name, $sisId));
        $course = CourseHelper::getBySisId($courses, $sisId);
        
        if ($course) {
            // enroll user to existing course as teacher if not enrolled
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

            // add playlist to existing course
            $modules = $this->canvasClient->run(new GetModulesCommand($course->id, $moduleName));
            $module = CourseHelper::getModuleByName($modules, $moduleName);
            if (!$module) {
                $module = $this->canvasClient->run(new CreateModuleCommand($course->id, ["name" => $moduleName]));
            }
            $moduleItem['title'] = $playlist->title . ($data['counter'] > 0 ? ' (' . $data['counter'] . ')' : '');
            $moduleItem['content_id'] = $playlist->id;
            $moduleItem['external_url'] = config('constants.curriki-tsugi-host') . "?playlist=" . $playlist->id;
            $playlistItem = $this->canvasClient->run(new CreateModuleItemCommand($course->id, $module->id, $moduleItem));
        } else {
            // create new course and add playlist
            $courseData = ['name' => $playlist->project->name];
            // Addig a date stamp to sis id
            $sisId .=  '-' . date('YmdHis'); 
            $course = $this->canvasClient->run(new CreateCourseCommand($accountId, $courseData, $sisId));
            $module = $this->canvasClient->run(new CreateModuleCommand($course->id, ["name" => $moduleName]));
            $moduleItem['title'] = $playlist->title . ($data['counter'] > 0 ? ' (' . $data['counter'] . ')' : '');
            $moduleItem['content_id'] = $playlist->id;
            $moduleItem['external_url'] = config('constants.curriki-tsugi-host') . "?playlist=" . $playlist->id;
            $playlistItem = $this->canvasClient->run(new CreateModuleItemCommand($course->id, $module->id, $moduleItem));

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
        }
        
        return CanvasPlaylistResource::make($playlistItem)->resolve();
    }
}
