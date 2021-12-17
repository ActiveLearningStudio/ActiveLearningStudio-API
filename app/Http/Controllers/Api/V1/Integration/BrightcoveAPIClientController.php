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
use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Client;
use App\CurrikiGo\Brightcove\Videos\GetVideoList;
use App\CurrikiGo\Brightcove\Videos\GetVideoCount;

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
     * Get All Brightcove Account List.
     * @param integer $suborganization
     * @return BrightcoveAPISettingCollection
     */
    public function getAccountList($suborganization)
    {
      $collections = $this->bcAPISettingRepository->getAccountListByOrg($suborganization);
      return new BrightcoveAPISettingCollection($collections);    
    }

    /**
     * Get Brightcove Videos Count
     * Get the specified Brightcove API setting data.
     * @param Request $request
     * @bodyParam id require Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id require Valid id of existing user organization  Example: 1
     * @bodyParam query_param optional Valid brightcove query param Example: query=name=file
     * @return BrightcoveAPISettingResource
     * @throws GeneralException
     */
    public function getVideosCount(Request $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'query_param'
      ]);
      $queryParam = isset($data['query_param']) ? '?' . $data['query_param'] : '';
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);

      // Implement Command Design Pattern to access Brightcove API
      $bcAPIClient = new Client($setting);
      $bcInstance = new GetVideoCount($bcAPIClient);
      return $this->connectWithBrightcoveAPI($bcInstance, $setting, $queryParam);
    }

    /**
     * Get Brightcove Videos List
     * Get the specified Brightcove API setting data.
     * @param Request $request
     * @bodyParam id require Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id require Valid id of existing user organization  Example: 1
     * @bodyParam query_param optional Valid brightcove query param Example: query=name=file&limit=0&offset=0
     * @return BrightcoveAPISettingResource     
     * @throws GeneralException
     */
    public function getVideosList(Request $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'query_param'
      ]);
      $queryParam = isset($data['query_param']) ? '?' . $data['query_param'] : '';
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);

      // Implement Command Design Pattern to access Brightcove API
      $bcAPIClient = new Client($setting);
      $bcInstance = new GetVideoList($bcAPIClient);
      return $this->connectWithBrightcoveAPI($bcInstance, $setting, $queryParam);
    }

    /**
     * Implement Command Design Pattern to access Brightcove API.
     * @param  object $bcInstance, object $setting, string $queryParam
     * @return BrightcoveAPISettingResource
     * @throws GeneralException
     */
    private function connectWithBrightcoveAPI($bcInstance, $setting, $queryParam)
    {
      $bcApiResponse = $bcInstance->fetch($setting, $queryParam);
      $countResult = count((array)$bcApiResponse);
      if ($countResult >= 1) {
        return new BrightcoveAPISettingResource($bcApiResponse);
      } elseif ($countResult == 0) {
        throw new GeneralException('No Record Found!');  
      }      
    }
}
