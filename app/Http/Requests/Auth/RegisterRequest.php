<?php

namespace App\Http\Requests\Auth;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL', // pass the email if record is soft-deleted issue#CUR-537
            'password' => ['required', 'string', new StrongPassword],
            'organization_name' => 'nullable|string|max:50',
            'organization_type' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
        ];
    }
}
