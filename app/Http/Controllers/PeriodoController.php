<?php

namespace App\Http\Controllers;

use App\Http\Requests\Periodo\CrearPeriodoRequest;
use App\Http\Requests\Periodo\EditarPeriodoRequest;
use App\Models\Categoria;
use App\Models\Gasto;
use App\Models\Ingreso;
use App\Models\Periodo;
use App\Models\Presupuesto;
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

            //Al crear un nuevo periodo, se generan presupuestos con monto 0 para cada categoría del usuario.
            $categorias = Categoria::where('creado_por', $datosValidados['creado_por'])->get();
            $presupuestos = [
                [
                    'creado_por' => $periodo->creado_por,
                    'id_periodo' => $periodo->id,
                    'id_categoria' => null,
                    'monto' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            foreach ($categorias as $categoria) {
                $presupuestos[] = [
                    'creado_por' => $periodo->creado_por,
                    'id_periodo' => $periodo->id,
                    'id_categoria' => $categoria->id,
                    'monto' => $categoria->presupuesto_base ?? 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            Presupuesto::insert($presupuestos);

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

            $periodos = Periodo::where('creado_por', $id_usuario)
                ->orderBy('fecha_inicio', 'desc')
                ->get()
                ->map(function ($periodo) use ($id_usuario) {
                    $ingresos = Ingreso::where('creado_por', $id_usuario)->where('id_periodo', $periodo->id)->sum('monto');
                    $gastos = Gasto::where('creado_por', $id_usuario)->where('id_periodo', $periodo->id)->where('estatus', Gasto::ESTATUS_ACTIVO)->sum('monto');

                    return array_merge($periodo->toArray(), [
                        'ingresos' => (float) $ingresos,
                        'gastos' => (float) $gastos,
                        'balance' => (float) $ingresos - (float) $gastos,
                    ]);
                });

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

            $ingresos = Ingreso::where('creado_por', $id_usuario)->where('id_periodo', $id)->sum('monto');
            $gastos = Gasto::where('creado_por', $id_usuario)->where('id_periodo', $id)->where('estatus', Gasto::ESTATUS_ACTIVO)->sum('monto');

            $data = array_merge($periodo->toArray(), [
                'ingresos' => (float) $ingresos,
                'gastos' => (float) $gastos,
                'balance' => (float) $ingresos - (float) $gastos,
            ]);

            return response()->json(['status' => true, 'data' => $data], Response::HTTP_OK);
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

    /**
     * Devuelve el resumen del dashboard de un periodo.
     * @param Request $request
     * @param int $id Identificador del periodo
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;

            $periodo = Periodo::where('creado_por', $id_usuario)->where('id', $id)->first();

            if (!$periodo) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El periodo no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            // Resumen financiero del periodo
            $totalIngresos = Ingreso::where('creado_por', $id_usuario)->where('id_periodo', $id)->sum('monto');
            $totalGastos   = Gasto::where('creado_por', $id_usuario)->where('id_periodo', $id)->where('estatus', Gasto::ESTATUS_ACTIVO)->sum('monto');

            $resumen = [
                'ingresos' => (float) $totalIngresos,
                'gastos' => (float) $totalGastos,
                'balance' => (float) $totalIngresos - (float) $totalGastos,
            ];

            // Presupuestos por categoría con gastado, diferencia y porcentaje
            $presupuestos = Presupuesto::with('categoria')
                ->where('creado_por', $id_usuario)
                ->where('id_periodo', $id)
                ->whereNotNull('id_categoria')
                ->get()
                ->map(function ($presupuesto) {
                    $diferencia = (float) $presupuesto->monto > 0 ? (float) $presupuesto->monto - $presupuesto->gastado : null;
                    $porcentaje = (float) $presupuesto->monto > 0 ? round(($presupuesto->gastado / (float) $presupuesto->monto) * 100, 1) : null;

                    return [
                        'id' => $presupuesto->id,
                        'categoria' => $presupuesto->categoria?->nombre,
                        'presupuesto' => (float) $presupuesto->monto,
                        'gastado' => $presupuesto->gastado,
                        'diferencia' => $diferencia,
                        'porcentaje' => $porcentaje,
                    ];
                });

            // Fila de totales
            $totalPresupuestado = $presupuestos->sum('presupuesto');
            $totalGastadoCategorias = $presupuestos->sum('gastado');
            $totalDiferencia = $totalPresupuestado > 0 ? $totalPresupuestado - $totalGastadoCategorias : null;
            $totalPorcentaje = $totalPresupuestado > 0 ? round(($totalGastadoCategorias / $totalPresupuestado) * 100, 1) : null;

            $totales = [
                'id' => null,
                'categoria' => 'Total',
                'presupuesto' => $totalPresupuestado,
                'gastado' => $totalGastadoCategorias,
                'diferencia' => $totalDiferencia,
                'porcentaje' => $totalPorcentaje,
            ];

            // Últimos 5 movimientos (gastos e ingresos combinados)
            $gastos = Gasto::with('categoria', 'formaPago')
                ->where('creado_por', $id_usuario)
                ->where('id_periodo', $id)
                ->where('estatus', Gasto::ESTATUS_ACTIVO)
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get()
                ->map(fn($gasto) => [
                    'nombre' => $gasto->descripcion,
                    'fecha' => $gasto->fecha,
                    'categoria' => $gasto->categoria?->nombre,
                    'forma_pago' => $gasto->formaPago?->nombre,
                    'tipo' => 'Gasto',
                    'monto' => -(float) $gasto->monto,
                ]);

            $ingresos = Ingreso::where('creado_por', $id_usuario)
                ->where('id_periodo', $id)
                ->where('estatus', Ingreso::ESTATUS_ACTIVO)
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get()
                ->map(fn($ingreso) => [
                    'nombre' => $ingreso->nombre,
                    'fecha' => $ingreso->fecha,
                    'categoria' => null,
                    'forma_pago' => null,
                    'tipo' => 'Ingreso',
                    'monto' => (float) $ingreso->monto,
                ]);

            $ultimosMovimientos = $gastos->concat($ingresos)
                ->sortByDesc('fecha')
                ->take(5)
                ->values();

            return response()->json([
                'status' => true,
                'data' => [
                    'resumen' => $resumen,
                    'presupuestos' => $presupuestos,
                    'totales' => $totales,
                    'ultimos_movimientos' => $ultimosMovimientos,
                ],
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve los movimientos paginados de un periodo (gastos e ingresos).
     * @param Request $request
     * @param int $id Identificador del periodo
     * @return \Illuminate\Http\JsonResponse
     */
    public function movimientos(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;

            $periodo = Periodo::where('creado_por', $id_usuario)->where('id', $id)->first();

            if (!$periodo) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El periodo no fue encontrado.']
                ], Response::HTTP_NOT_FOUND);
            }

            $gastos = Gasto::with('categoria', 'formaPago')
                ->where('creado_por', $id_usuario)
                ->where('id_periodo', $id)
                ->where('estatus', Gasto::ESTATUS_ACTIVO)
                ->get()
                ->map(fn($gasto) => [
                    'nombre' => $gasto->descripcion,
                    'fecha' => $gasto->fecha,
                    'categoria' => $gasto->categoria?->nombre,
                    'forma_pago' => $gasto->formaPago?->nombre,
                    'tipo' => 'Gasto',
                    'monto' => -(float) $gasto->monto,
                ]);

            $ingresos = Ingreso::where('creado_por', $id_usuario)
                ->where('id_periodo', $id)
                ->where('estatus', Ingreso::ESTATUS_ACTIVO)
                ->get()
                ->map(fn($ingreso) => [
                    'nombre' => $ingreso->nombre,
                    'fecha' => $ingreso->fecha,
                    'categoria' => null,
                    'forma_pago' => null,
                    'tipo' => 'Ingreso',
                    'monto' => (float) $ingreso->monto,
                ]);

            $movimientos = $gastos->concat($ingresos)->sortByDesc('fecha')->values();

            return response()->json([
                'status' => true,
                'data' => $movimientos,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
