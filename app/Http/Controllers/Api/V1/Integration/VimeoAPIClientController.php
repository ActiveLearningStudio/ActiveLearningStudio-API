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
use App\Http\Requests\V1\LtiTool\VimeoAPISettingRequest;
use App\Models\MediaSource;

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
     *
     * Get the specified Vimeo API setting data. Using nside H5p Curriki Interactive Video
     *
     * @bodyParam organization_id required int. The Id of a suborganization Example: 1
     * @bodyParam page required string. Mean pagination or page number Example: 1
     * @bodyParam per_page required string. Mean record per page Example: 10
     * @bodyParam query optional string mean search record by video title or video id Example: 696855454 or Wildlife Windows
     *
     * @responseFile responses/vimeo/vimeo-videos.json
     *
     * @param VimeoAPISettingRequest $request
     * @return object $response
     * @throws GeneralException
     */
    public function getMyVideosList(VimeoAPISettingRequest $request)
    {
      $getParam = $request->only([
        'organization_id',
        'page',
        'per_page',
        'query'
      ]);
      $videoMediaSources = getVideoMediaSources();
      $mediaSourcesRow = MediaSource::where('name', $videoMediaSources['vimeo'])->where('media_type', 'Video')->first();
      $mediaSourcesId = !empty($mediaSourcesRow) ? $mediaSourcesRow->id : 0;
      $ltiRowResult = $this->ltiToolSettingRepository->getRowRecordByOrgAndToolType($getParam['organization_id'], $mediaSourcesId);
      if ($ltiRowResult) {
          // Implement Command Design Pattern to access Vimeo API
          unset($getParam['organization_id']);
          $client = new Client($ltiRowResult);
          $instance = new GetMyVideoList($client);
          $response = $instance->fetch($ltiRowResult, $getParam);
          return $response;
      }
      throw new GeneralException('Unable to find Vimeo API settings!');
    }
}
