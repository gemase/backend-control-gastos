<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaPago\CrearFormaPagoRequest;
use App\Http\Requests\FormaPago\EditarFormaPagoRequest;
use App\Http\Requests\FormaPago\EditarEstatusFormaPagoRequest;
use Illuminate\Http\Response;
use App\Models\FormaPago;
use Exception;
use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    /**
     * Crear una nueva forma de pago.
     * @param CrearFormaPagoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearFormaPago(CrearFormaPagoRequest $request)
    {
        try {
            $datosValidados = $request->validated();
            $datosValidados['creado_por'] = $request->user()->id;

            //Se valida que el nombre y el usuario sean únicos.
            $existe = FormaPago::where('creado_por', $datosValidados['creado_por'])
                ->where('nombre', $datosValidados['nombre'])->exists();

            if ($existe) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El nombre de forma de pago ya existe.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $formaPago = FormaPago::create($datosValidados);
            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar una forma de pago.
     * @param EditarFormaPagoRequest $request
     * @param int $id Identificador de la forma de pago
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarFormaPago(EditarFormaPagoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $formaPago = FormaPago::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que la forma de pago exista.
            if (!$formaPago) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'La forma de pago no fue encontrada.']
                ], Response::HTTP_NOT_FOUND);
            }

            //Se valida que el nombre y el usuario sean únicos.
            $existe = FormaPago::where('creado_por', $id_usuario)
                ->where('nombre', $datosValidados['nombre'])
                ->where('id', '!=', $id)->exists();

            if ($existe) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El nombre de forma de pago ya existe.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $formaPago->update($datosValidados);

            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar el estatus de una forma de pago.
     * @param EditarEstatusFormaPagoRequest $request
     * @param int $id Identificador de la forma de pago
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstatusFormaPago(EditarEstatusFormaPagoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $formaPago = FormaPago::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que la forma de pago exista.
            if (!$formaPago) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'La forma de pago no fue encontrada.']
                ], Response::HTTP_NOT_FOUND);
            }

            $formaPago->update($datosValidados);

            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve todas las formas de pago.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarFormasPago()
    {
        try {
            $formasPago = FormaPago::all();
            return response()->json(['status' => true, 'data' => $formasPago], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve una forma de pago de manera individual.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarFormaPagoPorId(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;
            $formaPago = FormaPago::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$formaPago) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'La forma de pago no fue encontrada.']
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
