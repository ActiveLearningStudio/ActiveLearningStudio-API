<?php
/**
 * @author        Asim Sarwar
 * Description    Validation request class for update birghtcove api settings
 * class          UpdateKalturaAPISetting
 */
namespace App\Http\Requests\V1\Integration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKalturaAPISetting extends FormRequest
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
     * @bodyParam     partner_id required|string|max:50|unique. Example: 4186473
     * @bodyParam     sub_partner_id required|string|max:50|unique. Example: 418647300
     * @bodyParam     name nullable|string|max:100|unique. Example: Curriki Kaltura CMS
     * @bodyParam     email nullable|string|max:150|unique. Example: mike@curriki.org
     * @bodyParam     expiry nullable|string|max:150. Example: 86400
     * @bodyParam     session_type nullable|integer|max:1. Example: 2 or 0
     * @bodyParam     admin_secret required|max:255|unique. Example: secret key
     * @bodyParam     user_secret required|max:255|unique. Example: user key
     * @bodyParam     user_id required|exists:users,id. Example: 1
     * @bodyParam     organization_id required|exists:organizations,id. Example: 1
     * @return array
     */
    public function rules()
    {
        $id = $this->route('kaltura_api_setting');
        return [
            'partner_id' => 'required|string|max:50|unique:kaltura_api_settings,partner_id, ' . $id . ' ,id,deleted_at,NULL',
            'sub_partner_id' => 'nullable|string|max:50|unique:kaltura_api_settings,sub_partner_id, ' . $id . ' ,id,deleted_at,NULL',
            'name' => 'nullable|string|max:100|unique:kaltura_api_settings,name, ' . $id . ' ,id,deleted_at,NULL',
            'email' => 'nullable|string|max:150|unique:kaltura_api_settings,email, ' . $id . ' ,id,deleted_at,NULL',
            'expiry' => 'nullable|string|max:150',
            'session_type' => 'nullable|integer|max:2',
            'admin_secret' => 'required|string|max:255|unique:kaltura_api_settings,admin_secret, ' . $id . ' ,id,deleted_at,NULL',
            'user_secret' => 'nullable|string|max:255|unique:kaltura_api_settings,user_secret, ' . $id . ' ,id,deleted_at,NULL',
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id'
        ];
    }
}
