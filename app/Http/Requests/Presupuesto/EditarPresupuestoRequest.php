<?php

namespace App\Http\Requests\Presupuesto;

use App\Http\Requests\ApiFormRequest;

class EditarPresupuestoRequest extends ApiFormRequest
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
            'monto' => ['bail', 'required', 'regex:/^\d{1,16}(\.\d{1,2})?$/'],
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'monto.required' => 'El monto del presupuesto es requerido.',
            'monto.regex' => 'El monto del presupuesto debe tener un formato válido (máximo 16 dígitos y 2 decimales).',
        ];
    }
}
