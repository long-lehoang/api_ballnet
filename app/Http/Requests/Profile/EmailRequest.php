<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseRequest;

class EmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}