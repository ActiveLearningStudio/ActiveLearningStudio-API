<?php

namespace App\Http\Requests\V1\CurrikiGo;

use Illuminate\Foundation\Http\FormRequest;

class TeamsSearchRequest extends FormRequest
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
            'user_email' => 'string|required|max:255',
            'lti_client_id' => 'string|required',
        ];
    }
}
