<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle get my video list in Komodo
 * ClassName    GetMyVideoListCommand
*/
namespace App\CurrikiGo\Komodo\Commands\Videos;

use App\CurrikiGo\Komodo\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetMyVideoListCommand implements Command
{

    /**
     * Komodo API Setting
     * @var object
     */
    private $setting;    
    /**
     * Komodo API query param
     * @var array
     */
    private $getParam;

    /**
     * Creates an instance of the GetMyVideoListCommand class
     * @param object $setting, array $getParam
     */
    public function __construct($setting, $getParam)
    {
        $this->setting = $setting;
        $this->getParam = $getParam;
    }

    /**
     * Execute an API request to return my Komodo videos list
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('komodo.api_base_url') . '/recordings?email='.$this->setting->tool_consumer_key.'&' . http_build_query($this->getParam);
        $authHeaders = array('Authorization' => ' Bearer ' . $this->setting->tool_secret_key);
        $response = Http::withHeaders($authHeaders)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
