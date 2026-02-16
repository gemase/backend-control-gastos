<?php

namespace App\Http\Controllers;

use App\Http\Requests\Periodo\CrearPeriodoRequest;
use App\Http\Requests\Periodo\EditarPeriodoRequest;
use App\Models\Periodo;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PeriodoController extends Controller
{
    /**
     * Crear un nuevo periodo.
     * @param CrearPeriodoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearPeriodo(CrearPeriodoRequest $request)
    {
        try {
            $datosValidados = $request->validated();
            $datosValidados['creado_por'] = $request->user()->id;
            $datosValidados['mes'] = Str::ucfirst(Carbon::parse($datosValidados['fecha_inicio'])->locale('es')->translatedFormat('F'));

            $periodoEmpalmado = Periodo::where('creado_por', $datosValidados['creado_por'])
                ->where('fecha_inicio', '<=', $datosValidados['fecha_fin'])
                ->where('fecha_fin', '>=', $datosValidados['fecha_inicio'])
                ->first();

            if ($periodoEmpalmado) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'Las fechas del periodo se empalman con un periodo existente del ' . $periodoEmpalmado->fecha_inicio . ' al ' . $periodoEmpalmado->fecha_fin . '.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $periodo = Periodo::create($datosValidados);
            return response()->json(['status' => true, 'data' => $periodo], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve los periodos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarPeriodos(Request $request)
    {
        try {
            $id_usuario = $request->user()->id;
            $periodos = Periodo::where('creado_por', $id_usuario)->get();
            return response()->json(['status' => true, 'data' => $periodos], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve un periodo de manera individual.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarPeriodoPorId(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;
            $periodo = Periodo::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$periodo) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El periodo no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'data' => $periodo], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar un periodo.
     * @param EditarPeriodoRequest $request
     * @param int $id Identificador del periodo
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarPeriodo(EditarPeriodoRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;
            $datosValidados['mes'] = Str::ucfirst(Carbon::parse($datosValidados['fecha_inicio'])->locale('es')->translatedFormat('F'));

            $periodo = Periodo::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que el periodo exista.
            if (!$periodo) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El periodo no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            $periodoEmpalmado = Periodo::where('creado_por', $id_usuario)
                ->where('id', '!=', $id)
                ->where('fecha_inicio', '<=', $datosValidados['fecha_fin'])
                ->where('fecha_fin', '>=', $datosValidados['fecha_inicio'])
                ->first();

            if ($periodoEmpalmado) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'Las fechas del periodo se empalman con un periodo existente del ' . $periodoEmpalmado->fecha_inicio . ' al ' . $periodoEmpalmado->fecha_fin . '.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $periodo->update($datosValidados);

            return response()->json(['status' => true, 'data' => $periodo], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
