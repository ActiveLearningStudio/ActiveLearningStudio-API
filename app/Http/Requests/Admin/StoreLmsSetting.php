<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLmsSetting extends FormRequest
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
            'lms_url' => 'required|url',
            'lms_access_token' => 'required|min:20',
            'site_name' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'lms_name' => 'nullable|string',
            'lms_access_key' => 'nullable|string',
            'lms_access_secret' => 'required_with:lms_access_key',
            'description' => 'nullable|max:500',
        ];
    }
}
