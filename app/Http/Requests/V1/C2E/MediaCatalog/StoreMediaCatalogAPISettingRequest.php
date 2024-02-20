<?php

namespace App\Http\Requests\V1\C2E\MediaCatalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author        Asim Sarwar
 * Description    Validation request class for create media catalog api settings
 * class          StoreMediaCatalogAPISettingRequest  
 */
class StoreMediaCatalogAPISettingRequest extends FormRequest
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
        $orgId = $suborganization->id;
        $mediaSourceId = request('media_source_id');
        return [
            'name' => 'required|string|max:255|unique:media_catalog_api_settings,name,NULL,id,deleted_at,NULL,organization_id,' . $orgId,
            
            'api_setting_id' => 'nullable|string|max:255|unique:media_catalog_api_settings,api_setting_id,NULL,id,deleted_at,NULL,organization_id,' . $orgId,

            'email' => 'nullable|string|max:255|unique:media_catalog_api_settings,email,NULL,id,deleted_at,NULL,organization_id,' . $orgId .' ,media_source_id,' . $mediaSourceId,

            'url' => 'nullable|url|max:255|unique:media_catalog_api_settings,url,NULL,id,deleted_at,NULL,organization_id,' . $orgId,

            'description' => 'nullable|string|max:1000',

            'media_source_id' => 'required|exists:media_sources,id|unique:media_catalog_api_settings,media_source_id,NULL,id,deleted_at,NULL,organization_id,' . $orgId,

            'client_key' => 'nullable|string|max:255|unique:media_catalog_api_settings,client_key,NULL,id,deleted_at,NULL,organization_id,' . $orgId,

            'secret_key' => 'required_with:client_key|max:255|unique:media_catalog_api_settings,secret_key,NULL,id,deleted_at,NULL,organization_id,' . $orgId,

            'custom_metadata' => 'nullable|string|max:500'
        ];
    }

    /**
     * Set the custom validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'media_source_id.required' => 'The Source Type field is required.',
            'media_source_id.exists' => 'The selected Source Type is invalid.',
            'media_source_id.unique' => 'The Source Type has already been taken.'
        ];
    }
}
