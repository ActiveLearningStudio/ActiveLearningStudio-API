<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle get playlist videos count in Brightcove
 * ClassName    GetPlaylistVideosCountCommand
*/
namespace App\CurrikiGo\Brightcove\Commands\Playlists;

use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetPlaylistVideosCountCommand implements Command
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
     * Brightcove API play list id
     * @var string
     */
    private $playlistId;
    /**
     * Brightcove API query param for search
     * @var string
     */
    private $queryParam;

    /**
     * Creates an instance of the GetPlaylistVideosCountCommand class
     * @param object $setting, array $getToken, string $queryParam
     * @return void
     */
    public function __construct($setting, $getToken, $playlistId, $queryParam)
    {
        $this->setting = $setting;
        $this->getToken = $getToken;
        $this->playlistId = $playlistId;
        $this->queryParam = $queryParam;
    }

    /**
     * Execute an API request to return Brightcove playlists count
     * @return json object
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $this->setting->account_id . '/counts/playlists/' . $this->playlistId . '/videos' . $this->queryParam;
        $response = Http::withHeaders($this->getToken)->get($apiUrl);
        if ($response->status() == 200) {
            return $response->json();
        }
    }
}
