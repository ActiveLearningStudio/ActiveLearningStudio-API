<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StandAloneActivityCreateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'description' => 'string|max:500',
            'order' => 'integer|max:2147483647',
            'shared' => 'boolean',
            'h5p_content_id' => 'required|integer',
            'thumb_url' => 'string',
            'subject_id' => 'nullable|string',
            'education_level_id' => 'nullable|string',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}