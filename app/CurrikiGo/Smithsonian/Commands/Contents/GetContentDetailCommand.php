<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian Content Detail
 * ClassName    GetContentDetailCommand
*/
namespace App\CurrikiGo\Smithsonian\Commands\Contents;

use App\CurrikiGo\Smithsonian\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetContentDetailCommand implements Command
{
    /**
     * Smithsonian Institution Open Access API query param
     * @var array
     */
    private $getParam;

    /**
     * GetContentDetailCommand constructor.
     * @param array $getParam
     * @return array
     */
    public function __construct($getParam)
    {
        $this->getParam = $getParam;
    }

    /**
     * Execute an API request to return Smithsonian content detail
     * @return json object $response
     */
    public function execute()
    {
        $apiUrl = config('smithsonian.api_base_url') . '/content/'.$this->getParam['id'].'?api_key='.config('smithsonian.api_key');
        $response = Http::get($apiUrl);
        return $response->json();
    }
}
