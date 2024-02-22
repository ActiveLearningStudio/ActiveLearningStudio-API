<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Brightcove Playlists API end points
 * ClassName    GetPlaylist
*/
namespace App\CurrikiGo\Brightcove\Playlists;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Playlists\GetPlaylistCountCommand;
use App\CurrikiGo\Brightcove\Commands\Playlists\GetPlaylistCommand;

class GetPlaylist
{
    /**
     * Brightcove GetPlaylist instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new GetPlaylist instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Fetch a Playlists list from Brightcove
     * @param object $setting, string $queryParam, string $type
     * @return array
     * @throws GeneralException
     */
    public function fetch($setting, $queryParam = '', $type = '')
    {
        $setting->account_id = ( $type === 'media_catalog' ) ? $setting->api_setting_id : $setting->account_id;
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if ( isset($getToken['Authorization']) ) {
            $getCountResponse = $this->bcAPIClient->run(new GetPlaylistCountCommand($setting, $getToken, $queryParam));
            $response = $this->bcAPIClient->run(new GetPlaylistCommand($setting, $getToken, $queryParam));
            if ( $getCountResponse && $response) {                
                return response()->json(['data' => $response, 'meta' => $getCountResponse]);
            } else {
                throw new GeneralException('No Record Found!');
            }            
        }
    }

}
