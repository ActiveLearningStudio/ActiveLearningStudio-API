<?php
namespace App\CurrikiGo\Canvas;
use App\Models\CurrikiGo\LmsSetting;
use App\CurrikiGo\Canvas\Contracts\Command;
class Client
{
    private $setting_id;
    public function __construct($setting_id) {
        $this->setting_id = $setting_id;
    }

    public function run(Command $command)
    {
        $api_version = 'v1';
        $lms_setting = LmsSetting::where('_id', $this->setting_id)->first();        
        $command->api_url = $lms_setting->lms_url."/api/".$api_version;
        $command->asscess_token = $lms_setting->lms_access_token;
        $command->http_client = new \GuzzleHttp\Client();
        return $command->execute();
    }
}
