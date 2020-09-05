<?php

namespace App\CurrikiGo\Moodle;

use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;

class Course
{
    private $lmsSetting;
    private $client;

    public function __construct($lmsSetting)
    {
        $this->lmsSetting = $lmsSetting;
        $this->client = new \GuzzleHttp\Client();
    }

    public function fetch($project)
    {
        $web_service_token = $this->lmsSetting->lms_access_token;
        $lms_host = $this->lmsSetting->lms_url;
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
