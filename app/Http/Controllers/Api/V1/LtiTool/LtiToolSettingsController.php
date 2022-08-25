<?php

namespace App\Http\Controllers\Api\V1\LtiTool;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LtiTool\StoreLtiToolSetting;
use App\Http\Requests\V1\LtiTool\UpdateLtiToolSetting;
use App\Http\Resources\V1\LtiTool\LtiToolSettingCollection;
use App\Http\Resources\V1\LtiTool\LtiToolSettingResource;
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
     * Creates the new lti tool setting in database
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
