<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Ruta;
use App\Services\DistanciaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RutaController extends Controller
{
    public function index(): View
    {
        $rutas = Ruta::with(['origen', 'destino'])
            ->orderBy('id_ciudad_partida')
            ->orderBy('id_ciudad_llegada')
            ->get();

        return view('ruta.index', compact('rutas'));
    }

    public function create(): View
    {
        $ciudades = Ciudad::with('provincia')->orderBy('nombre_ciudad')->get();

        return view('ruta.create', compact('ciudades'));
    }

    public function store(Request $request, DistanciaService $distanciaService): RedirectResponse
    {
        $validated = $request->validate([
            'id_ciudad_partida' => ['required', 'integer', 'exists:ciudades,id_ciudad'],
            'id_ciudad_llegada' => ['required', 'integer', 'exists:ciudades,id_ciudad'],
            'distancia' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($validated['id_ciudad_partida'] == $validated['id_ciudad_llegada']) {
            return back()->withInput()->withErrors([
                'id_ciudad_llegada' => 'La ciudad de llegada debe ser distinta de la de partida.',
            ]);
        }

        $existe = Ruta::where('id_ciudad_partida', $validated['id_ciudad_partida'])
            ->where('id_ciudad_llegada', $validated['id_ciudad_llegada'])
            ->exists();

        if ($existe) {
            return back()->withInput()->withErrors([
                'id_ciudad_partida' => 'Ya existe una ruta entre esas ciudades.',
            ]);
        }

        $distancia = $this->resolverDistancia($validated, $distanciaService);

        if ($distancia === null) {
            return back()->withInput()->withErrors([
                'distancia' => 'No se pudo calcular la distancia. Verificá las coordenadas de las ciudades o ingresala manualmente.',
            ]);
        }

        Ruta::create([...$validated, 'distancia' => $distancia]);

        return redirect()->route('rutas.index')->with('success', 'Ruta creada correctamente.');
    }

    public function show(Ruta $ruta): View
    {
        $ruta->load(['origen.provincia', 'destino.provincia']);

        return view('ruta.show', compact('ruta'));
    }

    public function edit(Ruta $ruta): View
    {
        $ciudades = Ciudad::with('provincia')->orderBy('nombre_ciudad')->get();

        return view('ruta.edit', compact('ruta', 'ciudades'));
    }

    public function update(Request $request, Ruta $ruta, DistanciaService $distanciaService): RedirectResponse
    {
        $validated = $request->validate([
            'id_ciudad_partida' => ['required', 'integer', 'exists:ciudades,id_ciudad'],
            'id_ciudad_llegada' => ['required', 'integer', 'exists:ciudades,id_ciudad'],
            'distancia' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($validated['id_ciudad_partida'] == $validated['id_ciudad_llegada']) {
            return back()->withInput()->withErrors([
                'id_ciudad_llegada' => 'La ciudad de llegada debe ser distinta de la de partida.',
            ]);
        }

        $cambioClave = $validated['id_ciudad_partida'] != $ruta->id_ciudad_partida
            || $validated['id_ciudad_llegada'] != $ruta->id_ciudad_llegada;

        if ($cambioClave) {
            $existe = Ruta::where('id_ciudad_partida', $validated['id_ciudad_partida'])
                ->where('id_ciudad_llegada', $validated['id_ciudad_llegada'])
                ->exists();

            if ($existe) {
                return back()->withInput()->withErrors([
                    'id_ciudad_partida' => 'Ya existe una ruta entre esas ciudades.',
                ]);
            }
        }

        $distancia = $this->resolverDistancia($validated, $distanciaService);

        if ($distancia === null) {
            return back()->withInput()->withErrors([
                'distancia' => 'No se pudo calcular la distancia. Verificá las coordenadas de las ciudades o ingresala manualmente.',
            ]);
        }

        $ruta->newQuery()
            ->where('id_ciudad_partida', $ruta->id_ciudad_partida)
            ->where('id_ciudad_llegada', $ruta->id_ciudad_llegada)
            ->update([...$validated, 'distancia' => $distancia]);

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente.');
    }

    public function distancia(Request $request, DistanciaService $distanciaService): JsonResponse
    {
        $validated = $request->validate([
            'id_ciudad_partida' => ['required', 'integer', 'exists:ciudades,id_ciudad'],
            'id_ciudad_llegada' => ['required', 'integer', 'different:id_ciudad_partida', 'exists:ciudades,id_ciudad'],
        ]);

        $distancia = $this->resolverDistancia($validated, $distanciaService);

        return response()->json([
            'distancia' => $distancia,
            'calculada' => $distancia !== null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $datos
     */
    private function resolverDistancia(array $datos, DistanciaService $distanciaService): ?int
    {
        $origen = Ciudad::find($datos['id_ciudad_partida']);
        $destino = Ciudad::find($datos['id_ciudad_llegada']);

        if ($origen === null || $destino === null) {
            return null;
        }

        $distanciaApi = $distanciaService->calcular($origen, $destino);

        return $distanciaApi ?? $datos['distancia'] ?? null;
    }

    public function destroy(Ruta $ruta): RedirectResponse
    {
        $ruta->newQuery()
            ->where('id_ciudad_partida', $ruta->id_ciudad_partida)
            ->where('id_ciudad_llegada', $ruta->id_ciudad_llegada)
            ->delete();

        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente.');
    }
}
