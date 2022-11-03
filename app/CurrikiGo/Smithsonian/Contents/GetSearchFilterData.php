<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian get search filter data API end point
 * ClassName    GetSearchFilterData
*/
namespace App\CurrikiGo\Smithsonian\Contents;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Smithsonian\Commands\Contents\GetSearchFilterDataCommand;

class GetSearchFilterData
{
    /**
     * Smithsonian client param
     * @var \App\CurrikiGo\Smithsonian\Client
    */
    private $smithsonianClient;
    
    /**
     * GetSearchFilterData constructor.   
     * Create a new smithsonianClient instance.
     * @param object $client
     * @return object
     */
    public function __construct($client)
    {
        $this->smithsonianClient = $client;
    }

    /**
     * Fetch Smithsonian get search filter api data
     * @param array $getParam
     * @return json object $response
     * @throws GeneralException
     */
    public function fetch($getParam)
    {
        $response = $this->smithsonianClient->run(new GetSearchFilterDataCommand($getParam));
        if ( $response ) {
            return $response;
        } else {
            throw new GeneralException('No Record Found!');
        }
    }

}
