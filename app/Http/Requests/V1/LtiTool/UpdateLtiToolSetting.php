<?php

namespace App\Http\Requests\V1\LtiTool;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author        Asim Sarwar
 * Date           11-10-2021 
 * Description    Validation request class for update lti tool settings
 * class          UpdateLtiToolSetting
 */
class UpdateLtiToolSetting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @bodyParam     tool_name required|unique. Example: Safari Montage
     * @bodyParam     tool_url required|unique. Example: https://partner.safarimontage.com/SAFARI/api/imsltisearch.php
     * @bodyParam     lti_version required. Example: LTI-1p0
     * @bodyParam     tool_type required. Example: kaltura or safari_montage or vimeo or youtube or vimeo other
     * @bodyParam     tool_consumer_key nullable|unique. Example: consumer key
     * @bodyParam     tool_secret_key required_with:tool_consumer_key|unique. Example: secret key
     * @bodyParam     tool_content_selection_url nullable. Example: if not set, automatically set the tool_url
     * @bodyParam     user_id required. Example: 1
     * @bodyParam     organization_id required. Example: 1
     * @return array
     */
    public function rules()
    {
        $id = $this->route('lti_tool_setting');
        return [
            'tool_name' => 'required|string|max:255|unique:lti_tool_settings,tool_name, ' . $id . ' ,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_url' => 'required|url|max:255|unique:lti_tool_settings,tool_url, ' . $id . ' ,id,deleted_at,NULL,user_id,' . request('user_id'),
            'lti_version' => 'required|max:20',
            'tool_type' => 'required|in:kaltura,safari_montage,youtube,vimeo,other',
            'tool_consumer_key' => 'nullable|string|max:255|unique:lti_tool_settings,tool_consumer_key, ' . $id . ' ,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_secret_key' => 'required_with:tool_consumer_key|max:255|unique:lti_tool_settings,tool_secret_key, ' . $id . ' ,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_content_selection_url' => 'nullable|url|max:255',
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id'
        ];
    }
}
