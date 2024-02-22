<?php

namespace App\Http\Requests\V1\C2E\MediaCatalog\BrightcoveAPI;

use Illuminate\Foundation\Http\FormRequest;

class BrightcoveAPIPlaylistVideosRequest extends FormRequest
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
            'palylist_id' => 'required|string',
            'query_param' => 'nullable|string'
        ];
    }
}
