<?php

namespace App\Http\Requests\Ingreso;

use App\Http\Requests\ApiFormRequest;

class EditarIngresoRequest extends ApiFormRequest
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
            'fecha' => 'required|date',
            'monto' => ['bail', 'required', 'regex:/^\d{1,16}(\.\d{1,2})?$/'],
            'nombre' => 'required|string|max:80'
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha del ingreso es requerida.',
            'fecha.date' => 'La fecha del ingreso debe ser una fecha válida.',
            'monto.required' => 'El monto del ingreso es requerido.',
            'monto.regex' => 'El monto del ingreso debe tener un formato válido (máximo 16 dígitos y 2 decimales).',
            'nombre.required' => 'El nombre del ingreso es requerido.',
            'nombre.string' => 'El nombre del ingreso debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del ingreso no debe exceder los 80 caracteres.',
        ];
    }
}
