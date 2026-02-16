<?php

namespace App\Http\Requests\FormaPago;

use App\Http\Requests\ApiFormRequest;

class CrearFormaPagoRequest extends ApiFormRequest
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
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string|max:150',
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de forma de pago es requerido.',
            'nombre.string' => 'El nombre de forma de pago debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de forma de pago no debe exceder los 80 caracteres.',
            'descripcion.string' => 'La descripción de forma de pago debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción de forma de pago no debe exceder los 150 caracteres.',
        ];
    }
}
