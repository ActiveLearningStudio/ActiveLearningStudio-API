<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\CreateAssignmentCommand;
use App\CurrikiGo\Canvas\Commands\CreateAssignmentGroupsCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseCommand;
use App\CurrikiGo\Canvas\Commands\GetAllCoursesCommand;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\GetAssignmentGroupsCommand;
use App\CurrikiGo\Canvas\Commands\GetModuleItemsCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

use function Doctrine\Common\Cache\Psr6\get;

/**
 * Class for fetching courses from Canvas LMS
 */
class Course
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
     * Fetch a course from Canvas LMS
     * 
     * @param \App\Models\Project $project
     * @return array
     */
    public function fetch(Project $project)
    {
        $playlist = null;
        $moduleName = Client::CURRIKI_MODULE_NAME;
        $accountId = "self";

        $user = Auth::user();
        $projectNameSlug = CourseHelper::urlTitle($project->name);
        $sisId = $projectNameSlug . '-' . $user->id . '-' . $project->id;

        $courses = $this->canvasClient->run(new GetCoursesCommand($accountId, $project->name, $sisId));
        $course = CourseHelper::getBySisId($courses, $sisId);
        
        $moduleItems = [];
        if ($course) {
            $assignmentGroups = $this->canvasClient->run(new GetAssignmentGroupsCommand($course->id));
            $modules = $this->canvasClient->run(new GetModulesCommand($course->id, $moduleName));
            $module = CourseHelper::getModuleByName($modules, $moduleName);
            if ($module) {
                $items = $this->canvasClient->run(new GetModuleItemsCommand($course->id, $module->id));
                foreach ($items as $key => $item) {
                    $moduleItems[] = $item->title;
                }
            }
            return [
                'course' => $course->name,
                'playlists' => $moduleItems,
                'assignment_groups' => $assignmentGroups
            ];
        }
        
        return ['course' => null, 'playlists' => [], 'assignment_groups' => []];        
    }

    /**
     * Fetch all courses from Canvas LMS
     * 
     * @return array
     */
    public function fetchAllCourses()
    {
        $moduleItems = [];
        $courses = $this->canvasClient->run(new GetAllCoursesCommand());
        if ($courses) {
            foreach ($courses as $key => $item) {
                $moduleItems[$key]['id'] = $item->id;
                $moduleItems[$key]['name'] = $item->name;
            }
        }
        return $moduleItems;
    }

    /**
     * Fetch assignmet groups from Canvas LMS
     * 
     * @param $courseId
     * @return array
     */
    public function fetchAssignmentGroups($courseId)
    {
        $assignmentGroups = $this->canvasClient->run(new GetAssignmentGroupsCommand($courseId));
        $moduleItems = [];
        if ($assignmentGroups) {
            foreach ($assignmentGroups as $key => $item) {
                $moduleItems[$key]['id'] = $item->id;
                $moduleItems[$key]['name'] = $item->name;
            }
        }
        return $moduleItems;
    }

    /**
     * Create assignmet groups in Canvas LMS course
     * 
     * @param $courseId
     * @param $assignmentGroupName
     * @return array
     */
    public function CreateAssignmentGroups($courseId, $assignmentGroupName)
    {
        return $this->canvasClient->run(new CreateAssignmentGroupsCommand($courseId, $assignmentGroupName));
    }

    /**
     * Create new course groups in Canvas LMS course
     * 
     * @param $courseName
     * @return string
     */
    public function createNewCourse($courseName)
    {
        return $this->canvasClient->run(new CreateCourseCommand($courseName, $accountId = 'self'));
    }

    /**
     * Create assignmet in Canvas LMS course
     * 
     * @param $courseId
     * @param $AssignmentGroupId
     * @param $AssignmentName
     * @param $currikiActivityId
     * @return array
     */
    public function createActivity($courseId, $AssignmentGroupId, $AssignmentName, $currikiActivityId)
    {
        return $this->canvasClient->run(new CreateAssignmentCommand($courseId, $AssignmentGroupId, $AssignmentName, $currikiActivityId));
    }
}
