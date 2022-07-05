<?php

namespace App\Http\Requests\V1\LtiTool;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author        Asim Sarwar
 * Date           11-10-2021
 * Description    Validation request class for create lti tool settings
 * class          StoreLtiToolSetting  
 */
class StoreLtiToolSetting extends FormRequest
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
     * @bodyParam     media_source_id required. Example: media_source_id required. Example: Kaltura or safari Montage or Vimeo or Youtube
     * @bodyParam     tool_consumer_key nullable|unique. Example: consumer key
     * @bodyParam     tool_secret_key required_with:tool_consumer_key|unique. Example: secret key
     * @bodyParam     tool_content_selection_url nullable. Example: if not set, automatically set the tool_url
     * @bodyParam     user_id required. Example: 1
     * @bodyParam     organization_id required. Example: 1
     * @return array
     */
    public function rules()
    {
        return [
            'tool_name' => 'required|string|max:255|unique:lti_tool_settings,tool_name,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_url' => 'required|url|max:255|unique:lti_tool_settings,tool_url,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'lti_version' => 'required|max:20',
            'media_source_id' => 'required|exists:media_sources,id',
            'tool_consumer_key' => 'nullable|string|max:255|unique:lti_tool_settings,tool_consumer_key,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_secret_key' => 'required_with:tool_consumer_key|max:255|unique:lti_tool_settings,tool_secret_key,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'tool_content_selection_url' => 'nullable|url|max:255',
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id'
        ];
    }
}
