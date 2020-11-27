<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\GetModuleItemsCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

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
            $modules = $this->canvasClient->run(new GetModulesCommand($course->id, $moduleName));
            $module = CourseHelper::getModuleByName($modules, $moduleName);
            if ($module) {
                $items = $this->canvasClient->run(new GetModuleItemsCommand($course->id, $module->id));
                foreach ($items as $key => $item) {
                    $moduleItems[] = $item->title;
                }
            }
            return ['course' => $course->name, 'playlists' => $moduleItems];
        }
        
        return ['course' => null, 'playlists' => []];        
    }
}
