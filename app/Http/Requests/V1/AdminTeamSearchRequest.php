<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AdminTeamSearchRequest extends FormRequest
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
            'order_by_column' => 'string|in:name,created_at',
            'order_by_type' => 'string|in:asc,desc',
            'size' => 'integer|max:100',
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
            'order_by_column.in' => 'The selected order by column should be created_at OR name only',
            'order_by_type.in' => 'The selected order by type should be asc OR desc only',
        ];
    }
}
