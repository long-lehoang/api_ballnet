<?php

namespace App\Http\Requests\TeamRequest;

use App\Http\Requests\BaseRequest;

class InviteRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_id' => 'required|integer',
            'user_id' => 'required|integer'
        ];
    }
}
