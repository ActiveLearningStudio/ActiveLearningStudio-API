<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'last_name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255|unique:users',
            'organization_name' => 'required|string|min:2|max:50',
            'organization_type' => 'string|max:255',
            'website' => 'url|max:255',
            'job_title' => 'required|string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:255',
        ];
    }
}
