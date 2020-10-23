<?php

namespace App\Http\Requests\Admin;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'organization_name' => 'required|string|max:50',
            'organization_type' => 'required|string|max:255|exists:organization_types,label',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => ['sometimes', 'required', 'string', new StrongPassword],
        ];
    }
}
