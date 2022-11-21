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
     * @bodyParam page required string Mean pagination or page number Example: 1
     * @bodyParam per_page required string Mean record per page Example: 10next page and so on
     * @bodyParam search optional string Mean search record by video title Example: Wildlife
     *
     * @responseFile responses/komodo/komodo-videos.json
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to find Komodo API settings!"
     *   ]
     * }
     *
     * @param KomodoAPISettingRequest $request
     * @return object $response
     * @throws GeneralException
     */
    public function getMyVideosList(KomodoAPISettingRequest $request)
    {
        $getParam = $request->only([
            'organization_id',
            'page', 
            'per_page',
            'search'
        ]);
        $videoMediaSources = getVideoMediaSources();
      $mediaSourcesId = $this->mediaSourcesRepository->getMediaSourceIdByName($videoMediaSources['komodo']);
      $ltiRowResult = $this->ltiToolSettingRepository->getRowRecordByOrgAndToolType($getParam['organization_id'], $mediaSourcesId);
        if ($ltiRowResult) {
            // Implement Command Design Pattern to access Komodo API
            unset($getParam['organization_id']);
            $client = new Client($ltiRowResult);
            $instance = new GetMyVideoList($client);
            $response = $instance->fetch($ltiRowResult, $getParam);
            return $response;
        }
        throw new GeneralException('Unable to find Komodo API settings!');
    }
}
