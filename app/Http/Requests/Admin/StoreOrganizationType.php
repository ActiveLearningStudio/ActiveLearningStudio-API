<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationType extends FormRequest
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
        $orgType = $this->route('organization_type');
        if($orgType){
            return [
                'name' => 'required|string|max:255|unique:organization_types,name,'.$orgType->id,
                'label' => 'required|string|max:255|unique:organization_types,label,'.$orgType->id,
                'order' => 'integer'
            ];
        } else {
            return [
                'name' => 'required|string|max:255|unique:organization_types',
                'label' => 'required|string|max:255'
            ];
        }
    }
}
