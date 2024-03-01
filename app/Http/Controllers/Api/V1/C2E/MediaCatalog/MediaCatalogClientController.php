<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Media Catalog Client
 * ClassName    MediaCatalogClientController
 */
namespace App\Http\Controllers\Api\V1\C2E\MediaCatalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\C2E\MediaCatalog\MediaCatalogAPISettingInterface;
use App\Repositories\MediaSources\MediaSourcesInterface;
use App\CurrikiGo\Brightcove\Client;
use App\CurrikiGo\Brightcove\Videos\GetVideoList;
use App\CurrikiGo\Brightcove\Playlists\GetPlaylist;
use App\CurrikiGo\Brightcove\Playlists\GetPlaylistVideos;
use App\CurrikiGo\Brightcove\Videos\GetVideoListByIds;
use App\CurrikiGo\Brightcove\Videos\GetSrtVideoById;
use App\Exceptions\GeneralException;
use App\Http\Requests\V1\C2E\MediaCatalog\BrightcoveAPI\BrightcoveAPIRequest;
use App\Http\Requests\V1\C2E\MediaCatalog\BrightcoveAPI\BrightcoveAPIVideoByIdsRequest;
use App\Http\Requests\V1\C2E\MediaCatalog\BrightcoveAPI\BrightcoveAPIPlaylistVideosRequest;
use App\Models\Organization;
use App\Models\C2E\MediaCatalog\MediaCatalogAPISetting;

class MediaCatalogClientController extends Controller
{
    private $mediaCatalogAPISettingRepository;
    private $mediaSourcesRepository;
    private $type;
    /**
     * MediaCatalogClientController constructor.
     * @param MediaCatalogAPISettingInterface $mediaCatalogAPISettingRepository
     * @param MediaSourcesInterface $mediaSourcesRepository
     */
    public function __construct(MediaCatalogAPISettingInterface $mediaCatalogAPISettingRepository, MediaSourcesInterface $mediaSourcesRepository)
    {
        $this->mediaCatalogAPISettingRepository = $mediaCatalogAPISettingRepository;
        $this->mediaSourcesRepository = $mediaSourcesRepository;
        $this->type = 'media_catalog';
    }

