<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required name of the group. Example: Public
 * @bodyParam description string required Description of the group. Example: This is informative group
 * @bodyParam status integer required status. Example: active or in-active
 */
class StoreGroup extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'status' => 'required|in:0,1',
        ];
    }
}
