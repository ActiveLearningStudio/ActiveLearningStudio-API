<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuborganizationUpdateUser extends FormRequest
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
        $authenticated_user = auth()->user();
        $default_organization = $authenticated_user->default_organization;

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:App\User,id',
                Rule::exists('organization_user_roles')->where(function ($query) use ($default_organization) {
                    return $query->where('organization_id', $default_organization);
                })
            ],
            'role_id' => 'required|integer|exists:organization_role_types,id'
        ];
    }
}
