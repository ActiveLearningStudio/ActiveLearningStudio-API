<?php

namespace App\Http\Controllers\Api\V1\LtiTool;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LtiTool\StoreLtiToolSettingRequest;
use App\Http\Requests\V1\LtiTool\UpdateLtiToolSettingRequest;
use App\Http\Resources\V1\LtiTool\LtiToolSettingCollection;
use App\Http\Resources\V1\LtiTool\LtiToolSettingResource;
use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Repositories\LtiTool\LtiToolSettingInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Repositories\MediaSources\MediaSourcesInterface;

/**
 * @authenticated
 *
 * @group 21. LTI Tool Settings API
 *
 * APIs for lti tool settings on admin panel.
 */
class LtiToolSettingsController extends Controller
{
    private $ltiToolSettingRepository;
    protected $mediaSourceRepo;

    /**
     * LtiToolSettingsController constructor.
     *
     * @param LtiToolSettingInterface $ltiToolSettingRepository
     * @param MediaSourcesInterface $mediaSourceRepo
     */
    public function __construct(LtiToolSettingInterface $ltiToolSettingRepository, MediaSourcesInterface $mediaSourceRepo)
    {
        $this->ltiToolSettingRepository = $ltiToolSettingRepository;
        $this->mediaSourceRepo = $mediaSourceRepo;
    }

    /**
     * Get All LTI Tool Settings.
     * 
     * Get a list of the lti tool settings for a user's default organization.
     * 
     * @urlParam $suborganization integer required Id of an organization Example: 1
     * @bodyParam query string Query to search lti tool settings against email or tool_name or tool_url Example: masterdemo@curriki.org or Kaltura API or https://4515783.kaf.kaltura.com
     * @bodyParam size integer Size to show per page records Example: 10
     * @bodyParam order_by_column string To sort data with specific column Example: name
     * @bodyParam order_by_type string To sort data in ascending or descending order Example: asc
     * 
     * @responseFile responses/admin/lti-tool/lti-tool-settings-list.json
     * 
     * @param Request $request
     * @param Organization $suborganization
     * 
     * @return LtiToolSettingCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $collections = $this->ltiToolSettingRepository->getAll($request->all(), $suborganization);
        return new LtiToolSettingCollection($collections);
    }

    /**
     * Get Lti Tool Setting
     *
     * Get the specified lti tool setting details.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam id required The Id of a lti tool settings Example: 1
     *
     * @responseFile responses/admin/lti-tool/lti-tool-settings-show.json
     *
     * @param Organization $suborganization
     * @param $id
     * @return LtiToolSettingResource
     */
    public function show(Organization $suborganization, $id)
    {
        $setting = $this->ltiToolSettingRepository->find($id);
        return new LtiToolSettingResource($setting->load('organization', 'mediaSources'));
    }

    /**
     * Create Lti tool Settings
     * 
     * Creates the new lti tool settings in database
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam user_id int required Logged user id Example: 2
     * @bodyParam organization_id int required Id of an organization Example: 1
     * @bodyParam tool_name string required LTI Tool Settings tool name Example: Kaltura API Integration
     * @bodyParam tool_url string required LTI Tool Settings tool url Example: https://4515783.kaf.kaltura.com
     * @bodyParam lti_version string required LTI Tool Settings lti version Example: LTI-1p0
     * @bodyParam lti_tool_type_id int required Id of lti tool type Example: 1
     * @bodyParam tool_description string optional LTI Tool Settings description Example: Kaltura API Testing
     * @bodyParam tool_custom_parameter string optional LTI Tool Settings custom param Example: embed=true
     * @bodyParam tool_consumer_key string optional LTI Tool Settings consumer key Example: 4515783
     * @bodyParam tool_secret_key string optional Required with consumer key. Example: Token
     * @bodyParam tool_content_selection_url string optional Content ulr Example: https:kaltura.com
     * @bodyParam tool_content_selection_url string optional Content ulr Example: https:kaltura.com
     *
     * @responseFile 200 responses/admin/lti-tool/lti-tool-settings-create.json
     * 
     * @response 500 {
     *   "errors": [
     *     "You have already created these settings!"
     *   ]
     * }
     *
     * @param StoreLtiToolSettingRequest $request
     * @param Organization $suborganization
     * @return LtiToolSettingResource
     */
    public function store(StoreLtiToolSettingRequest $request, Organization $suborganization)
    {
        $data = $request->all();
        $parse = parse_url($data['tool_url']);
        $data['tool_domain'] = $parse['host'];
        $data['tool_content_selection_url'] = (isset($data['tool_content_selection_url']) && $data['tool_content_selection_url'] != '') ? $data['tool_content_selection_url'] : $data['tool_url'];
        $response = $this->ltiToolSettingRepository->create($data);
        return response(['message' => $response['message'], 'data' => new LtiToolSettingResource($response['data']->load('organization', 'mediaSources'))], 200);
        
    }

