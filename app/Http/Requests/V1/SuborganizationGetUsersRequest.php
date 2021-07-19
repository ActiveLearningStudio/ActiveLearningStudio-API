<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuborganizationGetUsersRequest extends FormRequest
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
            'query' => 'string|min:2|max:255',
            'size' => 'integer|max:100',
            'role' => 'integer|exists:organization_role_types,id,organization_id,' . $suborganization->id,
        ];
    }
}
