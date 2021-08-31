<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SearchPublicRequest extends FormRequest
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
            'query' => 'string|max:255',
            'organization_id' => 'integer|exists:App\Models\Organization,id',
            'negativeQuery' => 'string|max:255',
            'indexing' => 'array|in:' . config('constants.indexing-options'),
            'startDate' => 'date',
            'endDate' => 'date|after_or_equal:startDate',
            'userIds' => 'array|exists:App\User,id',
            'author' => 'string|max:255',
            'h5pLibraries' => 'array|exists:App\Models\ActivityItem,h5pLib',
            'subjectIds' => 'array',
            'educationLevelIds' => 'array',
            'model' => 'in:activities,playlists,projects',
            'sort' => 'in:created_at',
            'order' => 'in:asc,desc',
            'from' => 'integer',
            'size' => 'integer'
        ];
    }
}
