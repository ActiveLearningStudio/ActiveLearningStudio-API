<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Brightcove Video By Ids API end points
 * ClassName    GetVideoListByIds
*/
namespace App\CurrikiGo\Brightcove\Videos;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Videos\GetVideoListByIdsCommand;

class GetVideoListByIds
{
    /**
     * Brightcove GetVideoListByIds instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new GetVideoListByIds instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Fetch a video list by ids from Brightcove
     * @param object $setting, string $videoIds
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $videoIds = '')
    {
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if ( isset($getToken['Authorization']) ) {            
            $response = $this->bcAPIClient->run(new GetVideoListByIdsCommand($setting, $getToken, $videoIds));
            if ( $response ) {                
                return response()->json(['data' => $response]);
            } else {
                throw new GeneralException('No Record Found!');
            }            
        }
    }

}
