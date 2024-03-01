<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Brightcove Video By Ids API end points
 * ClassName    GetSrtVideoById
*/
namespace App\CurrikiGo\Brightcove\Videos;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Videos\GetVideoListByIdsCommand;

class GetSrtVideoById
{
    /**
     * Brightcove GetSrtVideoById instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new GetSrtVideoById instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Fetch a video by id from Brightcove
     * @param object $setting, string $videoId, string $type, string $srtContent
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $videoId = '', $type = '', $srtContent)
    {
        $setting->account_id = ( $type === 'media_catalog' ) ? $setting->api_setting_id : $setting->account_id;
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if ( isset($getToken['Authorization']) ) {            
            $response = $this->bcAPIClient->run(new GetVideoListByIdsCommand($setting, $getToken, $videoId));
            if ( $response ) {
                $response['srt_content'] = $srtContent;
                return $response;
            } else {
                throw new GeneralException('No Record Found!');
            }            
        }
    }

}
