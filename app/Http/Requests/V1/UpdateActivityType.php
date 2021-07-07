<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam title string required Activity Item Title. Example: Audio
 * @bodyParam image image required Valid Image.
 * @bodyParam order int required At what order it should appear. Example: 1
 */
class UpdateActivityType extends FormRequest
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
            'title' => 'sometimes|max:255',
            'image' => 'sometimes',
            'order' => 'sometimes|integer'
        ];
    }
}
