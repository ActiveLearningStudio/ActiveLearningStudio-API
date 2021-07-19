<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuborganizationDeleteUserRequest extends FormRequest
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
        $suborganization = $this->route('suborganization');

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:App\User,id,id,!' . $this->user()->id,
                Rule::exists('organization_user_roles')->where(function ($query) use ($suborganization) {
                    return $query->where('organization_id', $suborganization->id);
                })
            ],
            'preserve_data' => 'boolean'
        ];
    }
}
