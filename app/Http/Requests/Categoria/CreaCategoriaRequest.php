<?php

namespace App\Http\Requests\Categoria;

use App\Http\Requests\ApiFormRequest;

class CreaCategoriaRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Si no se proporciona la propiedad correspondiente, lo establecemos en un valor predeterminado para evitar errores de validación.
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'presupuesto_base' => $this->presupuesto_base ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:80',
            'descripcion' => 'nullable|string|max:150',
            'presupuesto_base' => 'numeric|min:0|max:9999999999999999.99',
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe exceder los 80 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no debe exceder los 150 caracteres.',
            'presupuesto_base.numeric' => 'El presupuesto base debe ser un número.',
            'presupuesto_base.min' => 'El presupuesto base no puede ser negativo.',
            'presupuesto_base.max' => 'El presupuesto base no puede exceder 9999999999999999.99.',
        ];
    }
}
