<?php

namespace App\Http\Requests\Stadium;

use App\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required',
            'sport' => 'string|required',
            'location' => 'string|required',
            'phone' => 'string|required'
        ];
    }
}
