<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Komodo API Client
 * ClassName    KomodoAPIClientController
 */
namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LtiTool\LtiToolSettingRepository;
use App\CurrikiGo\Komodo\Client;
use App\CurrikiGo\Komodo\Videos\GetMyVideoList;
use App\Exceptions\GeneralException;
use App\Models\MediaSource;

class KomodoAPIClientController extends Controller
{
    private $ltiToolSettingRepository;
    /**
     * KomodoAPIClientController constructor.
     * @param LtiToolSettingRepository $ltiToolSettingRepository
     */
    public function __construct(LtiToolSettingRepository $ltiToolSettingRepository)
    {
        $this->ltiToolSettingRepository = $ltiToolSettingRepository;
    }

    /**
     * Get My Komodo Videos List
     * Get the specified Komodo API setting data.
     * @param Request $request
     * @return object $response
     * @throws GeneralException
     */
    public function getMyVideosList(Request $request)
    {
      $getParam = $request->only([
        'organization_id',
        'page',
        'per_page',
        'search'
      ]);
      $auth = \Auth::user();
      $mediaSourcesRow = MediaSource::where('name', 'Komodo')->where('media_type', 'Video')->first();
      $mediaSourcesId = ($mediaSourcesRow) ? $mediaSourcesRow->id : 0;
      if ( $auth && $auth->id && isset($getParam['organization_id']) && $getParam['organization_id'] > 0 ) {
        $ltiRowResult = $this->ltiToolSettingRepository->getRowRecordByUserOrgAndToolType($auth->id, $getParam['organization_id'], $mediaSourcesId);
        if ($ltiRowResult) {
            // Implement Command Design Pattern to access Komodo API
            unset($getParam['organization_id']);
            $client = new Client($ltiRowResult);
            $instance = new GetMyVideoList($client);
            $response = $instance->fetch($ltiRowResult, $getParam);
            return $response;
        }
        throw new GeneralException('Unable to find komodo settings!');
      }
      throw new GeneralException('Unable to get the record. Please check your payload data. organization_id, page and per_page is require field!');      
    }
}
