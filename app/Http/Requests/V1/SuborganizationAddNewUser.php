<?php

namespace App\Http\Requests\V1;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class SuborganizationAddNewUser extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'job_title' => 'nullable|string|max:255',
            'password' => ['required', 'string', new StrongPassword],
            'role_id' => 'required|integer|exists:organization_role_types,id',
            'organization_name' => 'string',
            'organization_type' => 'string',
            'website' => 'string',
            'address' => 'string',
        ];
    }
}
