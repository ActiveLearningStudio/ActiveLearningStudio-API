<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian Institution Open Access Get Content List API end point
 * ClassName    GetContentList
*/
namespace App\CurrikiGo\Smithsonian\Contents;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Smithsonian\Commands\Contents\GetContentListCommand;

class GetContentList
{
    /**
     * Smithsonian content list instance
     * @var \App\CurrikiGo\Smithsonian\Client
    */
    private $smithsonianClient;
    
    /**
     * GetContentList constructor.   
     * Create a new smithsonianClient instance.
     * @param object $client
     * @return object
     */
    public function __construct($client)
    {
        $this->smithsonianClient = $client;
    }

    /**
     * Fetch Smithsonian contents list
     * @param array $getParam
     * @return json object $response
     * @throws GeneralException
     */
    public function fetch($getParam)
    {
        $response = $this->smithsonianClient->run(new GetContentListCommand($getParam));
        if ( $response ) {
            return $response;
        } else {
            throw new GeneralException('No Record Found!');
        }
    }

}
