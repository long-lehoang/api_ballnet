<?php

namespace App\Http\Requests\Match;

use App\Http\Requests\BaseRequest;

class UpdateMatchRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "private" => "string",
            'type' => "string",
            "location" => "string",
            "time" => "string",
        ];
    }
}
