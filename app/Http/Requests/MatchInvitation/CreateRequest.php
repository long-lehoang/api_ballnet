<?php

namespace App\Http\Requests\MatchInvitation;

use App\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "match_id" => "required",  
        ];
    }
}
