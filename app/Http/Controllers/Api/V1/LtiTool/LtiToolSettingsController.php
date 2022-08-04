<?php

namespace App\Http\Controllers\Api\V1\LtiTool;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LtiTool\StoreLtiToolSetting;
use App\Http\Requests\V1\LtiTool\UpdateLtiToolSetting;
use App\Http\Resources\V1\LtiTool\LtiToolSettingCollection;
use App\Http\Resources\V1\LtiTool\LtiToolSettingResource;
use App\Jobs\CloneLtiToolSetting;
use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Repositories\LtiTool\LtiToolSettingRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * @author        Asim Sarwar
 * Date           11-10-2021
 * @group 1008.   Admin/Lti Tool Settings *
 * APIs for Lti tool settings on admin panel.
 */
class LtiToolSettingsController extends Controller
{
    private $ltiToolSettingRepository;

    /**
     * LtiToolSettingsController constructor.
     * @param ltiToolSettingRepository $ltiToolSettingRepository
     */
    public function __construct(LtiToolSettingRepository $ltiToolSettingRepository)
    {
        $this->ltiToolSettingRepository = $ltiToolSettingRepository;
    }

    /**
     * Get All LTI Tool Settings for listing.
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     * @queryParam Organization $suborganization
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     * @responseFile responses/admin/lti-tool/lti-tool-settings
     * @param Request $request
     * @return LtiToolSettingCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $collections = $this->ltiToolSettingRepository->getAll($request->all(), $suborganization);
        return new LtiToolSettingCollection($collections);
    }

    /**
     * Get Lti Tool Setting
     * Get the specified lti tool setting data.
     * @urlParam id required The Id of a lti_tool_settings table Example: 1
     * @urlParam Organization $suborganization
     * @responseFile responses/admin/lti-tool/lti-tool-settings
     * @return LtiToolSettingResource
     * @param $id
     * @throws GeneralException
     */
    public function show(Organization $suborganization, $id)
    {
        $setting = $this->ltiToolSettingRepository->find($id);
        return new LtiToolSettingResource($setting->load('user', 'organization', 'mediaSources'));
    }

