<?php

namespace App\Http\Requests\Periodo;

use App\Http\Requests\ApiFormRequest;

class CrearPeriodoRequest extends ApiFormRequest
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
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fecha_inicio.required' => 'La fecha de inicio del periodo es requerida.',
            'fecha_inicio.date' => 'La fecha de inicio del periodo debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin del periodo es requerida.',
            'fecha_fin.date' => 'La fecha de fin del periodo debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin del periodo debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
