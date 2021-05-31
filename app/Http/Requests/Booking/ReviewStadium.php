<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\BaseRequest;

class ReviewStadium extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "rating" => "integer|required",
            "feedback" => "string|required",
        ];
    }
}
