<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'organization_id' => 'required|integer|exists:App\Models\Organization,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'users' => 'nullable|array',
            'users.*.id'  => 'required|exists:App\User,id',
            'users.*.role_id'  => 'required|exists:App\Models\TeamRoleType,id',
            'users.*.email'  => 'required|email',
            'projects' => 'nullable|array|max:1|exists:App\Models\Project,id,deleted_at,NULL',
            'note' => 'string|max:200',
            'noovo_group_title' => 'string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'team name',
        ];
    }
}
