<?php

namespace App\Http\Requests\V1;

use App\Rules\StrongPassword;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $email = request('email');
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|email|max:255',
            'job_title' => 'nullable|string|max:255',
            'password' => [
                Rule::requiredIf( function () use ($email){
                    return User::where('email', $email)->exists() === FALSE;
                }),
                'nullable',
                new StrongPassword
            ],
            'role_id' => 'required|integer|exists:organization_role_types,id',
            'organization_name' => 'string',
            'organization_type' => 'string',
            'website' => 'string',
            'address' => 'string',
            'send_email' => 'nullable|boolean',
            'message' => 'nullable|required_if:send_email,true|max:500',
        ];
    }
}
