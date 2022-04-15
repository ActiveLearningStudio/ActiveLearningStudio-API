<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @bodyParam name string required Education Level name. Example: Special Education
 * @bodyParam order int required At what order it should appear. Example: 1
 */
class StoreSubjectRequest extends FormRequest
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
        $organizationId = request('organization_id');

        return [
            'name' =>  [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->where(function ($query) use($organizationId) {
                    return $query->where('organization_id', $organizationId);
                })
            ],
            'order' => 'integer|max:2147483647',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}
