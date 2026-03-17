<?php

namespace App\Http\Requests\Gasto;

use App\Http\Requests\ApiFormRequest;
use App\Models\Gasto;

class CancelarGastoRequest extends ApiFormRequest
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
            'estatus' => 'required|integer|in:' . Gasto::ESTATUS_CANCELADO,
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'estatus.required' => 'El estatus del gasto es requerido.',
            'estatus.integer' => 'El estatus del gasto debe ser un número entero.',
            'estatus.in' => 'El estatus del gasto debe ser (' . Gasto::ESTATUS_DESCRIPCIONES[Gasto::ESTATUS_CANCELADO] . ').',
        ];
    }
}
