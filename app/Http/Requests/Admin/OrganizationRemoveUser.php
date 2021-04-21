<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationRemoveUser extends FormRequest
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
        $organization = $this->route('organization');

        return [
            'organization' => 'integer|exists:App\Models\Organization,id',
            'user' => [
                'required',
                'integer',
                'exists:App\User,id',
                Rule::exists('organization_user_roles', 'user_id')->where(function ($query) use ($organization) {
                    return $query->where('organization_id', $organization);
                })
            ]
        ];
    }

    public function all($keys = null) 
    {
        $data = parent::all($keys);
        $data['user'] = $this->route('user');
        return $data;
    }
}
