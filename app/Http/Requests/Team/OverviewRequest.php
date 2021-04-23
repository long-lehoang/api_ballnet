<?php

namespace App\Http\Requests\Team;

use App\Http\Requests\BaseRequest;

class OverviewRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'overview' => 'string',
        ];
    }
}
