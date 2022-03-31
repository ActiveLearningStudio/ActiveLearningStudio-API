<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SearchAuthorTagRequest extends FormRequest
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
            'size' => 'integer|max:255|nullable',
            'query' => 'string|max:255|nullable',
            'order_by_column' => 'nullable|in:order,name',
            'order_by_type' => 'nullable|in:asc,desc,ASC,DESC',
        ];
    }
}
