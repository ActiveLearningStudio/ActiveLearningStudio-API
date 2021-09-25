<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class TeamAddProjectRequest extends FormRequest
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
            'ids' => 'required|array|exists:App\Models\Project,id,deleted_at,NULL',
        ];
    }

    /**
     * Get the validation message that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ids.exists' => 'Some of given project Ids are invalid or deleted.',
            'ids.required' => 'Please select a project.',
        ];
    }
}
