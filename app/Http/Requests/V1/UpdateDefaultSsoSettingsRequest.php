<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDefaultSsoSettingsRequest extends FormRequest
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
        return [
            'lms_url' => 'required|url|max:255',
            'lms_access_token' => 'required|min:20|max:255',
            'site_name' => 'required|string|max:255',
            'lti_client_id' => 'nullable|string|max:255',
            'lms_name' => 'nullable|string|max:255',
            'lms_access_key' => 'nullable|string|max:255',
            'lms_access_secret' => 'required_with:lms_access_key|max:255',
            'description' => 'nullable|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'role_id' => 'required|integer|exists:organization_role_types,id',
            'project_visibility' => 'nullable|boolean',
            'playlist_visibility' => 'nullable|boolean',
            'activity_visibility' => 'nullable|boolean',
        ];
    }
}
