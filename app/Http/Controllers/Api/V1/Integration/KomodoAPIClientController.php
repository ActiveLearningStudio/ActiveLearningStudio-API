<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Komodo API Client
 * ClassName    KomodoAPIClientController
 */
namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LtiTool\LtiToolSettingInterface;
use App\CurrikiGo\Komodo\Client;
use App\CurrikiGo\Komodo\Videos\GetMyVideoList;
use App\Exceptions\GeneralException;
use App\Http\Requests\V1\LtiTool\KomodoAPISettingRequest;
use App\Repositories\MediaSources\MediaSourcesInterface;
use Auth;

class KomodoAPIClientController extends Controller
{
    private $ltiToolSettingRepository;
    private $mediaSourcesRepository;
    /**
     * KomodoAPIClientController constructor.
     * @param LtiToolSettingInterface $ltiToolSettingRepository
     * @param MediaSourcesInterface $mediaSourcesRepository
     */
    public function __construct(LtiToolSettingInterface $ltiToolSettingRepository, MediaSourcesInterface $mediaSourcesRepository)
    {
        $this->ltiToolSettingRepository = $ltiToolSettingRepository;
        $this->mediaSourcesRepository = $mediaSourcesRepository;
    }

    /**
     * Get My Komodo Videos List
     *
     * Get the specified Komodo API setting data. Using inside H5p Curriki Interactive Video
     *
     * @bodyParam organization_id required int The Id of a suborganization Example: 1
     * @bodyParam page required int Mean pagination or page number Example: 1
     * @bodyParam per_page required int Mean record per page Example: 10
     * @bodyParam search optional string Mean search record by video title Example: Wildlife
     *
     * @responseFile responses/komodo/komodo-videos.json
     *
     * @response 401 {
     *   "errors": "Unauthorized"
     * }
     * @response 403 {
     *   "errors": "Forbidden"
     * }
     * @response 404 {
     *   "errors": "User not found"
     * }
     *
     * @param KomodoAPISettingRequest $request
     * @return json object $response
     */
    public function getMyVideosList(KomodoAPISettingRequest $request)
    {
        $getParam = $request->only([
            'organization_id',
            'page', 
            'per_page',
            'search'
        ]);
        $apiSettings = [];
        $apiSettings['logged_user_email'] = Auth::user()->email;
        $videoMediaSources = getVideoMediaSources();
        $mediaSourcesId = $this->mediaSourcesRepository->getMediaSourceIdByName($videoMediaSources['komodo']);
        $ltiRowResult = $this->ltiToolSettingRepository->getRowRecordByOrgAndToolType($getParam['organization_id'], $mediaSourcesId);
        if ( !empty($ltiRowResult) ) {
            $apiSettings['tool_secret_key'] = $ltiRowResult->tool_secret_key;
        } else {
            $apiSettings['tool_secret_key'] = config('komodo.api_key');
        }

        // Implement Command Design Pattern to access Komodo API
        unset($getParam['organization_id']);
        $client = new Client();
        $instance = new GetMyVideoList($client);
        $response = $instance->fetch($apiSettings, $getParam);
        return $response;
    }
}
