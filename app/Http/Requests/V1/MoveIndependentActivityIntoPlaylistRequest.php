<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MoveIndependentActivityIntoPlaylistRequest extends FormRequest
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
            'independentActivityIds' => 'required|array',
            'independentActivityIds.*' => 'required|int|exists:independent_activities,id,deleted_at,NULL,shared,false,organization_visibility_type_id,1'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'independentActivityIds.*.exists' => 'Activities that are moving to projects should have share disabled and library preference should be private.'
        ];
    }
}
