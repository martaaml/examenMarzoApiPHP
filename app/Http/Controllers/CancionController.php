<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canciones;
use App\Models\Lista_canciones;
use App\Models\Lista_reproduccion;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CancionController extends Controller
{
    /**
     * Mostrar todas las inscripciones
     */
    public function index()
    {
        // Traer todas las inscripciones con los usuarios y eventos relacionados
        $listas = Canciones::with(['id_cancion', 'titulo', 'artista', 'album', 'anio'])->get();

        return response()->json($listas);
    }

    /**
     * Crear una nueva inscripción
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
           'id_cancion'=>'required',
           'titulo'=>'required',
           'artista'=>'required',
           'album'=>'required',
           'anio'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Crear la nueva inscripción
        $lista = Canciones::create([
            'id_cacnion' => $request->id_cancion,
            'titulo' => $request->titulo,
            'artista' => $request->artista,
            'album' => $request->album,
            'anio' => $request->anio,
        ]);

        return response()->json([
            'message' => 'Inscripción creada correctamente',
            'asistencia' => $lista
        ], 201);
    }

    /**
     * Ver detalles de una inscripción
     */
    public function show($id_cancion)
    {
        // Buscar la inscripción por ID
        $lista = Canciones::with(['id_cancion', 'titulo', 'artista', 'album', 'anio'])->find($id_cancion);

        if (!$lista) {
            return response()->json(['message' => 'Cancion no encontrada'], 404);
        }

        return response()->json($lista);
    }

    /**
     * Eliminar una inscripción (Soft Delete)
     */
    public function destroy($id)
    {
        // Buscar la inscripción por ID
        $lista = Canciones::find($id);

        if (!$lista) {
            return response()->json(['message' => 'Cancion no encontrada'], 404);
        }

        // Eliminar la inscripción
        $lista->delete();

        return response()->json(['message' => 'Cancion eliminada correctamente']);
    }
}
 