<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class IndependentActivityForDeeplink extends FormRequest
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
            'user_email' => 'required|email',
            'lti_client_id' => 'required',
            'query' => 'string',
            'size' => 'integer|max:100'
        ];
    }
}
