<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam lms_url url required Valid LMS URL. Example: https://google.com
 * @bodyParam lms_access_token string required Min 20 characters LMS Access Token. Example: abcafdgd343asgretgdasgadsfsdfdasgdagsadf
 * @bodyParam site_name string required Site Name. Example: Moodle Curriki
 * @bodyParam lti_client_id string LTI Client ID for reference. Example: 1
 * @bodyParam lms_login_id string LMS Login ID for reference. Example: 1
 * @bodyParam user_id int required Valid ID of existing user. Example: 1
 * @bodyParam lms_name string LMS name for which setting is being configured. Example: Moodle
 * @bodyParam lms_access_key string Access key for LMS. Example: fdaskfasdkjghadskljgh54r325
 * @bodyParam lms_access_secret string required Secret key is required if Access Key is provided. Example: fasdjhjke4wh54354326
 * @bodyParam description text required Brief description. Example: Create LMS Setting for providing access to Moodle.
 */
class StoreLmsSetting extends FormRequest
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
            'lms_url' => 'required|url|max:255',
            'lms_access_token' => 'required|min:20|max:255',
            'site_name' => 'required|string|max:255',
            'lti_client_id' => 'nullable|string|max:255',
            'lms_login_id' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'lms_name' => 'nullable|string|max:255',
            'lms_access_key' => 'nullable|string|max:255',
            'lms_access_secret' => 'required_with:lms_access_key|max:255',
            'description' => 'nullable|max:255',
        ];
    }
}
