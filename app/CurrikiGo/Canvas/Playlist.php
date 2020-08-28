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
use App\HTTP\Resources\V1\CurrikiGo\CanvasPlaylistResource;

class Playlist
{
    private $canvas_client;

    public function __construct(Client $client) {
        $this->canvas_client = $client;        
    }

    public function send(PlaylistModel $playlist_record, $data)
    {
        $playlist = null;
        $module_name = "Curriki Playlists";
        
        $account_id = "self";        
        $courses = $this->canvas_client->run(new GetCoursesCommand($account_id, $playlist_record->project->name));
        $course = CourseHelper::getByName($courses, $playlist_record->project->name);
        
        if ($course) {
            // add playlist to existing course
            $modules = $this->canvas_client->run(new GetModulesCommand($course->id, $module_name));
            $module = CourseHelper::getModulesByName($modules, $module_name);
            if (!$module) {
                $module = $this->canvas_client->run(new CreateModuleCommand($course->id, ["name" => $module_name]));
            }                
            $module_item['title'] = $playlist_record->title . ($data['counter'] > 0 ? ' ('.$data['counter'].')' : '');
            $module_item['content_id'] = $playlist_record->id;
            $module_item['external_url'] = config('constants.curriki-tsugi-host')."?playlist=".$playlist_record->id;
            $playlist = $this->canvas_client->run(new CreateModuleItemCommand($course->id, $module->id, $module_item));                
        } else {
            // create new course and add playlist
            $course = $this->canvas_client->run(new CreateCourseCommand($account_id, ['name' => $playlist_record->project->name]));
            $module = $this->canvas_client->run(new CreateModuleCommand($course->id, ["name" => $module_name]));
            $module_item['title'] = $playlist_record->title . ($data['counter'] > 0 ? ' ('.$data['counter'].')' : '');
            $module_item['content_id'] = $playlist_record->id;
            $module_item['external_url'] = config('constants.curriki-tsugi-host')."?playlist=".$playlist_record->id;
            $playlist = $this->canvas_client->run(new CreateModuleItemCommand($course->id, $module->id, $module_item));
        }
        
        return CanvasPlaylistResource::make($playlist)->resolve();
    }
}
