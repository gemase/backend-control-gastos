<?php

namespace App\Http\Requests\Gasto;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CrearGastoRequest extends ApiFormRequest
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
        $usuarioId = $this->user()?->id;

        return [
            'descripcion' => 'nullable|string|max:80',
            'fecha' => 'required|date',
            'monto' => ['bail', 'required', 'regex:/^\d{1,16}(\.\d{1,2})?$/'],
            'id_periodo' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('periodos', 'id')->where('creado_por', $usuarioId),
            ],
            'id_categoria' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('categorias', 'id')->where('creado_por', $usuarioId),
            ],
            'id_forma_pago' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('formas_pago', 'id')->where('creado_por', $usuarioId),
            ],
        ];
    }

    /**
     * Devuelve los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha del gasto es requerida.',
            'fecha.date' => 'La fecha del gasto debe ser una fecha válida.',
            'monto.required' => 'El monto del gasto es requerido.',
            'monto.regex' => 'El monto del gasto debe tener un formato válido (máximo 16 dígitos y 2 decimales).',
            'descripcion.string' => 'La descripción del gasto debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción del gasto no debe exceder los 80 caracteres.',
            'id_periodo.exists' => 'El periodo seleccionado no existe.',
            'id_periodo.integer' => 'El periodo seleccionado debe ser un número entero.',
            'id_periodo.min' => 'El periodo seleccionado debe ser un número mayor o igual a 1.',
            'id_categoria.required' => 'La categoría del gasto es requerida.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'id_categoria.integer' => 'La categoría seleccionada debe ser un número entero.',
            'id_categoria.min' => 'La categoría seleccionada debe ser un número mayor o igual a 1.',
            'id_forma_pago.required' => 'La forma de pago del gasto es requerida.',
            'id_forma_pago.exists' => 'La forma de pago seleccionada no existe.',
            'id_forma_pago.integer' => 'La forma de pago seleccionada debe ser un número entero.',
            'id_forma_pago.min' => 'La forma de pago seleccionada debe ser un número mayor o igual a 1.',
        ];
    }
}
