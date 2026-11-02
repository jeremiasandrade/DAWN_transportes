<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Provincia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DestinoController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'orden' => ['nullable', 'string', Rule::in(['provincia', 'nombre_asc', 'nombre_desc'])],
        ]);
        $busqueda = trim($validated['q'] ?? '');
        $orden = $validated['orden'] ?? 'provincia';

        $ciudades = Ciudad::query()
            ->join('provincias', 'provincias.id_provincia', '=', 'ciudades.provincia_id')
            ->select('ciudades.*')
            ->when($busqueda !== '', function ($query) use ($busqueda): void {
                $termino = '%'.$busqueda.'%';

                $query->where(function ($query) use ($termino): void {
                    $query->where('ciudades.nombre_ciudad', 'like', $termino)
                        ->orWhere('provincias.nombre_provincia', 'like', $termino);
                });
            })
            ->with('provincia')
            ->when($orden === 'provincia', function ($query): void {
                $query->orderBy('provincias.nombre_provincia')
                    ->orderBy('ciudades.nombre_ciudad');
            })
            ->when($orden === 'nombre_asc', function ($query): void {
                $query->orderBy('ciudades.nombre_ciudad')
                    ->orderBy('provincias.nombre_provincia');
            })
            ->when($orden === 'nombre_desc', function ($query): void {
                $query->orderByDesc('ciudades.nombre_ciudad')
                    ->orderBy('provincias.nombre_provincia');
            })
            ->paginate(30)
            ->withQueryString();

        return view('destino.index', compact('ciudades', 'busqueda', 'orden'));
    }

    public function create(): View
    {
        return view('destino.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_ciudad' => ['required', 'string', 'max:45', 'unique:ciudades,nombre_ciudad'],
            'provincia' => ['required', 'string', 'max:45'],
        ]);

        $provincia = Provincia::firstOrCreate([
            'nombre_provincia' => $validated['provincia'],
        ]);

        Ciudad::create([
            'nombre_ciudad' => $validated['nombre_ciudad'],
            'provincia_id' => $provincia->id_provincia,
        ]);

        return redirect()->route('destinos.index')->with('success', 'Destino creado correctamente.');
    }

    public function show(Ciudad $destino): View
    {
        $destino->load(['provincia', 'rutasOrigen.destino', 'rutasDestino.origen']);

        return view('destino.show', compact('destino'));
    }

    public function edit(Ciudad $destino): View
    {
        return view('destino.edit', compact('destino'));
    }

    public function update(Request $request, Ciudad $destino): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_ciudad' => ['required', 'string', 'max:45', 'unique:ciudades,nombre_ciudad,'.$destino->id_ciudad.',id_ciudad'],
            'provincia' => ['required', 'string', 'max:45'],
        ]);

        $provincia = Provincia::firstOrCreate([
            'nombre_provincia' => $validated['provincia'],
        ]);

        $destino->update([
            'nombre_ciudad' => $validated['nombre_ciudad'],
            'provincia_id' => $provincia->id_provincia,
        ]);

        return redirect()->route('destinos.index')->with('success', 'Destino actualizado correctamente.');
    }

    public function destroy(Ciudad $destino): RedirectResponse
    {
        if ($destino->rutasOrigen()->exists() || $destino->rutasDestino()->exists()) {
            return back()->with('error', 'No se puede eliminar el destino porque tiene rutas asociadas.');
        }

        $destino->delete();

        return redirect()->route('destinos.index')->with('success', 'Destino eliminado correctamente.');
    }
}
