<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SuborganizationUpdateRoleUiPermissions extends FormRequest
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
            'role_id' => 'required|exists:organization_role_types,id,organization_id,' . $suborganization->id,
            'permissions' => 'required|array|exists:ui_module_permissions,id'
        ];
    }
}
