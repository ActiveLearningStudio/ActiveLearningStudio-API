<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationUpdate extends FormRequest
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
            'description' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'image' => 'image|max:1000',
            'member_id' => 'integer|exists:App\User,id',
            'parent_id' => 'integer|exists:App\Models\Organization,id'
        ];
    }
}
