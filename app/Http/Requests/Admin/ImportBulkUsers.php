<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam import_file file required The import file must be a file of type: CSV.
 */
class ImportBulkUsers extends FormRequest
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
            'import_file' => 'required|mimes:csv,txt|max:30000',
        ];
    }

    /**
     * Custom messages
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'import_file.mimes' => 'The import file must be a file of type: CSV.',
        ];
    }
}
