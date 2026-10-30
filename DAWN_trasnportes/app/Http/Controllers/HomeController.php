<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Ruta;
use App\Services\DistanciaService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request, DistanciaService $distanciaService): View
    {
        $ciudades = Ciudad::with('provincia')
            ->orderBy('nombre_ciudad')
            ->get();

        $origenSeleccionado = null;
        $destinoSeleccionado = null;
        $distancia = null;
        $rutaExistente = null;
        $busquedaRealizada = false;
        $mensajeDistancia = null;

        $origenId = $request->integer('origin');
        $destinoId = $request->integer('destination');

        if ($origenId > 0 || $destinoId > 0) {
            $busquedaRealizada = true;

            $origenSeleccionado = $origenId > 0
                ? Ciudad::with('provincia')->find($origenId)
                : null;
            $destinoSeleccionado = $destinoId > 0
                ? Ciudad::with('provincia')->find($destinoId)
                : null;

            if ($origenSeleccionado === null || $destinoSeleccionado === null) {
                $mensajeDistancia = 'Seleccioná una ciudad de origen y una de destino válidas.';
            } elseif ($origenSeleccionado->id_ciudad === $destinoSeleccionado->id_ciudad) {
                $mensajeDistancia = 'La ciudad de origen y la de destino deben ser distintas.';
            } else {
                $distancia = $distanciaService->calcular($origenSeleccionado, $destinoSeleccionado);

                if ($distancia === null) {
                    $mensajeDistancia = 'No se pudo calcular la distancia para esa ruta.';
                }

                $rutaExistente = Ruta::query()
                    ->where('id_ciudad_partida', $origenSeleccionado->id_ciudad)
                    ->where('id_ciudad_llegada', $destinoSeleccionado->id_ciudad)
                    ->first();
            }
        }

        return view('home', compact(
            'ciudades',
            'origenSeleccionado',
            'destinoSeleccionado',
            'distancia',
            'rutaExistente',
            'busquedaRealizada',
            'mensajeDistancia',
        ));
    }
}
