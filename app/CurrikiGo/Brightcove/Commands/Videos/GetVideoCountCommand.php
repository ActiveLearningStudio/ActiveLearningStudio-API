<?php
/**
 * @Author      Asim Sarwar
 * Date         17-12-2021
 * Description  Handle get video list count in Brightcove
 * ClassName    GetVideoCountCommand
*/
namespace App\CurrikiGo\Brightcove\Commands\Videos;

use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetVideoCountCommand implements Command
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
     * Creates an instance of the GetVideoCountCommand class
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
     * Execute an API request to return Brightcove videos list count
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $this->setting->account_id . '/counts/videos' . $this->queryParam;
        $response = Http::withHeaders($this->getToken)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
