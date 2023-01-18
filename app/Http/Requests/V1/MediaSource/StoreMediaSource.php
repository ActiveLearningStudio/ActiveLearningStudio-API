<?php

namespace App\Http\Requests\V1\MediaSource;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Description    Validation request class for create media sources
 * class          StoreMediaSource
 */
class StoreMediaSource extends FormRequest
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
        $mediaType = request('media_type');
        return [
            'name' => 'required|string|max:255|unique:media_sources,name,NULL,id,deleted_at,NULL,media_type,' . $mediaType,
            'media_type' => 'required|in:Video,Image'
        ];
    }
}
