<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class H5pContentByTemplateRequest extends FormRequest
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
            'activityTitle' => 'string|required',
            'library' => 'string|required',
            'slides' => 'array|min:1|required'
        ];
    }
}
