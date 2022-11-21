<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Komodo My Video API end points
 * ClassName    GetMyVideoList
*/
namespace App\CurrikiGo\Komodo\Videos;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Komodo\Commands\Videos\GetMyVideoListCommand;

class GetMyVideoList
{
    /**
     * Komodo GetMyVideoList instance
     * @var \App\CurrikiGo\Komodo\Client
    */
    private $komodoClient;
    
    /**
     * Create a new komodoClient instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->komodoClient = $client;
    }

    /**
     * Fetch a my videos list from Komodo
     * @param object $setting, array $getParam
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $getParam)
    {
        $response = $this->komodoClient->run(new GetMyVideoListCommand($setting, $getParam));
        if ( $response) {
            return $response;
        } else {
            throw new GeneralException('No Record Found!');
        }
    }

}
