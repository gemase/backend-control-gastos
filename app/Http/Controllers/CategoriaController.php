<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoria\CreaCategoriaRequest;
use App\Http\Requests\Categoria\EditaEstatusCategoriaRequest;
use App\Http\Requests\Categoria\EditaCategoriaRequest;
use Exception;
use Illuminate\Http\Response;
use App\Models\Categoria;
use App\Models\Periodo;
use App\Models\Presupuesto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Crear una nueva categoría.
     * @param CreaCategoriaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearCategoria(CreaCategoriaRequest $request)
    {
        try {
            $datosValidados = $request->validated();
            $datosValidados['creado_por'] = $request->user()->id;

            //Se valida que el nombre y el usuario sean únicos.
            $existe = Categoria::where('creado_por', $datosValidados['creado_por'])
                ->where('nombre', $datosValidados['nombre'])->exists();

            if ($existe) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El nombre de categoría ya existe.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $categoria = Categoria::create($datosValidados);

            //Al crear una nueva categoría, se generan presupuestos con monto 0 para cada periodo del usuario.
            $periodos = Periodo::where('creado_por', $datosValidados['creado_por'])->get();
            $presupuestos = [];
            foreach ($periodos as $periodo) {
                $presupuestos[] = [
                    'creado_por' => $categoria->creado_por,
                    'id_periodo' => $periodo->id,
                    'id_categoria' => $categoria->id,
                    'monto' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if (!empty($presupuestos)) {
                Presupuesto::insert($presupuestos);
            }

            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve las categorías.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarCategorias(Request $request)
    {
        try {
            $id_usuario = $request->user()->id;
            $categorias = Categoria::where('creado_por', $id_usuario)->get();
            return response()->json(['status' => true, 'data' => $categorias], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Devuelve una categoría de manera individual.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarCategoriaPorId(Request $request, $id)
    {
        try {
            $id_usuario = $request->user()->id;
            $categoria = Categoria::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'La categoría no fue encontrada.']
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar una categoría.
     * @param EditaCategoriaRequest $request
     * @param int $id Identificador movimiento categoría
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarCategoria(EditaCategoriaRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $categoria = Categoria::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que la categoría exista.
            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'La categoría no fue encontrada.']
                ], Response::HTTP_NOT_FOUND);
            }

            //Se valida que el nombre y el usuario sean únicos.
            $existe = Categoria::where('creado_por', $id_usuario)
                ->where('nombre', $datosValidados['nombre'])
                ->where('id', '!=', $id)->exists();

            if ($existe) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'El nombre de categoría ya existe.']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $categoria->update($datosValidados);

            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Editar el estatus de una categoría de movimiento.
     * @param EditaEstatusCategoriaRequest $request
     * @param int $id Identificador movimiento categoría
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstatusCategoria(EditaEstatusCategoriaRequest $request, $id)
    {
        try {
            $datosValidados = $request->validated();
            $id_usuario = $request->user()->id;

            $categoria = Categoria::where('creado_por', $id_usuario)
                ->where('id', $id)->first();

            //Se valida que la categoría exista.
            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'error' => ['message' => 'La categoría no fue encontrada.']
                ], Response::HTTP_NOT_FOUND);
            }

            $categoria->update($datosValidados);

            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
