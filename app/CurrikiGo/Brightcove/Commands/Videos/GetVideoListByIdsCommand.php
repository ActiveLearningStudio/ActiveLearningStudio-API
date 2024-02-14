<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle get video list by ids in Brightcove
 * ClassName    GetVideoListByIdsCommand
*/
namespace App\CurrikiGo\Brightcove\Commands\Videos;

use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetVideoListByIdsCommand implements Command
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
     * Brightcove API video ids
     * @var string
     */
    private $videoIds;

    /**
     * Creates an instance of the GetVideoListByIdsCommand class
     * @param object $setting, array $getToken, string $videoIds
     * @return void
     */
    public function __construct($setting, $getToken, $videoIds)
    {
        $this->setting = $setting;
        $this->getToken = $getToken;
        $this->videoIds = $videoIds;
    }

    /**
     * Execute an API request to return Brightcove video list by ids
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $this->setting->account_id . '/videos/' . $this->videoIds;
        $response = Http::withHeaders($this->getToken)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
