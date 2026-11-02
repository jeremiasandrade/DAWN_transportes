<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\Ruta;
use App\Models\Vehiculo;
use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ViajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $viajes = Viaje::query()
            ->with(['ciudadPartida', 'ciudadLlegada', 'cliente.persona', 'vehiculo'])
            ->withCount('pasajes')
            ->orderByDesc('fecha_partida')
            ->paginate(20);

        return view('admin.viajes.index', compact('viajes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.viajes.create', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedTripData($request);
        Viaje::query()->create($validated);

        return redirect()->route('admin.viajes.index')->with('success', 'Viaje creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Viaje $viaje): View
    {
        $viaje->load(['ciudadPartida.provincia', 'ciudadLlegada.provincia', 'cliente.persona', 'vehiculo'])
            ->loadCount('pasajes');

        return view('admin.viajes.show', compact('viaje'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Viaje $viaje): View
    {
        return view('admin.viajes.edit', [...$this->formData(), 'viaje' => $viaje]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Viaje $viaje): RedirectResponse
    {
        $viaje->update($this->validatedTripData($request));

        return redirect()->route('admin.viajes.show', $viaje)->with('success', 'Viaje actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Viaje $viaje): RedirectResponse
    {
        if ($viaje->pasajes()->exists()) {
            return back()->with('error', 'No se puede eliminar un viaje que ya tiene pasajes.');
        }

        $viaje->delete();

        return redirect()->route('admin.viajes.index')->with('success', 'Viaje eliminado correctamente.');
    }

    /**
     * @return array{ciudades: Collection<int, Ciudad>, rutas: Collection<int, Ruta>, clientes: Collection<int, Cliente>, vehiculos: Collection<int, Vehiculo>}
     */
    private function formData(): array
    {
        return [
            'ciudades' => Ciudad::query()
                ->with('provincia')
                ->orderBy('nombre_ciudad')
                ->get(),
            'rutas' => Ruta::query()
                ->with(['origen', 'destino'])
                ->orderBy('id_ciudad_partida')
                ->orderBy('id_ciudad_llegada')
                ->get(),
            'clientes' => Cliente::query()
                ->with('persona')
                ->orderBy('persona_dni')
                ->get(),
            'vehiculos' => Vehiculo::query()
                ->orderBy('marca')
                ->orderBy('modelo')
                ->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedTripData(Request $request): array
    {
        $validated = $request->validate([
            'id_ciudad_partida' => ['required', 'integer', 'exists:ciudades,id_ciudad'],
            'id_ciudad_llegada' => ['required', 'integer', 'different:id_ciudad_partida', 'exists:ciudades,id_ciudad'],
            'cliente_dni' => ['required', 'string', 'exists:clientes,persona_dni'],
            'vehiculo_id' => ['required', 'integer', 'exists:vehiculos,id_vehiculo'],
            'fecha_partida' => ['required', 'date'],
            'fecha_llegada' => ['required', 'date', 'after:fecha_partida'],
        ]);

        $routeExists = Ruta::query()
            ->where('id_ciudad_partida', $validated['id_ciudad_partida'])
            ->where('id_ciudad_llegada', $validated['id_ciudad_llegada'])
            ->exists();

        if (! $routeExists) {
            throw ValidationException::withMessages([
                'id_ciudad_llegada' => 'No existe una ruta registrada entre las ciudades seleccionadas.',
            ]);
        }

        return $validated;
    }
}
