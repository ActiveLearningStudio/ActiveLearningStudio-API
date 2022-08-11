<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SuborganizationUpdateMediaSource extends FormRequest
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
            'media_source_ids' => 'nullable|array',
            'media_source_ids.*.media_source_id' => 'required|exists:App\Models\MediaSource,id',
            'media_source_ids.*.h5p_library' => 'nullable',
        ];
    }

}
