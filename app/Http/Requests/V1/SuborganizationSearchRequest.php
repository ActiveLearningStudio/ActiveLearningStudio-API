<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SuborganizationSearchRequest extends FormRequest
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
            'query' => 'string|max:255',
            'size' => 'integer|max:100',
            'order_by_column' => 'string|in:name,domain',
            'order_by_type' => 'string|in:asc,desc',
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
            'order_by_column.in' => 'The selected order by column should be name OR domain only',
            'order_by_type.in' => 'The selected order by type should be asc OR desc only',
        ];
    }
}
