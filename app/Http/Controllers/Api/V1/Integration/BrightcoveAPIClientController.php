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
use Illuminate\Support\Facades\Http;
use App\Exceptions\GeneralException;

class BrightcoveAPIClientController extends Controller
{
    private $bcAPISettingRepository;  
    /**
     * BrightcoveAPIClientController constructor.
     *
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
     * Get Brightcove Videos List
     * Get the specified Brightcove API setting data.
     * @param Request $request
     * @bodyParam id require Valid id of a brightcove api settings table Example: 1
     * @bodyParam organization_id require Valid id of existing user organization  Example: 1
     * @bodyParam search_param optional Valid brightcove video id or name Example: 6283186896001 or bunny
     * @return BrightcoveAPISettingResource     
     * @throws GeneralException
     */
    public function getVideosList(Request $request)
    {
      $data = $request->only([
        'id',
        'organization_id',
        'search_param'
      ]);
      $queryParam = isset($data['search_param']) ? '?query=name=' . $data['search_param'] : '';
      $setting = $this->bcAPISettingRepository->getRowRecordByOrgId($data['organization_id'], $data['id']);
      $getToken = $this->getAPIToken($setting);
      if (isset($getToken['Authorization'])) {
        $getVideoListUrl = config('brightcove-api.base_url') . 'v1/accounts/' . $setting->account_id . '/videos' . $queryParam;
        $response = Http::withHeaders($getToken)
                    ->get($getVideoListUrl);
        return new BrightcoveAPISettingResource($response->json());  
      }
      throw new GeneralException('Brightcove api token not found.Please try later!');      
    }

    /**
     * Get Brightcove API Token
     * @param object $setting
     * @return array
    */
    private function getAPIToken($setting)
    {
      $requestParam = '?grant_type=client_credentials&client_id=' . $setting->client_id . '&client_secret=' . $setting->client_secret . '';
      $response = Http::post(config('brightcove-api.token_url') . $requestParam);
      if ($response->status() == 200) {
        $result = $response->json();
        return array('Authorization' => ' Bearer ' . $result['access_token'], 'Content-Type: ' => 'application/json');
      }
    }
    
}
