<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Kaltura\Client\Type\Rule;

class GetProjectsRequest extends FormRequest
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
            'size' => 'integer',
            'order_by_column' => 'in:id,name,order.status,created_at',
            'order_by_type' => 'in:asc,desc,ASC,DESC'
        ];
    }
}
