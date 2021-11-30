<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationProjectRequest extends FormRequest
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
            'query' => 'string',
            'indexing' => 'in:0,1,2,3',
            'exclude_starter' => 'in:true',
            'starter_project' => 'in:true,false',
        ];
    }

    public function messages()
    {
        return [
            'indexing.in' => 'Indexing should be 1,2 or 3',
            'exclude_starter.in' => 'Indexing should be true',
            'starter_project.in' => 'Indexing should be true or false',
        ];
    }
}
