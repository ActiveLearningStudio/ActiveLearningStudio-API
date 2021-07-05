<?php

namespace App\Http\Requests\V1;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class SuborganizationUpdateUserDetail extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.request('user_id'),
            'job_title' => 'nullable|string|max:255',
            'role_id' => 'required|integer|exists:organization_role_types,id',
            'password' => ['sometimes', 'string', new StrongPassword],
            'organization_name' => 'string',
            'organization_type' => 'string',
            'website' => 'string',
            'address' => 'string',
        ];
    }
}
