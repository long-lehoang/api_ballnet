<?php

namespace App\Http\Requests\MatchJoining;

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
            "match_id" => "required|integer",
            "team_id" => "required|integer",
            "player_id" => "integer",
        ];
    }
}
