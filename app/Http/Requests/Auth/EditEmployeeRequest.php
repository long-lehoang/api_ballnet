<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\BaseRequest;

class EditEmployeeRequest extends BaseRequest
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
            'last_name' => 'string|max:50',
            'first_name' => 'string|max:50',
            'tel'=>'numeric',
            'address'=>'string',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please enter the email!',
            'last_name.max:50' => 'The last name may not be greater than 50 characters',
            'first_name.max:50' => 'The first name may not be greater than 50 characters',
            'tel.numeric'=>'Please enter the number',
        ];
    }
}