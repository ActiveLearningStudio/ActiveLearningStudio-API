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
