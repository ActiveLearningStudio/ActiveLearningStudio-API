<?php
/**
 * @Author      Asim Sarwar
 * Date         17-12-2021
 * Description  Handle Brightcove Video Count API end points
 * ClassName    GetVideoCount
*/
namespace App\CurrikiGo\Brightcove\Videos;

use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Videos\GetVideoCountCommand;

class GetVideoCount
{
    /**
     * Brightcove GetVideoCount instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new GetVideoCount instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Fetch a videos list count from Brightcove
     * @param object $setting, string $queryParam
     * @return array
     */
    public function fetch($setting, $queryParam = '')
    {
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if (isset($getToken['Authorization'])) {
            $response = $this->bcAPIClient->run(new GetVideoCountCommand($setting, $getToken, $queryParam));
            return $response;
        }
    }
}
