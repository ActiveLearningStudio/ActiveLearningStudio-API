<?php
/**
 * @Author      Asim Sarwar
 * Date         16-12-2021
 * Description  Handle get video list in Brightcove
 * ClassName    GetVideoListCommand
*/
namespace App\CurrikiGo\Brightcove\Commands\Videos;

use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetVideoListCommand implements Command
{

    /**
     * Brightcove API Setting
     * @var object
     */
    private $setting;
    /**
     * Brightcove API Token
     * @var array
     */
    private $getToken;
    /**
     * Brightcove API query param for search
     * @var string
     */
    private $queryParam;

    /**
     * Creates an instance of the GetVideoListCommand class
     * @param object $setting, array $getToken, string $queryParam
     * @return void
     */
    public function __construct($setting, $getToken, $queryParam)
    {
        $this->setting = $setting;
        $this->getToken = $getToken;
        $this->queryParam = $queryParam;
    }

    /**
     * Execute an API request to return Brightcove videos list
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $this->setting->account_id . '/videos' . $this->queryParam;
        $response = Http::withHeaders($this->getToken)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
