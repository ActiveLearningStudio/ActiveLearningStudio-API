<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lowercase;
use Illuminate\Validation\Rule;

class OrganizationUpdate extends FormRequest
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
        $id = $this->route('organization');

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'domain' => ['required', 'alpha_dash', 'min:2', 'max:255', 'unique:organizations,domain,' . $id, new Lowercase],
            'image' => 'image|max:1000',
            'member_id' => 'integer|exists:App\User,id',
            'parent_id' => [
                'integer',
                Rule::exists('organizations', 'id')->where(function ($query) use ($id) {
                    $query->where('id', '<>', $id);
                }),
            ],
        ];
    }
}
