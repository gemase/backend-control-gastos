<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gasto\CancelarGastoRequest;
use App\Http\Requests\Gasto\CrearGastoRequest;
use App\Http\Requests\Gasto\EditarGastoRequest;
use App\Models\Gasto;
use App\Models\Periodo;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    /**
     * Crear un nuevo gasto.
     * @param CrearGastoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearGasto(CrearGastoRequest $request)
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
                $datosValidados['estatus'] = Gasto::ESTATUS_ACTIVO;
            } else {
                $datosValidados['id_periodo'] = null;
                $datosValidados['estatus'] = Gasto::ESTATUS_SIN_PERIODO;
            }

            $gasto = Gasto::create($datosValidados);
            $gasto->load('formaPago', 'categoria', 'periodo');
            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar un gasto.
     * @param EditarGastoRequest $request
     * @param int $id Identificador del gasto
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarGasto(EditarGastoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $gasto = Gasto::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que el gasto exista.
            if (!$gasto) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El gasto no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            if ($gasto->estatus == Gasto::ESTATUS_CANCELADO) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El gasto no puede ser actualizado porque se encuentra (' . Gasto::ESTATUS_DESCRIPCIONES[Gasto::ESTATUS_CANCELADO] . ')']
                ], Response::HTTP_BAD_REQUEST);
            }

            if ((string) $gasto->fecha !== (string) $datosValidados['fecha']) {
                $periodo = Periodo::where('creado_por', $id_usuario)
                    ->where('fecha_inicio', '<=', $datosValidados['fecha'])
                    ->where('fecha_fin', '>=', $datosValidados['fecha'])
                    ->first();

                if ($periodo) {
                    $datosValidados['id_periodo'] = $periodo->id;
                    $datosValidados['estatus'] = Gasto::ESTATUS_ACTIVO;
                } else {
                    $datosValidados['id_periodo'] = null;
                    $datosValidados['estatus'] = Gasto::ESTATUS_SIN_PERIODO;
                }
            }

            $gasto->update($datosValidados);
            $gasto->load('formaPago', 'categoria', 'periodo');

            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve los gastos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarGastos(Request $request)
    {
        try {
            $id_usuario = $request->user()->id;
            $gastos = Gasto::with('formaPago')->with('categoria')->with('periodo')->where('creado_por', $id_usuario)->get();
            return response()->json(['status' => true, 'data' => $gastos], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve un gasto de manera individual.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarGastoPorId(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;
            $gasto = Gasto::with('formaPago', 'categoria', 'periodo')
                ->where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$gasto) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El gasto no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Cancelar gasto.
     * @param CancelarGastoRequest $request
     * @param int $id Identificador del gasto
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelarGasto(CancelarGastoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $gasto = Gasto::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que el gasto exista.
            if (!$gasto) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El gasto no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            if ($gasto->estatus == Gasto::ESTATUS_CANCELADO) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El gasto ya se encuentra en estatus (' . Gasto::ESTATUS_DESCRIPCIONES[Gasto::ESTATUS_CANCELADO] . ').']
                ], Response::HTTP_BAD_REQUEST);
            }

            $gasto->update($datosValidados);
            $gasto->load('formaPago', 'categoria', 'periodo');

            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
