<?php
/**
 * @Author      Asim Sarwar
 * Date         13-01-2022
 * Description  Handle Brightcove Update Video Tags API end points
 * ClassName    UpdateVideoTagsCommand
*/
namespace App\CurrikiGo\Brightcove\Commands\Videos;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class UpdateVideoTagsCommand implements Command
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
     * Brightcove Video Id
     * @var string
     */
    private $bcVideoId;
    /**
     * Brightcove Video Tags Name
     * @var string
     */
    private $tagsName;
    /**
     * Remove Brightcove Video Tags
     * @var string
     */
    private $removeTags;

    /**
     * Creates an instance of the UpdateVideoTagsCommand class
     * @param object $setting, array $getToken, string $bcVideoId, string $tagsName, boolean $removeTags
     * @return void
     */
    public function __construct($setting, $getToken, $bcVideoId, $tagsName, $removeTags)
    {
        $this->setting = $setting;
        $this->getToken = $getToken;
        $this->bcVideoId = $bcVideoId;
        $this->tagsName = $tagsName;
        $this->removeTags = $removeTags;
    }

    /**
     * Execute an API request to return Brightcove Update Video Tags Response
     * @return integer
     * @throws GeneralException
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $this->setting->account_id . '/videos/' . $this->bcVideoId;
        // Get the brightcove video detail by id
        $getVideoByIdResponse = Http::withHeaders($this->getToken)->get($apiUrl);
        if ($getVideoByIdResponse->status() == 200) {
            $videoTagsResult = $getVideoByIdResponse->json()['tags'];
            if ($this->removeTags && ($key = array_search($this->tagsName, $videoTagsResult)) !== false) {
                unset($videoTagsResult[$key]);
                $videoTagsResult = array_values($videoTagsResult);
            } elseif (!$this->removeTags && !in_array($this->tagsName, $videoTagsResult)) {
                array_push($videoTagsResult, $this->tagsName);
            }
            // Update Brightcove Video Tags
            $response = Http::withHeaders($this->getToken)->patch($apiUrl, [
                            'tags' => $videoTagsResult
                        ]);
            if ($response->status() !== 200) {
                throw new GeneralException('Something went wrong with API!');
            }
            return $response->status();
        } else {
            throw new GeneralException('No Video Record Found!');
        }
    }
}
