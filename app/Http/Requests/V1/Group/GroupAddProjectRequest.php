<?php

namespace App\Http\Requests\V1\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupAddProjectRequest extends FormRequest
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
            'ids' => 'required|array|exists:App\Models\Project,id',
        ];
    }
}