    /**
     * Create Lti tool Setting
     * Creates the new lti tool setting in database.     *
     * @response {
     *   "message": "Lti Tool Setting created successfully!",
     *   "data": ["Created Setting Data Array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to create setting, please try again later!"
     *   ]
     * }
     * @param StoreLtiToolSetting $request
     * @param Organization $suborganization
     * @return LtiToolSettingResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreLtiToolSetting $request, Organization $suborganization)
    {
        $data = $request->only([
            'user_id',
            'organization_id',
            'tool_name',
            'tool_url',
            'lti_version',
            'media_source_id',
            'tool_description',
            'tool_custom_parameter',
            'tool_consumer_key',
            'tool_secret_key',
            'tool_content_selection_url'
        ]);
        $parse = parse_url($data['tool_url']);
        $data['tool_domain'] = $parse['host'];
        $data['tool_content_selection_url'] = (isset($data['tool_content_selection_url']) && $data['tool_content_selection_url'] != '') ? $data['tool_content_selection_url'] : $data['tool_url'];
        $response = $this->ltiToolSettingRepository->create($data);
        return response(['message' => $response['message'], 'data' => new LtiToolSettingResource($response['data']->load('user', 'organization', 'mediaSources'))], 200);
    }

    /**
     * Update Lti Tool Setting
     * @urlParam id required The Id of a lti_tool_settings table Example: 1
     * @response {
     *   "message": "Lti tool setting data updated successfully!",
     *   "data": ["Updated Lti Tool setting data array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to update Lti Tool setting, please try again later."
     *   ]
     * }
     * @param UpdateLtiToolSetting $request
     * @param Organization $suborganization
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(UpdateLtiToolSetting $request, Organization $suborganization, $id)
    {
        $data = $request->only([
            'user_id',
            'organization_id',
            'tool_name',
            'tool_url',
            'lti_version',
            'media_source_id',
            'tool_description',
            'tool_custom_parameter',
            'tool_consumer_key',
            'tool_secret_key',
            'tool_content_selection_url'
        ]);
        $parse = parse_url($data['tool_url']);
        $data['tool_domain'] = $parse['host'];
        $data['tool_content_selection_url'] = (isset($data['tool_content_selection_url']) && $data['tool_content_selection_url'] != '') ? $data['tool_content_selection_url'] : $data['tool_url'];
        $response = $this->ltiToolSettingRepository->update($id, $data);
        return response(['message' => $response['message'], 'data' => new LtiToolSettingResource($response['data']->load('user', 'organization', 'mediaSources'))], 200);
    }

    /**
     * Delete Lti Tool Setting
     * @urlParam id required The Id of a lti_tool_settings Example: 1
     * @response {
     *   "message": "Lti Tool setting deleted successfully!",
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to delete Lti Tool setting, please try again later."
     *   ]
     * }
     * @param Organization $suborganization
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy(Organization $suborganization, $id)
    {
        return response(['message' => $this->ltiToolSettingRepository->destroy($id)], 200);
    }

    /**
     * Clone LTI tool setting
     *
     * Clone the specified Lti tool setting of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam LtiToolSetting required The Id of a LtiToolSetting Example: 1
     * @bodyParam user_id optional The Id of a user Example: 1
     *
     * @response {
     *   "message": "Lti tool setting is being cloned in background!"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Unable to clone."
     *   ]
     * }
     *
     * @param Request $request
     * @param Organization $suborganization
     * @param LtiToolSetting $ltiToolSetting
     * @return Response
     */
    public function clone(Request $request, Organization $suborganization, LtiToolSetting $ltiToolSetting)
    {
        $requestData = $request->only([
            'user_id'
        ]);
        $requestData['tool_name'] = $ltiToolSetting->tool_name;
        $requestData['tool_url'] = $ltiToolSetting->tool_url;
        $requestData['lti_version'] = $ltiToolSetting->lti_version;
        $requestData['media_source_id'] = $ltiToolSetting->media_source_id;
        $requestData['tool_consumer_key'] = $ltiToolSetting->tool_consumer_key;
        $requestData['tool_secret_key'] = $ltiToolSetting->tool_secret_key;
        $requestData['tool_content_selection_url'] = $ltiToolSetting->tool_content_selection_url;
        $requestData['organization_id'] = $ltiToolSetting->organization_id;
        $request->merge($requestData);
        $validated = $request->validate([
            'tool_name' => 'required|string|max:255|unique:lti_tool_settings,tool_name,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_url' => 'required|url|max:255|unique:lti_tool_settings,tool_url,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'lti_version' => 'required|max:20',
            'media_source_id' => 'required|exists:media_sources,id',
            'tool_consumer_key' => 'nullable|string|max:255|unique:lti_tool_settings,tool_consumer_key,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_secret_key' => 'required_with:tool_consumer_key|max:255|unique:lti_tool_settings,tool_secret_key,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_content_selection_url' => 'nullable|url|max:255',
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id'
        ]);
        CloneLtiToolSetting::dispatch($ltiToolSetting, $suborganization, $request->bearerToken())->delay(now()->addSecond());
        return response([
            "message" => "Your request to clone Lti Tool Setting [$ltiToolSetting->tool_name] has been received and is being processed. <br> 
            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
    }

    /**
     * Get Tool Type For LTI Tool Settings
     * @urlParam Organization $suborganization
     * @responseFile responses/admin/lti-tool/lti-tool-settings
     * @return LtiToolSettingResource
     */
    public function getLTIToolTypeList(Organization $suborganization)
    {
        $ltiToolType = $suborganization->mediaSources->where('media_type', 'Video');
        return new LtiToolSettingResource($ltiToolType);
    }
}
