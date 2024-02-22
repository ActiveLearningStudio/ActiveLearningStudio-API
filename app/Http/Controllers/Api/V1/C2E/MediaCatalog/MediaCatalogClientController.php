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
     * @param mediaSourcesInterface $mediaSourcesRepository
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
     * 
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcoveVideos(BrightcoveAPIRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);
        
        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);
      
        if ( $setting ) {
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
     * 
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcoveVideoByIds(BrightcoveAPIVideoByIdsRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);
        
        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);
      
        if ( $setting ) {
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
     * 
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcovePlaylists(BrightcoveAPIRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);
        
        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);

        if ( $setting ) {
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
     * 
     * @return json
     * 
     * @throws GeneralException
     */
    public function getBrightcovePlaylistVideos(BrightcoveAPIPlaylistVideosRequest $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);

        $validated = $request->validated();
        $setting = $this->getAPISettings('brightcove', $suborganization->id);

        if ( $setting ) {
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
