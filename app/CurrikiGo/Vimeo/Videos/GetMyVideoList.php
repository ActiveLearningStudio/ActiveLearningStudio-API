<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Vimeo My Video API end points
 * ClassName    GetMyVideoList
*/
namespace App\CurrikiGo\Vimeo\Videos;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Vimeo\Commands\Videos\GetMyVideoListCommand;

class GetMyVideoList
{
    /**
     * Vimeo GetMyVideoList instance
     * @var \App\CurrikiGo\Vimeo\Client
    */
    private $vimeoClient;
    
    /**
     * Create a new vimeoClient instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->vimeoClient = $client;
    }

    /**
     * Fetch a my videos list from Vimeo
     * @param object $setting, array $getParam
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $getParam)
    {
        $response = $this->vimeoClient->run(new GetMyVideoListCommand($setting, $getParam));
        if ( $response) {
            return $response;
        } else {
            throw new GeneralException('No Record Found!');
        }
    }

}