    /**
     * Update Lti tool Setting
     * 
     * Update the specific lti tool settings record
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam id required The Id of a LTI Tool Settings Example: 1
     * @bodyParam user_id int required Logged user id Example: 2
     * @bodyParam organization_id int required Id of an organization Example: 1
     * @bodyParam tool_name string required LTI Tool Settings tool name Example: Kaltura API Integration
     * @bodyParam tool_url string required LTI Tool Settings tool url Example: https://4515783.kaf.kaltura.com
     * @bodyParam lti_version string required LTI Tool Settings lti version Example: LTI-1p0
     * @bodyParam lti_tool_type_id int required Id of lti tool type Example: 1
     * @bodyParam tool_description string optional LTI Tool Settings description Example: Kaltura API Testing
     * @bodyParam tool_custom_parameter string optional LTI Tool Settings custom param Example: embed=true
     * @bodyParam tool_consumer_key string optional LTI Tool Settings consumer key Example: 4515783
     * @bodyParam tool_secret_key string optional Required with consumer key. Example: Token
     * @bodyParam tool_content_selection_url string optional Content ulr Example: https:kaltura.com
     * @bodyParam tool_content_selection_url string optional Content ulr Example: https:kaltura.com
     *
     * @responseFile 200 responses/admin/lti-tool/lti-tool-settings-update.json
     * 
     * @param UpdateLtiToolSettingRequest $request
     * @param Organization $suborganization
     * @param $id 
     * @return LtiToolSettingResource
     */
    public function update(UpdateLtiToolSettingRequest $request, Organization $suborganization, $id)
    {
        $data = $request->all();
        $parse = parse_url($data['tool_url']);
        $data['tool_domain'] = $parse['host'];
        $data['tool_content_selection_url'] = (isset($data['tool_content_selection_url']) && $data['tool_content_selection_url'] != '') ? $data['tool_content_selection_url'] : $data['tool_url'];
        $response = $this->ltiToolSettingRepository->update($id, $data);
        return response(['message' => $response['message'], 'data' => new LtiToolSettingResource($response['data']->load('organization', 'mediaSources'))], 200);
    }

    /**
     * Delete Lti Tool
     * 
     * Delete Lti Tool Setting
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam id required The Id of a LTI Tool Settings Example: 1
     * 
     * @param Organization $suborganization
     * @param $id
     * 
     * @responseFile 200 responses/admin/lti-tool/lti-tool-settings-destory.json
     */
    public function destroy(Organization $suborganization, $id)
    {
        return response(['message' => $this->ltiToolSettingRepository->destroy($id)], 200);
    }

    /**
     * Get LTI Tool Type List
     *
     * Get lti tool type list from lti_tool_type table.
     *
     * @responseFile 200 responses/admin/lti-tool/lti-tool-type-list.json
     * 
     * @return LtiToolSettingResource
     */
    public function getLtiToolTypeList()
    {
        $videoMediaSource = $this->mediaSourceRepo->getMediaSourcesByType('Video');
        return new LtiToolSettingResource($videoMediaSource);
    }
}
