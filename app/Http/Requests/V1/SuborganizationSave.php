<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SuborganizationSave extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'domain' => 'required|alpha_dash|min:2|max:255|unique:organizations,domain',
            'image' => 'required',
            'admins' => 'required|array|exists:App\User,id',
            'users' => 'required|array',
            'users.*.user_id' => 'required_with:users.*.role_id|integer|exists:App\User,id',
            'users.*.role_id' => 'required_with:users.*.user_id|integer|exists:App\Models\OrganizationRoleType,id',
            'parent_id' => 'required|integer|exists:App\Models\Organization,id'
        ];
    }
}
