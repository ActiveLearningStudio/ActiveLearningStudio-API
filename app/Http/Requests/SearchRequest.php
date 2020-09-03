<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query' => 'required|string|max:255',
            'negativeQuery' => 'string|max:255',
            'userIds' => 'array|exists:App\User,id',
            'subjectIds' => 'array|exists:App\Models\Activity,subject_id',
            'educationLevelIds' => 'array|exists:App\Models\Activity,education_level_id',
            'model' => 'in:activities,playlists,projects',
            'sort' => 'in:created_at',
            'order' => 'in:asc,desc',
            'from' => 'integer',
            'size' => 'integer'
        ];
    }
}
