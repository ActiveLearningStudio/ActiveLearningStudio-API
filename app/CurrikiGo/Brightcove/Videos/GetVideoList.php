<?php
/**
 * @Author      Asim Sarwar
 * Date         16-12-2021
 * Description  Handle Brightcove Video API end points
 * ClassName    GetVideoList
*/
namespace App\CurrikiGo\Brightcove\Videos;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Videos\GetVideoCountCommand;
use App\CurrikiGo\Brightcove\Commands\Videos\GetVideoListCommand;

class GetVideoList
{
    /**
     * Brightcove GetVideoList instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new GetVideoList instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Fetch a videos list from Brightcove
     * @param object $setting, string $queryParam
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $queryParam = '')
    {
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if ( isset($getToken['Authorization']) ) {
            $getCountResponse = $this->bcAPIClient->run(new GetVideoCountCommand($setting, $getToken, $queryParam));
            $response = $this->bcAPIClient->run(new GetVideoListCommand($setting, $getToken, $queryParam));
            if ( $getCountResponse && $response) {                
                return response()->json(['data' => $response, 'meta' => $getCountResponse]);
            } else {
                throw new GeneralException('No Record Found!');
            }            
        }
    }

}
