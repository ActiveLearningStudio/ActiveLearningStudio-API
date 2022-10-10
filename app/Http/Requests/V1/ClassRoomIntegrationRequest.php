<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UUID;

class ClassRoomIntegrationRequest extends FormRequest
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
            'gcr_project_visibility' => 'boolean',
            'gcr_playlist_visibility' => 'boolean',
            'gcr_activity_visibility' => 'boolean',
            'msteam_client_id' => 'string|nullable|max:255',
            'msteam_secret_id' => 'string|nullable|max:255',
            'msteam_tenant_id' => 'string|nullable|max:255',
            'msteam_secret_id_expiry' => 'date|nullable',
            'msteam_project_visibility' => 'boolean',
            'msteam_playlist_visibility' => 'boolean',
            'msteam_activity_visibility' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tos_type.in' => 'The ToS type should be Parent, URL OR Content',
            'privacy_policy_type.in' => 'The privacy policy type should be Parent, URL OR Content',
        ];
    }
}
