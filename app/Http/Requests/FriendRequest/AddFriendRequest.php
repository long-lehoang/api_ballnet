<?php

namespace App\Http\Requests\FriendRequest;

use App\Http\Requests\BaseRequest;

class AddFriendRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "username" => "required"
        ];
    }
}
