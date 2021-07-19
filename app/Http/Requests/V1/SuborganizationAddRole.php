<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuborganizationAddRole extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organization_role_types')->where(function ($query) use ($suborganization) {
                    return $query->where('organization_id', $suborganization->id);
                })
            ],
            'display_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organization_role_types')->where(function ($query) use ($suborganization) {
                    return $query->where('organization_id', $suborganization->id);
                })
            ],
            'permissions' => 'required|array|exists:organization_permission_types,id'
        ];
    }
}
