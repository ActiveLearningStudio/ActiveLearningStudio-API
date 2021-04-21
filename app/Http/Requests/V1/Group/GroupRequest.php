<?php

namespace App\Http\Requests\V1\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
            'name' => 'required|string|max:80',
            'description' => 'required|string|max:1000',
            'users' => 'required|array',
            'projects' => 'array|exists:App\Models\Project,id',
            'note' => 'string|min:0',
        ];
    }
}
