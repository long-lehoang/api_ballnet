<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "members" => "string|required",
            "name" => "string|required",
            
        ];
    }
}
