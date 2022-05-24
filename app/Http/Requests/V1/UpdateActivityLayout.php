<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @bodyParam title string required Activity Item Title. Example: Audio
 * @bodyParam image image required Valid Image.
 * @bodyParam order int required At what order it should appear. Example: 1
 */
class UpdateActivityLayout extends FormRequest
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
        $activityLayout = $this->route('activity_layout');

        return [
            'title' =>  [
                'string',
                'max:255',
                Rule::unique('activity_layouts')->ignore($activityLayout->id)->where(function ($query) use($activityLayout) {
                    return $query->where('organization_id', $activityLayout->organization_id);
                })
            ],
            'description' => 'string',
            'order' => 'integer|max:2147483647',
            'type' => 'sometimes',
            'h5pLib' => 'sometimes',
            'image' => 'sometimes',
            'demo_activity_id' => 'required|string',
            'demo_video_id' => 'required|string',
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
        ];
    }
}
