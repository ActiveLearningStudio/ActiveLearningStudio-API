<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ActivityCreateRequest extends FormRequest
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
            'type' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'description' => 'string|max:500',
            'order' => 'integer|max:2147483647',
            'shared' => 'boolean',
            'h5p_content_id' => 'integer',
            'thumb_url' => 'string',
            'subject_id' => 'array|nullable',
            'subject_id.*' => 'nullable|integer|distinct|exists:subjects,id,deleted_at,NULL',
            'education_level_id' => 'array|nullable',
            'education_level_id.*' => 'nullable|integer|distinct|exists:education_levels,id,deleted_at,NULL',
            'author_tag_id' => 'array|nullable',
            'author_tag_id.*' => 'nullable|integer|distinct|exists:author_tags,id,deleted_at,NULL',
            'source_type' => 'nullable|string',
            'source_url' => 'nullable|string',
        ];
    }
}
