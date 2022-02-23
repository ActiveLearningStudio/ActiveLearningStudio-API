<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

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
        $educationlevel = $this->route('education_level');

        return [
            'name' => 'required|string|max:255|unique:education_levels,name,' . $educationlevel->id,
            'order' => 'integer|max:2147483647',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}
