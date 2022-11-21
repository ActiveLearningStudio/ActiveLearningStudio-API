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
use App\CurrikiGo\Brightcove\Videos\GetVideoCount;
use App\CurrikiGo\Brightcove\Videos\UpdateVideoTags;
use App\Exceptions\GeneralException;

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
     * @param Request $request
     * 
     * @return BrightcoveAPISettingResource
     * 
     * @throws GeneralException
     */
    public function getVideosList(Request $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'query_param'
      ]);
      if ( isset($data['organization_id']) && $data['organization_id'] > 0 && isset($data['id']) && $data['id'] > 0 ) {
        $queryParam = isset($data['query_param']) ? '?' . $data['query_param'] : '';
        $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);

        // Implement Command Design Pattern to access Brightcove API
        $bcAPIClient = new Client($setting);
        $bcInstance = new GetVideoList($bcAPIClient);
        $bcApiResponse = $bcInstance->fetch($setting, $queryParam);
        return $bcApiResponse;  
      }
      throw new GeneralException('Unable to get the record. Please check your payload data. id and organization_id is require field!');      
    }
}
