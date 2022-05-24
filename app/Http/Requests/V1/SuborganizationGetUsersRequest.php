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
            'order_by_column' => 'string|in:first_name,last_name',
            'order_by_type' => 'string|in:asc,desc',
            'role' => 'integer|exists:organization_role_types,id,organization_id,' . $suborganization->id,
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'order_by_column.in' => 'The selected order by column should be first_name OR last_name only',
            'order_by_type.in' => 'The selected order by type should be asc OR desc only',
        ];
    }
}
