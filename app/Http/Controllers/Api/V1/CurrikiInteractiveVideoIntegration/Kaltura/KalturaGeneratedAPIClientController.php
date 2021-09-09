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

class KalturaGeneratedAPIClientController extends Controller
{
    protected $kalturaConfiguration;
    protected $kalturaClient;
    protected $kalturaMediaEntryFilter;
    protected $kalturaFilterPager;

    /**
     * KalturaGeneratedAPIClientController constructor.
     *
     * @param KalturaConfiguration $kC, KalturaClient $kClient, KalturaMediaEntryFilter $kMEF, KalturaFilterPager $kFP
     */
    public function __construct(KalturaConfiguration $kC, KalturaClient $kClient, KalturaMediaEntryFilter $kMEF, KalturaFilterPager $kFP)
    {
        $this->kalturaConfiguration = $kC;
        $this->kalturaClient = $kClient;
        $this->kalturaMediaEntryFilter = $kMEF;
        $this->kalturaFilterPager = $kFP;

    }

    /**
     * Method       getMediaEntryList  
     * Description  Use Kaltura Session to get the api token
     * Purpose      To get those media list, which do not have any 'Entitlement Enforcement/Permission Category'
     * Usage        Inside H5p Curriki Interactive Video 
     * @param       Request $request
     * @return      string token
     */
    public function getMediaEntryList(Request $request)
    {
        $getParam = $request->only([            
            'pageSize',
            'pageIndex',
            'searchText'           
        ]);
        $responeResult = '';
        $config = new $this->kalturaConfiguration();
        $config->setServiceUrl(config('kaltura.service_url'));
        $client = new $this->kalturaClient($config);
        // Credentials For Kaltura Session
        $secret = config('kaltura.secret'); //Remember to provide the correct secret according to the sessionType you want
        $partnerId = config('kaltura.partner_id');
        $expiry = config('kaltura.expiry');
        $privileges = config('kaltura.privileges');
        // $sessionType mean Kaltura Session Type. It may be 0 or 2, 0 for user and 2 for admin (https://www.kaltura.com/api_v3/testmeDoc/enums/KalturaSessionType.html)
        $sessionType = config('kaltura.session_type');  

        $pageSize = $getParam['pageSize'];
        $pageIndex = $getParam['pageIndex'];
        $searchText = $getParam['searchText'];

        try {
          /**
           * Use Kaltura Session to get the api token
           * @purpose To get those media list, which do not have any 'Entitlement Enforcement/Permission Category'
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
            $responeResult = response()->json(array("results" => $result));
          } catch (Exception $e) {
            $responeResult = response()->json(array("error" => $e->getMessage()));
          }
        } catch (Exception $e) {
            $responeResult = response()->json(array("error" => $e->getMessage()));
        }
        return $responeResult;
    }
    
}
