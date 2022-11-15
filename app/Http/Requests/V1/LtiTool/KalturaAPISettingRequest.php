<?php

namespace App\Http\Requests\V1\LtiTool;

use Illuminate\Foundation\Http\FormRequest;

class KalturaAPISettingRequest extends FormRequest
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
            'pageSize' => 'required|integer',
            'pageIndex' => 'required|integer',
            'searchText' => 'nullable|string|max:255',
            'organization_id' => 'required|exists:organizations,id'
        ];
    }
}
