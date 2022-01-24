<?php
/**
 * @author        Asim Sarwar
 * Description    Validation request class for update birghtcove api settings
 * class          UpdateBrightcoveAPISetting
 */
namespace App\Http\Requests\V1\Integration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrightcoveAPISetting extends FormRequest
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
     * @bodyParam     client_id nullable|string|max:255. Example: client key
     * @bodyParam     client_secret required_with:client_id|max:255. Example: client secret key
     * @bodyParam     user_id required|exists:users,id. Example: 1
     * @bodyParam     organization_id required|exists:organizations,id. Example: 1
     * @return array
     */
    public function rules()
    {
        $id = $this->route('brightcove_api_setting');
        return [
            'account_id' => 'required|string|max:50|unique:brightcove_api_settings,account_id, ' . $id . ' ,id,deleted_at,NULL,organization_id,' . request('organization_id'),
            'account_name' => 'required|string|max:100|unique:brightcove_api_settings,account_name, ' . $id . ' ,id,deleted_at,NULL,organization_id,' . request('organization_id'),
            'account_email' => 'required|string|max:150|unique:brightcove_api_settings,account_email, ' . $id . ' ,id,deleted_at,NULL,organization_id,' . request('organization_id'),
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'required_with:client_id|max:255',
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id'
        ];
    }
}
