<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @bodyParam name string required Education Level name. Example: Special Education
 * @bodyParam order int required at what order it should appear. Example: 1
 */
class UpdateEducationLevelRequest extends FormRequest
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
        $educationLevel = $this->route('education_level');

        return [
            'name' =>  [
                'required',
                'string',
                'max:255',
                Rule::unique('education_levels')->ignore($educationLevel->id)->where(function ($query) use($educationLevel) {
                    return $query->where('organization_id', $educationLevel->organization_id);
                })
            ],
            'order' => 'integer|max:2147483647',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}
