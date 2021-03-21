<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class EditProfileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [     
            'email' => 'string|email',
            'name' => 'string|max:50',
            // 'first_name' => 'string|max:50',
            // 'tel'=>'numeric',
            // 'address'=>'string',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please enter the email!',
            'name.max:50' => 'The name may not be greater than 50 characters',
            // 'first_name.max:50' => 'The first name may not be greater than 50 characters',
            // 'tel.numeric'=>'Please enter the number',
        ];
    }
}