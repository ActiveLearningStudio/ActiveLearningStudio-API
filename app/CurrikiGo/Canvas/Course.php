<?php
namespace App\CurrikiGo\Canvas;
use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\GetModuleItemsCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\Models\Project;

class Course
{
    private $canvas_client;

    public function __construct(Client $client) {
        $this->canvas_client = $client;        
    }

    public function fetch(Project $project)
    {
        $playlist = null;
        $module_name = "Curriki Playlists";        
        $account_id = "self";
        $courses = $this->canvas_client->run(new GetCoursesCommand($account_id, $project->name));
        $course = CourseHelper::getByName($courses, $project->name);
        
        $module_items = [];
        if ($course) {
            $modules = $this->canvas_client->run(new GetModulesCommand($course->id, $module_name));
            $module = CourseHelper::getModulesByName($modules, $module_name);
            $m_itms = $this->canvas_client->run(new GetModuleItemsCommand($course->id, $module->id));
            foreach ($m_itms as $key => $item) {
                $module_items[] = $item->title;
            }
            return ['course' => $course->name, 'playlists' => $module_items];
        } else {
            return ['course' => null, 'playlists' => []];
        }

    }
}
