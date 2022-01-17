<?php
/**
 * @Author      Asim Sarwar
 * Date         13-01-2022
 * Description  Handle Brightcove Update Video Tags API end points
 * ClassName    UpdateVideoTags
*/
namespace App\CurrikiGo\Brightcove\Videos;

use App\CurrikiGo\Brightcove\Commands\GetAPITokenCommand;
use App\CurrikiGo\Brightcove\Commands\Videos\UpdateVideoTagsCommand;

class UpdateVideoTags
{
    /**
     * Brightcove UpdateVideoTags instance
     * @var \App\CurrikiGo\Brightcove\Client
    */
    private $bcAPIClient;
    
    /**
     * Create a new UpdateVideoTags instance.
     * @param object $client
     */
    public function __construct($client)
    {
        $this->bcAPIClient = $client;
    }

    /**
     * Update Brightcove Video
     * @param object $setting, string $bcVideoId, $tagsName, boolean $removeTags
     * @return integer
     */
    public function fetch($setting, $bcVideoId, $tagsName, $removeTags)
    { 
        $getToken = $this->bcAPIClient->run(new GetAPITokenCommand($setting));
        if ( isset($getToken['Authorization']) ) {            
            $response = $this->bcAPIClient->run(new UpdateVideoTagsCommand($setting, $getToken, $bcVideoId, $tagsName, $removeTags));
            return $response;
        }
    }
}
