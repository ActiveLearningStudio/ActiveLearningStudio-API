<?php

namespace App\Http\Requests\V1\CurrikiGo;

use Illuminate\Foundation\Http\FormRequest;

class PublishPlaylistRequest extends FormRequest
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
            'setting_id' => 'required|exists:lms_settings,id',
            'counter' => 'sometimes|integer',
            'publisher_org' => 'int|nullable',
            'creation_type' => 'required|in:modules,assignments',
            'canvas_course_id' => 'required|integer'
        ];
    }
}
