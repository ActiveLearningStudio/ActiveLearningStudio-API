<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Author Tag name. Example: Audio
 * @bodyParam order int required at what order it should appear. Example: 1
 */
class UpdateAuthorTagRequest extends FormRequest
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
        $authorTag = $this->route('author_tag');

        return [
            'name' => 'required|string|max:255|unique:author_tags,name,'.$authorTag->id,
            'order' => 'integer|max:2147483647',
        ];
    }
}
