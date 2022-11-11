<?php

namespace App\Http\Requests\V1\LtiTool;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author        Asim Sarwar
 * Date           11-10-2021
 * Description    Validation request class for create lti tool settings
 * class          StoreLtiToolSetting  
 */
class StoreLtiToolSettingRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        $orgId = request('organization_id');
        return [
            'tool_name' => 'required|string|max:255|unique:lti_tool_settings,tool_name,NULL,id,deleted_at,NULL,organization_id,' . $orgId,
            'tool_url' => 'required|url|max:255|unique:lti_tool_settings,tool_url,NULL,id,deleted_at,NULL,organization_id,' . $orgId,
            'lti_version' => 'required|max:20',
            'media_source_id' => 'required|exists:media_sources,id',
            'tool_consumer_key' => 'nullable|string|max:255|unique:lti_tool_settings,tool_consumer_key,NULL,id,deleted_at,NULL,organization_id,' . $orgId,
            'tool_secret_key' => 'required_with:tool_consumer_key|max:255|unique:lti_tool_settings,tool_secret_key,NULL,id,deleted_at,NULL,organization_id,' . $orgId,
            'tool_content_selection_url' => 'nullable|url|max:255',
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id'
        ];
    }
}
