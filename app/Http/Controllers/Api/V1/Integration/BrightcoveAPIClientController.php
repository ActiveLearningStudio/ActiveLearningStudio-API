<?php
/**
 * @Author      Asim Sarwar
 * Date         10-12-2021
 * Description  Handle Brightcove API Client
 * ClassName    BrightcoveAPIClientController
 */
namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Integration\BrightcoveAPISettingRepository;
use App\Http\Resources\V1\Integration\BrightcoveAPISettingCollection;
use App\Http\Resources\V1\Integration\BrightcoveAPISettingResource;
use App\CurrikiGo\Brightcove\Client;
use App\CurrikiGo\Brightcove\Videos\GetVideoList;
use App\CurrikiGo\Brightcove\Playlists\GetPlaylist;
use App\CurrikiGo\Brightcove\Playlists\GetPlaylistVideos;
use App\CurrikiGo\Brightcove\Videos\GetVideoListByIds;
use App\Exceptions\GeneralException;
use App\Http\Requests\V1\Integration\BrightcoveAPI\BrightcoveAPIRequest;
use App\Http\Requests\V1\Integration\BrightcoveAPI\BrightcoveAPIVideoByIdsRequest;
use App\Http\Requests\V1\Integration\BrightcoveAPI\BrightcoveAPIPlaylistVideosRequest;

class BrightcoveAPIClientController extends Controller
{
    private $bcAPISettingRepository;
    /**
     * BrightcoveAPIClientController constructor.
     * @param BrightcoveAPISettingRepository $brightcoveAPISettingRepository
     */
    public function __construct(BrightcoveAPISettingRepository $brightcoveAPISettingRepository)
    {
        $this->bcAPISettingRepository = $brightcoveAPISettingRepository;
    }
    
    /**
     * All Brightcove Account List.
     * 
     * Get All Brightcove Account List.
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * 
     * @param integer $suborganization
     * 
     * @return BrightcoveAPISettingCollection
     */
    public function getAccountList($suborganization)
    {
        $collections = $this->bcAPISettingRepository->getAccountListByOrg($suborganization);
        return new BrightcoveAPISettingCollection($collections);    
    }

    /**
     * Get Brightcove Videos List
     * 
     * Get the specified Brightcove API setting data.
     *  
     * @bodyParam id integer required Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id integer required Valid id of existing user organization Example: 1
     * @bodyParam query_param string optional Valid brightcove query param Example: query=name=file&limit=0&offset=0
     * 
     * @param BrightcoveAPIRequest $request
     * 
     * @return BrightcoveAPISettingResource
     * 
     * @throws GeneralException
     */
    public function getVideosList(BrightcoveAPIRequest $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'query_param'
      ]);
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);
      if ( $setting ) {
        $queryParam = isset($data['query_param']) ? '?' . $data['query_param'] : '';

        // Implement Command Design Pattern to access Brightcove API
        $bcAPIClient = new Client($setting);
        $bcInstance = new GetVideoList($bcAPIClient);
        $bcApiResponse = $bcInstance->fetch($setting, $queryParam);
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
     * @bodyParam id integer required Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id integer required Valid id of existing user organization Example: 1
     * @bodyParam video_ids string comma seperated ids required Valid brightcove video ids Example: 6346785961112,6343680181112
     * 
     * @param BrightcoveAPIVideoByIdsRequest $request
     * 
     * @return BrightcoveAPISettingResource
     * 
     * @throws GeneralException
     */
    public function getVideoListByIds(BrightcoveAPIVideoByIdsRequest $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'video_ids'
      ]);
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);
      if ( $setting ) {
        // Implement Command Design Pattern to access Brightcove API
        $bcAPIClient = new Client($setting);
        $bcInstance = new GetVideoListByIds($bcAPIClient);
        $bcApiResponse = $bcInstance->fetch($setting, $data['video_ids']);
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
     * @bodyParam id integer required Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id integer required Valid id of existing user organization Example: 1
     * @bodyParam query_param string optional Valid brightcove query param Example: query=q=name:test&limit=0&offset=0
     * 
     * @param BrightcoveAPIRequest $request
     * 
     * @return BrightcoveAPISettingResource
     * 
     * @throws GeneralException
     */
    public function getPlaylists(BrightcoveAPIRequest $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'query_param'
      ]);
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);
      if ( $setting ) {
        $queryParam = isset($data['query_param']) ? '?' . $data['query_param'] : '';
        
        // Implement Command Design Pattern to access Brightcove API
        $bcAPIClient = new Client($setting);
        $bcInstance = new GetPlaylist($bcAPIClient);
        $bcApiResponse = $bcInstance->fetch($setting, $queryParam);
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
     * @bodyParam id integer required Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id integer required Valid id of existing user organization Example: 1
     * @bodyParam palylist_id string required Valid play list id Example: 1790777921813579867
     * @bodyParam query_param string optional Valid brightcove query param Example: query=limit=0&offset=0
     * 
     * @param BrightcoveAPIPlaylistVideosRequest $request
     * 
     * @return BrightcoveAPISettingResource
     * 
     * @throws GeneralException
     */
    public function getPlaylistVideos(BrightcoveAPIPlaylistVideosRequest $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'palylist_id',
        'query_param'
      ]);
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);
      if ( $setting ) {
        $playlistId = $data['palylist_id'];
        $queryParam = isset($data['query_param']) ? '?' . $data['query_param'] : '';

        // Implement Command Design Pattern to access Brightcove API
        $bcAPIClient = new Client($setting);
        $bcInstance = new GetPlaylistVideos($bcAPIClient);
        $bcApiResponse = $bcInstance->fetch($setting, $playlistId, $queryParam);
        return $bcApiResponse;
      } else {
        throw new GeneralException('Unable to find Brightcove API settings!');
      }
    }
}
