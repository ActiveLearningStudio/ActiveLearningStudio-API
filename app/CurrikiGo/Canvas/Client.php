<?php

namespace App\CurrikiGo\Canvas;

use App\Models\CurrikiGo\LmsSetting;
use App\CurrikiGo\Canvas\Contracts\Command;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Http\Resources\V1\CurrikiGo\LmsSettingResource;

/**
 * Client class for executing Canvas API end points.
 */
class Client
{
    /**
     * Module name for Curriki playlist in Canvas
     * 
     * @var string
     */
    const CURRIKI_MODULE_NAME = 'Curriki Playlists';
    /**
     * API version
     * 
     * @var string
     */
    const API_VERSION = 'v1';

    /**
     * LMS Settings modal object
     * 
     * @var \App\Models\CurrikiGo\LmsSetting
     */
    private $lmsSetting;
    
    /**
     * Create a new Client instance.
     *
     * @param  \App\Models\CurrikiGo\LmsSetting  $lmsSetting
     * @return void
     */
    public function __construct(LmsSetting $lmsSetting)
    {
        $this->lmsSetting = $lmsSetting;
    }

    /**
     * Run a Canvas API command
     * 
     * @param \App\CurrikiGo\Canvas\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        $apiVersion = self::API_VERSION;
        $command->apiURL = $this->lmsSetting->lms_url . "/api/" . $apiVersion;
        $command->accessToken = $this->lmsSetting->lms_access_token;
        $command->httpClient = new \GuzzleHttp\Client();
        return $command->execute();
    }

    /**
     * Return lmsSettings
     */
    public function getLmsSettings()
    {
        return $this->lmsSetting;
    }
}
