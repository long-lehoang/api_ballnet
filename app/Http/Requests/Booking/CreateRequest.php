<?php

namespace App\Http\Requests\Booking;

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
            "match_id" => "integer|required",
            "stadium_id" => "integer|required",
        ];
    }
}
