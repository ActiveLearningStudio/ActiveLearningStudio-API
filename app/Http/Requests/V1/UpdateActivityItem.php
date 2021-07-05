<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam title string required Activity Item Title. Example: Audio
 * @bodyParam image image required Valid Image.
 * @bodyParam order int required At what order it should appear. Example: 1
 */
class UpdateActivityItem extends FormRequest
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
            'title' => 'string|max:255',
            'description' => 'string',
            'order' => 'integer',
            'activity_type_id' => 'required|integer|exists:activity_types,id',
            'type' => 'sometimes',
            'h5pLib' => 'sometimes',
            'image' => 'sometimes',
            'demo_activity_id' => 'required|string',
            'demo_video_id' => 'required|string',
        ];
    }
}
