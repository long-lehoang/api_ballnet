<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseRequest;

class CommentRequest extends BaseRequest
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
            "comment" => "required"
        ];
    }
}