<?php
namespace App\CurrikiGo\Canvas;
use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetUserCommand;
use App\CurrikiGo\Canvas\Commands\GetEnrollmentsCommand;
use App\CurrikiGo\Canvas\Commands\GetCoursesCommand;
use App\CurrikiGo\Canvas\Commands\CreateCourseCommand;
use App\CurrikiGo\Canvas\Commands\GetModulesCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleCommand;
use App\CurrikiGo\Canvas\Commands\CreateModuleItemCommand;
use App\CurrikiGo\Canvas\Helpers\Course as CourseHelper;
use App\Models\Playlist as PlaylistModel;

class Playlist
{
    private $setting_id;
    public function __construct($setting_id) {
        $this->setting_id = $setting_id;        
    }

    public function send($data)
    {
        $playlist_record = PlaylistModel::where('_id',$data["playlist_id"])->first();
        $playlist = null;
        $module_name = "Curriki Playlists";
        
        $canvas_client = new Client($this->setting_id);        
        $account_id = "self";        
        $courses = $canvas_client->run(new GetCoursesCommand($account_id, $playlist_record->project->name));
        $course = CourseHelper::getByName($courses, $playlist_record->project->name);
        if($course){
            // add playlist to existing course
            $modules = $canvas_client->run(new GetModulesCommand($course->id, $module_name));
            $module = CourseHelper::getModulesByName($modules, $module_name);
            if(!$module){
                $module = $canvas_client->run(new CreateModuleCommand($course->id, ["name" => $module_name]));
            }                
            $module_item['title'] = $playlist_record->title . ($data['counter'] > 0 ? ' ('.$data['counter'].')' : '');
            $module_item['content_id'] = $playlist_record->_id;
            $module_item['external_url'] = config('constants.curriki-tsugi-host')."?playlist=".$playlist_record->_id;
            $playlist = $canvas_client->run(new CreateModuleItemCommand($course->id, $module->id, $module_item));                
        }else {
            // create new course and add playlist
            $course = $canvas_client->run(new CreateCourseCommand($account_id, ['name' => $playlist_record->project->name]));
            $module = $canvas_client->run(new CreateModuleCommand($course->id, ["name" => $module_name]));
            $module_item['title'] = $playlist_record->title . ($data['counter'] > 0 ? ' ('.$data['counter'].')' : '');
            $module_item['content_id'] = $playlist_record->_id;
            $module_item['external_url'] = config('constants.curriki-tsugi-host')."?playlist=".$playlist_record->_id;
            $playlist = $canvas_client->run(new CreateModuleItemCommand($course->id, $module->id, $module_item));
        }
        
        return $playlist;
    }
}
