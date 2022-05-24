<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationIndependentActivityRequest extends FormRequest
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
            'query' => 'string',
            'indexing' => 'in:0,1,2,3',
            'shared' => 'boolean',
            'created_from' => 'date_format:Y-m-d',
            'created_to' => 'date_format:Y-m-d',
            'updated_from' => 'date_format:Y-m-d',
            'updated_to' => 'date_format:Y-m-d',
            'author_id' => 'integer|exists:users,id',
            'order_by_column' => 'string|in:name,created_at',
            'order_by_type' => 'string|in:asc,desc',
            'size' => 'integer|max:100',
        ];
    }

    public function messages()
    {
        return [
            'indexing.in' => 'Indexing should be 0, 1, 2 or 3',
            'shared.boolean' => 'Shared status should be 1 or 0',
            'order_by_column.in' => 'The selected order by column should be name OR created_at only',
            'order_by_type.in' => 'The selected order by type should be asc OR desc only',
        ];
    }
}
