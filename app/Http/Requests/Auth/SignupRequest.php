<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class SignupRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'name' => 'required',
            'email' => 'required|email:rfc,dns'
        ];
    }
}
