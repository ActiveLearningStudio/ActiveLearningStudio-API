<?php

namespace App\Http\Requests\V1\C2E\Publisher;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublisherRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
            'key' => 'required|string|max:255',
            'project_visibility' => 'nullable|boolean',
            'playlist_visibility' => 'nullable|boolean',
            'activity_visibility' => 'nullable|boolean',
        ];
    }
}
