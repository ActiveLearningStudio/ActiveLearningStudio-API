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
    private $canvasClient;
    
    public function __construct(Client $client)
    {
        $this->canvasClient = $client;
    }

    public function send(PlaylistModel $playlist, $data)
    {
        $playlistItem = null;
        $moduleName = Client::CURRIKI_MODULE_NAME;
        $accountId = "self";
        $courses = $this->canvasClient->run(new GetCoursesCommand($accountId, $playlist->project->name));
        $course = CourseHelper::getByName($courses, $playlist->project->name);
        
        if ($course) {
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
            $course = $this->canvasClient->run(new CreateCourseCommand($accountId, ['name' => $playlist->project->name]));
            $module = $this->canvasClient->run(new CreateModuleCommand($course->id, ["name" => $moduleName]));
            $moduleItem['title'] = $playlist->title . ($data['counter'] > 0 ? ' (' . $data['counter'] . ')' : '');
            $moduleItem['content_id'] = $playlist->id;
            $moduleItem['external_url'] = config('constants.curriki-tsugi-host') . "?playlist=" . $playlist->id;
            $playlistItem = $this->canvasClient->run(new CreateModuleItemCommand($course->id, $module->id, $moduleItem));
        }
        
        return CanvasPlaylistResource::make($playlistItem)->resolve();
    }
}
