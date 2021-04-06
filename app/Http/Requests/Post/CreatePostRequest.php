<?php

namespace App\Http\Requests;
use App\Http\Requests\BaseRequest;

class CreatePostRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'content' => 'required',
            'private' => 'required',
            // 'images' => 'image'
        ];
    }
}
