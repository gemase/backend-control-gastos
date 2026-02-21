<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ingreso\CrearIngresoRequest;
use App\Http\Requests\Ingreso\EditarIngresoRequest;
use App\Http\Requests\Ingreso\EditarEstatusIngresoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Ingreso;
use App\Models\Periodo;
use Exception;

class IngresoController extends Controller
{
    /**
     * Crear un nuevo ingreso.
     * @param CrearIngresoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearIngreso(CrearIngresoRequest $request)
    {
        try {
            $datosValidados = $request->validated();
            $datosValidados['creado_por'] = $request->user()->id;

            $periodo = Periodo::where('creado_por', $datosValidados['creado_por'])
                ->where('fecha_inicio', '<=', $datosValidados['fecha'])
                ->where('fecha_fin', '>=', $datosValidados['fecha'])
                ->first();

            if ($periodo) {
                $datosValidados['id_periodo'] = $periodo->id;
                $datosValidados['estatus'] = Ingreso::ESTATUS_ACTIVO;
            } else {
                $datosValidados['id_periodo'] = null;
                $datosValidados['estatus'] = Ingreso::ESTATUS_SIN_PERIODO;
            }

            $ingreso = Ingreso::create($datosValidados);
            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar un ingreso.
     * @param EditarIngresoRequest $request
     * @param int $id Identificador del ingreso
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarIngreso(EditarIngresoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $ingreso = Ingreso::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que el ingreso exista.
            if (!$ingreso) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El ingreso no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            //Se valida que el ingreso no esté inactivo.
            if ($ingreso->estatus == Ingreso::ESTATUS_INACTIVO) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'No se puede editar un ingreso inactivo.']
                ], Response::HTTP_BAD_REQUEST);
            }

            if ((string) $ingreso->fecha !== (string) $datosValidados['fecha']) {
                $periodo = Periodo::where('creado_por', $id_usuario)
                    ->where('fecha_inicio', '<=', $datosValidados['fecha'])
                    ->where('fecha_fin', '>=', $datosValidados['fecha'])
                    ->first();

                if ($periodo) {
                    $datosValidados['id_periodo'] = $periodo->id;
                    $datosValidados['estatus'] = Ingreso::ESTATUS_ACTIVO;
                } else {
                    $datosValidados['id_periodo'] = null;
                    $datosValidados['estatus'] = Ingreso::ESTATUS_SIN_PERIODO;
                }
            }

            $ingreso->update($datosValidados);

            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve los ingresos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarIngresos(Request $request)
    {
        try {
            $id_usuario = $request->user()->id;
            $ingresos = Ingreso::where('creado_por', $id_usuario)->get();
            return response()->json(['status' => true, 'data' => $ingresos], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve un ingreso de manera individual.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarIngresoPorId(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;
            $ingreso = Ingreso::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$ingreso) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El ingreso no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar el estatus de un ingreso.
     * @param EditarEstatusIngresoRequest $request
     * @param int $id Identificador del ingreso
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstatusIngreso(EditarEstatusIngresoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $ingreso = Ingreso::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que el ingreso exista.
            if (!$ingreso) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El ingreso no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            $ingreso->update($datosValidados);

            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
