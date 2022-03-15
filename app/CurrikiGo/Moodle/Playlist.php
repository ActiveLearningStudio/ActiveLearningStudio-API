<?php

namespace App\CurrikiGo\Moodle;

use App\Models\CurrikiGo\LmsSetting;
use App\Models\Playlist as PlaylistModel;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Activity;

class Playlist
{
    private $lmsSetting;
    private $client;

    public function __construct($lmsSetting)
    {
        $this->lmsSetting = $lmsSetting;
        $this->client = new \GuzzleHttp\Client();
    }

    public function send(PlaylistModel $playlist, $data)
    {        
        $organizationId = Project::where('id', $playlist->project_id)->value('organization_id');
        
        // Add Grade level of first activity on project manifest
        $organizationName = Organization::where('id', $organizationId)->value('name');
        
        $grade_name = $this->getActivityGrade($playlist->project_id, 'education_level_id');
        $subject_name = $this->getActivityGrade($playlist->project_id, 'subject_id');
        
        $web_service_token = $this->lmsSetting->lms_access_token;
        $lms_host = $this->lmsSetting->lms_url;
        $web_service_function = "local_curriki_moodle_plugin_create_playlist";

        $web_service_url = $lms_host . "/webservice/rest/server.php";
        $rquest_params = [
            "wstoken" => $web_service_token,
            "wsfunction" => $web_service_function, 
            "moodlewsrestformat" => "json",
            "entity_name" => $playlist->title . ($data['counter'] > 0 ? ' (' .$data['counter'] . ')' : ''),
            "entity_type" => "playlist",
            "entity_id" => $playlist->id,
            "parent_name" => $playlist->project->name,
            "parent_type" => "program",
            "project_id" => $playlist->project_id,
            "tool_url" => config('constants.curriki-tsugi-host'),
            "org_name" => $organizationName,
            "grade_name" => $grade_name,
            "subject_name" => $subject_name
        ];
        $response = $this->client->request('GET', $web_service_url, ['query' => $rquest_params]);
        return $response;
    }

    private function getActivityGrade($projectId, $activityParam)
    {
        \Log::info($projectId);
        $playlistId = PlaylistModel::where('project_id', $projectId)->orderBy('order','asc')->limit(1)->value('id');
        \Log::info($playlistId);
        
        $activity = Activity::where('playlist_id', $playlistId)->orderBy('order','asc')->limit(1)->value($activityParam);
        \Log::info($activity);
        return $activity;

    }
}
