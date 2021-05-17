<?php

namespace App\Http\Requests\Match;

use App\Http\Requests\BaseRequest;

class ReviewMatch extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "result" => "string|required",
            "match_id" => "integer|required",
            "team_id" => "integer|required",
            "rating_team" => "integer|required",
            "members" => "string|required",
        ];
    }
}
