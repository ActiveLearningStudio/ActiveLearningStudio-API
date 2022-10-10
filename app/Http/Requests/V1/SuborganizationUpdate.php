<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lowercase;
use Illuminate\Validation\Rule;

class SuborganizationUpdate extends FormRequest
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
        $suborganization = $this->route('suborganization');

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'domain' => ['required', 'alpha_dash', 'min:2', 'max:255', 'unique:organizations,domain,' . $suborganization->id, new Lowercase],
            'image' => 'required|max:255',
            'favicon' => 'nullable|max:255',
            'admins' => 'array|exists:App\User,id',
            'visibility_type_id' => 'array|min:1|required',
            'visibility_type_id.*' => 'integer|exists:App\Models\OrganizationVisibilityType,id',
            'users' => 'array',
            'users.*.user_id' => 'required_with:users.*.role_id|integer|exists:App\User,id',
            'users.*.role_id' => 'required_with:users.*.user_id|integer|exists:App\Models\OrganizationRoleType,id',
            'parent_id' => [
                'integer',
                Rule::exists('organizations', 'id')->where(function ($query) use ($suborganization) {
                    $query->where('id', '<>', $suborganization->id);
                }),
            ],
            'self_registration' => 'boolean',
            'account_id' => 'max:255',
            'api_key' => 'max:255',
            'unit_path' => 'max:255',
            'noovo_client_id' => 'string|max:255|nullable',
            'tos_type' => 'required|in:Parent,URL,Content',
            'tos_url' => 'required_if:tos_type,==,URL|url|max:255',
            'tos_content' => 'required_if:tos_type,==,Content|string|max:65000',
            'privacy_policy_type' => 'required|in:Parent,URL,Content',
            'privacy_policy_url' => 'required_if:privacy_policy_type,==,URL|url|max:255',
            'privacy_policy_content' => 'required_if:privacy_policy_type,==,Content|string|max:65000',
            'primary_color' => 'string|nullable|max:255',
            'secondary_color' => 'string|nullable|max:255',
            'tertiary_color' => 'string|nullable|max:255',
            'primary_font_family' => 'string|nullable|max:255',
            'secondary_font_family' => 'string|nullable|max:255',
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
