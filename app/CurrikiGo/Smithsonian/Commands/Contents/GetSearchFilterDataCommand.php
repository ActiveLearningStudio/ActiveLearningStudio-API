<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian get search filter data API end point
 * ClassName    GetSearchFilterDataCommand
*/
namespace App\CurrikiGo\Smithsonian\Commands\Contents;

use App\CurrikiGo\Smithsonian\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetSearchFilterDataCommand implements Command
{

    /**
     * Smithsonian API query param
     * @var array
     */
    private $getParam;

    /**
     * GetSearchFilterDataCommand constructor.
     * @param array $getParam
     * @return array
     */
    public function __construct($getParam)
    {
        $this->getParam = $getParam;
    }

    /**
     * Execute an API request to return Smithsonian search filter data
     * @return json object $response
     */
    public function execute()
    {
        $category = $this->getParam['category'];
        unset($this->getParam['category']);
        $apiUrl = config('smithsonian.api_base_url') . 
                  '/terms/' . $category . '?q=online_visual_material:true&api_key=' . 
                   config('smithsonian.api_key' ) . '&' . 
                   http_build_query($this->getParam);
        $response = Http::get($apiUrl);
        return $response->json();
    }
}
