<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'       => 'required',
            'password'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'username is required',
            'password.required' => 'password is required',
        ];
    }
}
