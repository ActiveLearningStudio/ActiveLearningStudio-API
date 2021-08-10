<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lowercase;
use Illuminate\Validation\Rule;

class SuborganizationUpdate extends FormRequest
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
        $suborganization = $this->route('suborganization');

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'domain' => ['required', 'alpha_dash', 'min:2', 'max:255', 'unique:organizations,domain,' . $suborganization->id, new Lowercase],
            'image' => 'required',
            'admins' => 'array|exists:App\User,id',
            'users' => 'array',
            'users.*.user_id' => 'required_with:users.*.role_id|integer|exists:App\User,id',
            'users.*.role_id' => 'required_with:users.*.user_id|integer|exists:App\Models\OrganizationRoleType,id',
            'parent_id' => [
                'integer',
                Rule::exists('organizations', 'id')->where(function ($query) use ($suborganization) {
                    $query->where('id', '<>', $suborganization->id);
                }),
            ],
            'self_registration' => 'boolean',
            'account_id' => 'max:255',
            'api_key' => 'max:255',
            'unit_path' => 'max:255'
        ];
    }
}
