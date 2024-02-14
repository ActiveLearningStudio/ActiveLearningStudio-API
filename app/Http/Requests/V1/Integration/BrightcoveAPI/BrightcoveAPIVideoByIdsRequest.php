<?php

namespace App\Http\Requests\V1\Integration\BrightcoveAPI;

use Illuminate\Foundation\Http\FormRequest;

class BrightcoveAPIVideoByIdsRequest extends FormRequest
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
            'id' => 'required|exists:brightcove_api_settings,id',
            'organization_id' => 'required|exists:organizations,id',
            'video_ids' => 'required|string'
        ];
    }
}
