<?php

namespace App\CurrikiGo\Moodle;

use App\Models\CurrikiGo\LmsSetting;
use App\Models\Playlist as PlaylistModel;

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
        ];
        $response = $this->client->request('GET', $web_service_url, ['query' => $rquest_params]);
        return $response;
    }
}
