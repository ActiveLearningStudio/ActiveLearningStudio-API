<?php

namespace App\Http\Requests\V1\C2E\MediaCatalog\SrtContent;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author        Asim Sarwar
 * Description    Validation request class for update media catalog srt contents
 * class          UpdateMediaCatalogSrtContentRequest
 */
class UpdateMediaCatalogSrtContentRequest extends FormRequest
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
        $srtContent = $this->route('id');
        $id = $srtContent->id;
        return [
            'video_id' => 'required|unique:media_catalog_srt_contents,video_id, ' . $id . ' ,id,deleted_at,NULL',
            
            'content' => 'required'
        ];
    }

    /**
     * Set the custom validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
