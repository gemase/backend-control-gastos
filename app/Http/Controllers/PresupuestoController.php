<?php

namespace App\Http\Controllers;

use App\Http\Requests\Presupuesto\EditarPresupuestoRequest;
use App\Models\Presupuesto;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    /**
     * Editar el monto de un presupuesto.
     * @param EditarPresupuestoRequest $request
     * @param int $id Identificador del presupuesto
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarPresupuesto(EditarPresupuestoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $presupuesto = Presupuesto::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$presupuesto) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El presupuesto no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            $presupuesto->update($datosValidados);
            $presupuesto->load('periodo', 'categoria');

            return response()->json(['status' => true, 'data' => $presupuesto], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve los presupuestos del usuario autenticado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarPresupuestos(Request $request)
    {
        try {
            $id_usuario = $request->user()->id;
            $presupuestos = Presupuesto::with('periodo', 'categoria')
                ->where('creado_por', $id_usuario)
                ->get();

            return response()->json(['status' => true, 'data' => $presupuestos], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve un presupuesto de manera individual.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarPresupuestoPorId(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;
            $presupuesto = Presupuesto::with('periodo', 'categoria')
                ->where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$presupuesto) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El presupuesto no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'data' => $presupuesto], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
