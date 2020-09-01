<?php

namespace App\CurrikiGo\Moodle;

use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;

class Course
{
    private $settingId;
    private $client;

    public function __construct($settingId)
    {
        $this->settingId = $settingId;
        $this->client = new \GuzzleHttp\Client();
    }

    public function fetch($data)
    {        
        $project = Project::where('_id',$data["project_id"])->first();
        if (!$project) {
            return null;
        }

        $lms_setting = LmsSetting::where('_id', $this->settingId)->first();
        $web_service_token = $lms_setting->lms_access_token;
        $lms_host = $lms_setting->lms_url;
        $web_service_function = "local_curriki_moodle_plugin_fetch_course";

        $web_service_url = $lms_host . "/webservice/rest/server.php";
        $rquest_params = [
            "wstoken" => $web_service_token,
            "wsfunction" => $web_service_function, 
            "moodlewsrestformat" => "json",
            "name" => $project->name
        ];
        $response = $this->client->request('GET', $web_service_url, ['query' => $rquest_params]);
        return $response;
    }
}
