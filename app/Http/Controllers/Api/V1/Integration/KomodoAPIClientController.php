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
use App\Http\Requests\V1\LtiTool\KomodoAPISettingRequest;

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
     *
     * Get the specified Komodo API setting data. Using nside H5p Curriki Interactive Video
     *
     * @bodyParam organization_id required int. The Id of a suborganization Example: 1
     * @bodyParam page required string. Mean pagination or page number Example: 1
     * @bodyParam per_page required string. Mean record per page Example: 10next page and so on
     * @bodyParam search optional string mean search record by video title Example: Wildlife
     *
     * @responseFile responses/komodo/komodo-videos.json
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
        $mediaSourcesRow = MediaSource::where('name', $videoMediaSources['komodo'])->where('media_type', 'Video')->first();
        $mediaSourcesId = !empty($mediaSourcesRow) ? $mediaSourcesRow->id : 0;
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
