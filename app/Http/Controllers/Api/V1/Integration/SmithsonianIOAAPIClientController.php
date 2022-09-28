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
        throw new GeneralException('Please check you payload data. id field is require!');
    }
}
