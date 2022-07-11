<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Vimeo API Client
 * ClassName    VimeoAPIClientController
 */
namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LtiTool\LtiToolSettingRepository;
use App\CurrikiGo\Vimeo\Client;
use App\CurrikiGo\Vimeo\Videos\GetMyVideoList;
use App\Exceptions\GeneralException;

class VimeoAPIClientController extends Controller
{
    private $ltiToolSettingRepository;
    /**
     * VimeoAPIClientController constructor.
     * @param LtiToolSettingRepository $ltiToolSettingRepository
     */
    public function __construct(LtiToolSettingRepository $ltiToolSettingRepository)
    {
        $this->ltiToolSettingRepository = $ltiToolSettingRepository;
    }

    /**
     * Get My Vimeo Videos List
     * Get the specified Vimeo API setting data.
     * @param Request $request
     * @return object $response
     * @throws GeneralException
     */
    public function getMyVideosList(Request $request)
    {
      $getParam = $request->only([
        'organization_id',
        'containing_uri',
        'direction',
        'filter',
        'filter_embeddable',
        'filter_playable',
        'page',
        'per_page',
        'query',
        'sort'
      ]);

      $auth = \Auth::user();
      $mediaSourcesIds = getVideoMediaSourceIdsArray();
      if ( $auth && $auth->id && isset($getParam['organization_id']) && $getParam['organization_id'] > 0 ) {
        $ltiRowResult = $this->ltiToolSettingRepository->getRowRecordByUserOrgAndToolType($auth->id, $getParam['organization_id'], $mediaSourcesIds['vimeo']);
        if ($ltiRowResult) {
            // Implement Command Design Pattern to access Vimeo API
            unset($getParam['organization_id']);
            $client = new Client($ltiRowResult);
            $instance = new GetMyVideoList($client);
            $response = $instance->fetch($ltiRowResult, $getParam);
            return $response;
        }
        throw new GeneralException('Unable to find vimeo settings!');
      }
      throw new GeneralException('Unable to get the record. Please check your payload data. organization_id, page and per_page is require field!');      
    }
}