    /**
     * Get Brightcove Videos List
     * 
     * Get the specified Brightcove API setting data.
     *  
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam query_param string optional Valid brightcove query param Example: query=name=file&limit=0&offset=0
     * 
     * @param BrightcoveAPIRequest $request
     * @param Organization $suborganization
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcoveVideos(BrightcoveAPIRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);
        
        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);
        $srtSearch = $request->input('srt_search');

        if ($setting) {

            if (isset($srtSearch) && $srtSearch != '') {                
                $matchingSubtitles = [];
                $matchingBcVideosList = [];
                $getSearchRecord = $this->mediaCatalogAPISettingRepository->getMediaCatalogSrtSearchRecord($setting->id, $srtSearch);
                if ($getSearchRecord->count() > 0) {
                    foreach ($getSearchRecord as $result) {
                        $content = $result->content;
                        // Extract start and end times when text matches
                        preg_match_all('/(\d{2}:\d{2}:\d{2},\d{3}) --> (\d{2}:\d{2}:\d{2},\d{3})\n' . $srtSearch . '/s', $content, $matches, PREG_SET_ORDER);    
                        foreach ($matches as $match) {
                            $matchingSubtitles[$result->video_id][] = [
                                'vido_id' => $result->video_id,
                                'start_time' => formatVideoSrtContentTime($match[1]),
                                'end_time' => formatVideoSrtContentTime($match[2]),
                                'text' => $srtSearch,
                            ];
                        }
                        if (array_key_exists($result->video_id, $matchingSubtitles)) {
                            $srtContent = '';                        
                            foreach($matchingSubtitles[$result->video_id] as $res) {
                                $srtContent .= $res['text'] . ' - ' . $res['start_time'] . ",";
                            }
                            // Implement Command Design Pattern to access Brightcove API
                            $bcAPIClient = new Client($setting, $this->type);
                            $bcInstance = new GetSrtVideoById($bcAPIClient);
                            $matchingBcVideosList['data'][] = $bcInstance->fetch($setting, $result->video_id, $this->type, $srtContent);
                        }                    
                    }
                    return response()->json($matchingBcVideosList);
                } else {
                    throw new GeneralException('No Record Found!');
                }
            }

            $queryParam = isset($validated['query_param']) ? '?' . $validated['query_param'] : '';

            // Implement Command Design Pattern to access Brightcove API
            $bcAPIClient = new Client($setting, $this->type);
            $bcInstance = new GetVideoList($bcAPIClient);
            $bcApiResponse = $bcInstance->fetch($setting, $queryParam, $this->type);
            return $bcApiResponse;
        } else {
            throw new GeneralException('Unable to find Brightcove API settings!');
        }
    }

    /**
     * Get Brightcove Video By Ids
     * 
     * Get the specified Brightcove API setting data.
     *  
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam video_ids string comma seperated ids required Valid brightcove video ids Example: 6346785961112,6343680181112
     * 
     * @param BrightcoveAPIVideoByIdsRequest $request
     * @param Organization $suborganization
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcoveVideoByIds(BrightcoveAPIVideoByIdsRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);
        
        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);
      
        if ($setting) {
            // Implement Command Design Pattern to access Brightcove API
            $bcAPIClient = new Client($setting, $this->type);
            $bcInstance = new GetVideoListByIds($bcAPIClient);
            $bcApiResponse = $bcInstance->fetch($setting, $validated['video_ids'], $this->type);
            return $bcApiResponse;
        } else {
            throw new GeneralException('Unable to find Brightcove API settings!');
        }
    }

    /**
     * Get Brightcove Playlist
     * 
     * Get the specified Brightcove API setting data.
     *  
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam query_param string optional Valid brightcove query param Example: query=q=name:test&limit=0&offset=0
     * 
     * @param BrightcoveAPIRequest $request
     * @param Organization $suborganization
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcovePlaylists(BrightcoveAPIRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);
        
        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);

        if ($setting) {
            $queryParam = isset($validated['query_param']) ? '?' . $validated['query_param'] : '';
        
            // Implement Command Design Pattern to access Brightcove API
            $bcAPIClient = new Client($setting, $this->type);
            $bcInstance = new GetPlaylist($bcAPIClient);
            $bcApiResponse = $bcInstance->fetch($setting, $queryParam, $this->type);
            return $bcApiResponse;  
        } else {
            throw new GeneralException('Unable to find Brightcove API settings!');
        }
    }

    /**
     * Get Brightcove Playlist Videos
     * 
     * Get the specified Brightcove API setting data.
     *  
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam palylist_id string required Valid play list id Example: 1790777921813579867
     * @bodyParam query_param string optional Valid brightcove query param Example: query=limit=0&offset=0
     * 
     * @param BrightcoveAPIPlaylistVideosRequest $request
     * @param Organization $suborganization
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcovePlaylistVideos(BrightcoveAPIPlaylistVideosRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);

        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);

        if ($setting) {
            $playlistId = $validated['palylist_id'];
            $queryParam = isset($validated['query_param']) ? '?' . $validated['query_param'] : '';

            // Implement Command Design Pattern to access Brightcove API
            $bcAPIClient = new Client($setting, $this->type);
            $bcInstance = new GetPlaylistVideos($bcAPIClient);
            $bcApiResponse = $bcInstance->fetch($setting, $playlistId, $queryParam, $this->type);
            return $bcApiResponse;
        } else {
            throw new GeneralException('Unable to find Brightcove API settings!');
        }
    }

    /**
     * Get the specified media catalog api settings
     *  
     * @param string $sourceType, int $orgId
     * 
     * @return object $setting
     */
    private function getAPISettings($sourceType, $orgId) {
        $videoMediaSources = getVideoMediaSources();
        $mediaSourcesId = $this->mediaSourcesRepository->getMediaSourceIdByName($videoMediaSources[$sourceType]);                
        return $this->mediaCatalogAPISettingRepository->getRowRecordByOrgAndSourceType($orgId, $mediaSourcesId);
    }
}
