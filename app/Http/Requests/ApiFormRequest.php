<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $primerMensaje = collect($validator->errors()->all())->first();
        $response = response()->json([
            'status' => false,
            'error' => [
                'message' => $primerMensaje,
            ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
