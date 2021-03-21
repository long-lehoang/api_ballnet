<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [     
            'password' => 'required|string',
            'email' => 'required|string|email',
        ];
    }
}