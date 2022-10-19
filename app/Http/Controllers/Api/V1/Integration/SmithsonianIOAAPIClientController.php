<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian Institution Open Access API's Client
 * ClassName    SmithsonianIOAAPIClientController
 */
namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrikiGo\Smithsonian\Client;
use App\CurrikiGo\Smithsonian\Contents\GetContentList;
use App\CurrikiGo\Smithsonian\Contents\GetContentDetail;
use App\CurrikiGo\Smithsonian\Contents\GetSearchFilterData;
use App\Exceptions\GeneralException;

class SmithsonianIOAAPIClientController extends Controller
{
    protected $client;
    /**
     * SmithsonianIOAAPIClientController constructor.
     * Create a new Client instance.
     * @return object $client
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Get Smithsonian Contents List
     * @param Request $request
     * @bodyParam q string use for search Example: q=online_visual_material:true AND IC 443
     * @bodyParam start int like page number Example: 1
     * @bodyParam rows int like page size or number of record per page Example: 10
     * @bodyParam sort string Sort list by id, newest, updated and random field 
     * @bodyParam type string get list by type = edanmdm or ead_collection or ead_component or all
     * @bodyParam row_group string The designated set of row types you are filtering it may be objects, archives
     * @return object $response
     * @throws GeneralException
     */
    public function getContentList(Request $request)
    {
        $getParam = $request->only([
            'q',
            'start',
            'rows',
            'sort',
            'type',
            'row_group'
        ]);
        $auth = \Auth::user();
        if ( $auth && $auth->id ) {
            $instance = new GetContentList($this->client);
            $response = $instance->fetch($getParam);
            return $response;
        }
        throw new GeneralException('Unauthorized!');
    }

    /**
     * Get Smithsonian Content Detail
     * @param Request $request
     * @bodyParam id string Example: con-1620124231687-1620150333404-0
     * @return object $response
     * @throws GeneralException
     */
    public function getContentDetail(Request $request)
    {
        $getParam = $request->only([
            'id'
        ]);
        $auth = \Auth::user();
        if ( $auth && $auth->id && isset($getParam['id']) && $getParam['id'] != '') {
            $instance = new GetContentDetail($this->client);
            $response = $instance->fetch($getParam);
            return $response;
        }
        throw new GeneralException('Please check your payload data. id field is require!');
    }

    /**
     * Get Smithsonian Search Filter Data
     * @param Request $request
     * @bodyParam the term category or search filter name. Required String. Only Allowed values:culture, data_source, date, object_type, online_media_type, place, topic, unit_code
     * @bodyParam starts_with the optional string prefix filter. Example: Any alphabet or string
     * @return object $response
     * @throws GeneralException
     */
    public function getSearchFilterData(Request $request)
    {
        $getParam = $request->only([
            'category',
            'starts_with'
        ]);
        $auth = \Auth::user();
        if ( $auth && $auth->id && isset($getParam['category']) && $getParam['category'] != '') {
            $instance = new GetSearchFilterData($this->client);
            $response = $instance->fetch($getParam);
            return $response;
        }
        throw new GeneralException('Please check your payload data. category field is require!');
    }
}
