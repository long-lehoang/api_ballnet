<?php

namespace App\Http\Requests\Team;

use App\Http\Requests\BaseRequest;

class KickRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'member_id' => 'required|integer',
        ];
    }
}
