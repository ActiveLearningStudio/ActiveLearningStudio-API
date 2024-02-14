<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Brightcove Playlist Videos API end points
 * ClassName    GetPlaylistVideos
*/
namespace App\CurrikiGo\Brightcove\Playlists;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Playlists\GetPlaylistVideosCountCommand;
use App\CurrikiGo\Brightcove\Commands\Playlists\GetPlaylistVideosCommand;

class GetPlaylistVideos
{
    /**
     * Brightcove GetPlaylistVideos instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new GetPlaylistVideos instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Fetch a Playlists list from Brightcove
     * @param object $setting, string $queryParam
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $playlistId, $queryParam = '')
    {
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if ( isset($getToken['Authorization']) ) {
            $getCountResponse = $this->bcAPIClient->run(new GetPlaylistVideosCountCommand($setting, $getToken, $playlistId, $queryParam));
            $response = $this->bcAPIClient->run(new GetPlaylistVideosCommand($setting, $getToken, $playlistId, $queryParam));
            if ( $getCountResponse && $response ) {                
                return response()->json(['data' => $response, 'meta' => $getCountResponse]);
            } else {
                throw new GeneralException('No Record Found!');
            }            
        }
    }

}
