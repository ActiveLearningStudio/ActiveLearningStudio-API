<?php
/**
 * @author        Asim Sarwar
 * Description    Validation request class for create brightcove api settings
 * class          StoreBrightcoveAPISetting  
 */
namespace App\Http\Requests\V1\Integration;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrightcoveAPISetting extends FormRequest
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
     * @bodyParam     account_id required|string|max:50|unique. Example: 6282550302001
     * @bodyParam     account_name required|string|max:100|unique. Example: Curriki Brightcove CMS
     * @bodyParam     account_email required|string|max:150|unique. Example: mike@curriki.org
     * @bodyParam     client_id nullable|string|max:255|unique. Example: client key
     * @bodyParam     client_secret required_with:client_id|max:255|unique. Example: client secret key
     * @bodyParam     user_id required|exists:users,id. Example: 1
     * @bodyParam     organization_id required|exists:organizations,id. Example: 1
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|string|max:50|unique:brightcove_api_settings,account_id,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'account_name' => 'required|string|max:100|unique:brightcove_api_settings,account_name,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'account_email' => 'required|string|max:150|unique:brightcove_api_settings,account_email,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'client_id' => 'nullable|string|max:255|unique:brightcove_api_settings,client_id,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'client_secret' => 'required_with:client_id|max:255|unique:brightcove_api_settings,client_secret,NULL,id,deleted_at,NULL,user_id,' . request('user_id'),
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
            'css_path' => 'nullable|string'
        ];
    }
}
