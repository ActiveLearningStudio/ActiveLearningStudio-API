<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle get my video list in Vimeo
 * ClassName    GetMyVideoListCommand
*/
namespace App\CurrikiGo\Vimeo\Commands\Videos;

use App\CurrikiGo\Vimeo\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetMyVideoListCommand implements Command
{

    /**
     * Vimeo API Setting
     * @var object
     */
    private $setting;    
    /**
     * Vimeo API query param
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
     * Execute an API request to return my Vimeo videos list
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('vimeo.base_url') . '/me/videos?' . http_build_query($this->getParam);
        $authHeaders = array('Authorization' => ' Bearer ' . $this->setting->tool_secret_key);
        $response = Http::withHeaders($authHeaders)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
