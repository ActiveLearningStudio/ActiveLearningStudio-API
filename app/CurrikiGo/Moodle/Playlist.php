<?php
namespace App\CurrikiGo\Moodle;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Playlist as PlaylistModel;

class Playlist
{
    private $setting_id;
    private $client;

    public function __construct($setting_id) {
        $this->setting_id = $setting_id;
        $this->client = new \GuzzleHttp\Client();
    }

    public function send($data)
    {        
        $playlist = PlaylistModel::where('_id',$data["playlist_id"])->first();
        $lms_setting = LmsSetting::where('_id', $this->setting_id)->first();
        $web_service_token = $lms_setting->lms_access_token;
        $lms_host = $lms_setting->lms_url;
        $web_service_function = "local_curriki_moodle_plugin_create_playlist";

        $web_service_url = $lms_host . "/webservice/rest/server.php";
        $rquest_params = [
            "wstoken" => $web_service_token,
            "wsfunction" => $web_service_function, 
            "moodlewsrestformat" => "json",
            "entity_name" => $playlist->title . ($data['counter'] > 0 ? ' ('.$data['counter'].')' : ''),
            "entity_type" => "playlist",
            "entity_id" => $playlist->_id,
            "parent_name" => $playlist->project->name,
            "parent_type" => "program",
        ];

        $response = $this->client->request('GET', $web_service_url, ['query' => $rquest_params]);
        return $response;
    }
}
