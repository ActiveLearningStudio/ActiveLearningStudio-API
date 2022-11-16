<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian Institution Open Access get content details API end point
 * ClassName    GetContentDetail
*/
namespace App\CurrikiGo\Smithsonian\Contents;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Smithsonian\Commands\Contents\GetContentDetailCommand;

class GetContentDetail
{
    /**
     * Smithsonian content detail instance
     * @var \App\CurrikiGo\Smithsonian\Client
    */
    private $smithsonianClient;
    
    /**
     * GetContentDetail constructor.
     * Create a new smithsonianClient instance.
     * @param object $client
     * @return object
     */
    public function __construct($client)
    {
        $this->smithsonianClient = $client;
    }

    /**
     * Fetch Smithsonian content detail
     * @param array $getParam
     * @return json object $response
     * @throws GeneralException
     */
    public function fetch($getParam)
    {
        $response = $this->smithsonianClient->run(new GetContentDetailCommand($getParam));
        if ( $response ) {
            return $response;
        } else {
            throw new GeneralException('No Record Found!');
        }
    }

}
