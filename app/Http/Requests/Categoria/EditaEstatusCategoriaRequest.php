<?php

namespace App\Http\Requests\Categoria;

use App\Http\Requests\ApiFormRequest;

class EditaEstatusCategoriaRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estatus' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'estatus.required' => 'El estatus es requerido.',
            'estatus.integer' => 'El estatus debe ser un número entero.',
            'estatus.in' => 'El estatus debe ser 0 o 1.',
        ];
    }
}
