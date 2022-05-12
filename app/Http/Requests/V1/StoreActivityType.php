<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @bodyParam title string required Activity Item Title. Example: Audio
 * @bodyParam image image required Valid Image.
 * @bodyParam order int required At what order it should appear. Example: 1
 */
class StoreActivityType extends FormRequest
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
            'title' =>  [
                'required',
                'string',
                'max:255',
                Rule::unique('activity_types')->where(function ($query) use($organizationId) {
                    return $query->where('organization_id', $organizationId);
                })
            ],
            'image' => 'required',
            'order' => 'integer|max:2147483647',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}
