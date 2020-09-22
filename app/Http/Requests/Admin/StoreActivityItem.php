<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
            'description' => 'required|max:255',
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
