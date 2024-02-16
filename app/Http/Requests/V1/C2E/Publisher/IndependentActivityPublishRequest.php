<?php

namespace App\Http\Requests\V1\C2E\Publisher;

use Illuminate\Foundation\Http\FormRequest;

class IndependentActivityPublishRequest extends FormRequest
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
            'store_id' => 'required|string',
        ];
    }
}
