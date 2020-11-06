<?php

namespace App\Http\Requests\Admin;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam first_name string required First name of the user. Example: Ahmad
 * @bodyParam last_name string required Last name of the user. Example: Mukhtar
 * @bodyParam organization_name string required Organization name. Example: Studio
 * @bodyParam organization_type string required Valid organization type. Example: K-12
 * @bodyParam job_title string required Job title of the user in organization. Example: 2
 * @bodyParam email email required Valid Email. Example: ahmedmukhtar1133@gmail.com
 * @bodyParam password password required Must be at-least 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase and 1 Numeric character. Example: kljd@Fi4R
 */
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
