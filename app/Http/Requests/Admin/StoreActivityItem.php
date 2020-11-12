<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam title string required Activity Item Title. Example: Math
 * @bodyParam description text required Short description of activity item. Example: Create Math activities.
 * @bodyParam demo_activity_id int Demo Activity Id. Example: 1
 * @bodyParam demo_video_id int Demo Video ID. Example: 1
 * @bodyParam image image required Valid Image.
 * @bodyParam order int required At what order it should appear. Example: 1
 * @bodyParam type string required H5P OR Immersive Reader. Example: h5p
 * @bodyParam activity_type_id int required Integer ID of parent activity type. Example: 1
 * @bodyParam h5pLib string required H5P activity name & version. Example: H5P.DocumentsUpload 1.0
 */
class StoreActivityItem extends FormRequest
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
            'title' => 'required|max:255',
            'description' => 'required',
            'demo_activity_id' => 'max:255',
            'demo_video_id' => 'max:255',
            'image' => 'image|max:1000',
            'order' => 'required|integer',
            'type' => 'required',
            'activity_type_id' => 'required|exists:activity_types,id',
            'h5pLib' => 'required',
        ];
    }
}
