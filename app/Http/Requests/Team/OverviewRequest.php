<?php

namespace App\Http\Requests\Team;

use App\Http\Requests\BaseRequest;

class LocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location' => 'string',
        ];
    }
}
