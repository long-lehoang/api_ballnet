<?php

namespace App\Http\Requests\Match;

use App\Http\Requests\BaseRequest;

class LeaveRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "team_id" => "required",
        ];
    }
}
