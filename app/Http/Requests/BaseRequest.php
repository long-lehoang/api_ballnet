<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    abstract public function rules();
    
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator));
        throw new HttpResponseException(response()->json(
            [
                'success'=>false,
                'message' => $validator->getMessageBag()->toArray(),
                'code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
