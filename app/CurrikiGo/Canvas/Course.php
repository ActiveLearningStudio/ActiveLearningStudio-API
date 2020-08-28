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
    private $setting_id;
    public function __construct($setting_id) {
        $this->setting_id = $setting_id;        
    }

    public function fetch($data)
    {

        $project = Project::where('_id',$data["project_id"])->first();
        if(!$project){
            return null;
        }
        
        $playlist = null;
        $module_name = "Curriki Playlists";        
        $canvas_client = new Client($this->setting_id);
        $account_id = "self";
        $courses = $canvas_client->run(new GetCoursesCommand($account_id, $project->name));
        $course = CourseHelper::getByName($courses, $project->name);
        $module_items = [];
        if($course){
            $modules = $canvas_client->run(new GetModulesCommand($course->id, $module_name));
            $module = CourseHelper::getModulesByName($modules, $module_name);
            $m_itms = $canvas_client->run(new GetModuleItemsCommand($course->id, $module->id));
            foreach ($m_itms as $key => $item) {
                $module_items[] = $item->title;
            }
            return ['course' => $course->name, 'playlists' => $module_items];
        }else{
            return ['course' => null, 'playlists' => []];
        }

    }
}
