<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle get playlist count in Brightcove
 * ClassName    GetPlaylistCountCommand
*/
namespace App\CurrikiGo\Brightcove\Commands\Playlists;

use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetPlaylistCountCommand implements Command
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
     * Creates an instance of the GetPlaylistCountCommand class
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
     * Execute an API request to return Brightcove playlists count
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $this->setting->account_id . '/counts/playlists' . $this->queryParam;
        $response = Http::withHeaders($this->getToken)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
