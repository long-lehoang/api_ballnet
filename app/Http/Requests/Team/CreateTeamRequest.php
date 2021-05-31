<?php

namespace App\Http\Requests\Team;

use App\Http\Requests\BaseRequest;

class CreateTeamRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required',
            'location' => 'string|required',
            'sport' => 'string|required',
        ];
    }
}
