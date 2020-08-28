<?php
namespace App\CurrikiGo\Canvas;
use App\Models\CurrikiGo\LmsSetting;
use App\CurrikiGo\Canvas\Contracts\Command;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Http\Resources\V1\CurrikiGo\LmsSettingResource;

class Client
{
    private $lms_setting;
    
    public function __construct(LmsSetting $lms_setting) {
        $this->lms_setting = $lms_setting;
    }

    public function run(Command $command)
    {
        $api_version = 'v1';
        
        $command->api_url = $this->lms_setting->lms_url."/api/".$api_version;
        $command->access_token = $this->lms_setting->lms_access_token;
        
        $command->http_client = new \GuzzleHttp\Client();
        return $command->execute();
    }
}
