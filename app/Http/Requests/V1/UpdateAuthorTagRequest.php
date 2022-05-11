<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' =>  [
                'required',
                'string',
                'max:255',
                Rule::unique('author_tags')->ignore($authorTag->id)->where(function ($query) use($authorTag) {
                    return $query->where('organization_id', $authorTag->organization_id);
                })
            ],
            'order' => 'integer|max:2147483647',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}
