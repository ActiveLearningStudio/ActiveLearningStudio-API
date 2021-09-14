<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class TeamInviteMembersRequest extends FormRequest
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
            'users' => 'required|array',
            'users.*.id'  => 'required|exists:App\User,id',
            'users.*.role_id'  => 'required|exists:App\Models\TeamRoleType,id',
            'users.*.email'  => 'required|email',
            'note' => 'string',
        ];
    }
}
