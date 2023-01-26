<?php

namespace App\Http\Requests\V1\MediaSource;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Description    Validation request class for update media sources
 * class          UpdateMediaSource
 */
class UpdateMediaSourceRequest extends FormRequest
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
        $id = $this->route('media_source_setting');
        $mediaType = request('media_type');
        return [
            'name' => 'required|string|max:255|unique:media_sources,name, ' . $id . ' ,id,deleted_at,NULL,media_type,' . $mediaType,
            'media_type' => 'required|in:Video,Image'
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
            'name.unique' => 'The name has already been taken with the mentioned media_type.'
        ];
    }
}
