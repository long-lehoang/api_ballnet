<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Current Password is required',
            'new_password.confirmed' => 'New password does not match',
            'new_password.required' => 'New Password is required'
        ];
    }
}