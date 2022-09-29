<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian Content List
 * ClassName    GetContentListCommand
*/
namespace App\CurrikiGo\Smithsonian\Commands\Contents;

use App\CurrikiGo\Smithsonian\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetContentListCommand implements Command
{

    /**
     * Smithsonian Institution Open Access API query param
     * @var array
     */
    private $getParam;

    /**
     * GetContentListCommand constructor.
     * @param array $getParam
     * @return array
     */
    public function __construct($getParam)
    {
        $this->getParam = $getParam;
    }

    /**
     * Execute an API request to return Smithsonian content list
     * @return json object $response
     */
    public function execute()
    {
        $apiUrl = config('smithsonian.api_base_url') . '/search?api_key='.config('smithsonian.api_key').'&' . http_build_query($this->getParam);
        $response = Http::get($apiUrl);
        return $response->json();
    }
}
