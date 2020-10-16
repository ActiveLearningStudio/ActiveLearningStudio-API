<?php

namespace App\Http\Requests\V1;

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
            'indexing' => 'array|in:null,1,2,3',
            'startDate' => 'date',
            'endDate' => 'date',
            'userIds' => 'array|exists:App\User,id',
            'h5pLibraries' => 'array|exists:App\Models\ActivityItem,h5pLib',
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
