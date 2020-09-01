<?php

namespace App\CurrikiGo\Canvas;

use App\Models\CurrikiGo\LmsSetting;
use App\CurrikiGo\Canvas\Contracts\Command;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Http\Resources\V1\CurrikiGo\LmsSettingResource;

class Client
{
    private $lmsSetting;
    const CURRIKI_MODULE_NAME = 'Curriki Playlists';
    const API_VERSION = 'v1';
    
    public function __construct(LmsSetting $lmsSetting)
    {
        $this->lmsSetting = $lmsSetting;
    }

    public function run(Command $command)
    {
        $apiVersion = self::API_VERSION;
        $command->apiURL = $this->lmsSetting->lms_url . "/api/" . $apiVersion;
        $command->accessToken = $this->lmsSetting->lms_access_token;
        $command->httpClient = new \GuzzleHttp\Client();
        return $command->execute();
    }
}
