<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Kaltura API Client Library
 * ClassName    KalturaGeneratedAPIClientController
 */

namespace App\Http\Controllers\Api\V1\CurrikiInteractiveVideoIntegration\Kaltura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kaltura\Client\Configuration as KalturaConfiguration;
use Kaltura\Client\Client as KalturaClient;
use Kaltura\Client\Enum\SessionType as KalturaSessionType;
use Kaltura\Client\ApiException;
use Kaltura\Client\ClientException;
use Kaltura\Client\Type\MediaEntryFilter as KalturaMediaEntryFilter;
use Kaltura\Client\Type\FilterPager as KalturaFilterPager;
use Kaltura\Client;
use Illuminate\Support\Facades\App;
use App\Repositories\LtiTool\LtiToolSettingRepository;
use App\Exceptions\GeneralException;

class KalturaGeneratedAPIClientController extends Controller
{
    protected $kalturaConfiguration;
    protected $kalturaClient;
    protected $kalturaMediaEntryFilter;
    protected $kalturaFilterPager;
    private $ltiToolSettingRepository;

    /**
     * KalturaGeneratedAPIClientController constructor.
     *
     * @param KalturaConfiguration $kC, KalturaClient $kClient, KalturaMediaEntryFilter $kMEF, KalturaFilterPager $kFP
     */
    public function __construct(KalturaConfiguration $kC, KalturaClient $kClient, KalturaMediaEntryFilter $kMEF,
        KalturaFilterPager $kFP, LtiToolSettingRepository $ltiToolSettingRepository)
    {
        $this->kalturaConfiguration = $kC;
        $this->kalturaClient = $kClient;
        $this->kalturaMediaEntryFilter = $kMEF;
        $this->kalturaFilterPager = $kFP;
        $this->ltiToolSettingRepository = $ltiToolSettingRepository;
    }

    /**
     * Method       getMediaEntryList
     * Description  Use Kaltura Session to get the api token
     * Purpose      To get those media list, which do not have any 'Entitlement Enforcement/Permission Category'
     * Usage        Inside H5p Curriki Interactive Video
     * @param       Request $request
     * @return      string token
     * @throws GeneralException
     */
    public function getMediaEntryList(Request $request)
    {
      $getParam = $request->only([
          'organization_id',
          'pageSize',
          'pageIndex',
          'searchText'
      ]);
      $auth = \Auth::user();
      $mediaSourcesIds = getVideoMediaSourceIdsArray();
      if ($auth && $auth->id && isset($getParam['organization_id']) && $getParam['organization_id'] > 0 ) {
        $ltiRowResult = $this->ltiToolSettingRepository->getRowRecordByUserOrgAndToolType($auth->id, $getParam['organization_id'], $mediaSourcesIds['kaltura']);
        // Credentials For Kaltura Session
        if ($ltiRowResult) {
          $secret = $ltiRowResult->tool_secret_key;
          $partnerId = $ltiRowResult->tool_consumer_key;
        } else {
          throw new GeneralException('Unable to find kaltur settings, please try again later!');
        }
        $responeResult = '';
        $config = new $this->kalturaConfiguration();
        $config->setServiceUrl(config('kaltura.service_url'));
        $client = new $this->kalturaClient($config);

        $expiry = config('kaltura.expiry');
        $privileges = '*';
        // $sessionType mean Kaltura Session Type. It may be 0 or 2, 0 for user and 2 for admin (https://www.kaltura.com/api_v3/testmeDoc/enums/KalturaSessionType.html)
        $sessionType = config('kaltura.session_type');

        $pageSize = $getParam['pageSize'];
        $pageIndex = $getParam['pageIndex'];
        $searchText = $getParam['searchText'];

        try {
          /**
           * Use Kaltura Session to get the api token
           * @purpose Get All Kaltura Media List
           * @return string token
           */
          $ks = $client->session->start($secret, null, $sessionType, $partnerId, $expiry, $privileges);
          $client->setKS($ks);
          $filter = new $this->kalturaMediaEntryFilter();
          $filter->mediaTypeIn = 1;
          $filter->searchTextMatchOr = $searchText;
          $pager = new $this->kalturaFilterPager();
          $pager->pageSize = $pageSize;
          $pager->pageIndex = $pageIndex;
          try {
            $result = $client->media->listAction($filter, $pager);
            $responeResult = response()->json($result);
          } catch (Exception $e) {
            $responeResult = response()->json(array("error" => $e->getMessage()));
          }
        } catch (Exception $e) {
            $responeResult = response()->json(array("error" => $e->getMessage()));
        }
        return $responeResult;
      }
      throw new GeneralException('Unable to get the record. Require field organization_id, pageSize, pageIndex, searchText!');
    }

}
