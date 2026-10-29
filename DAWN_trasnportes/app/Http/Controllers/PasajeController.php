<?php

namespace App\Http\Controllers;

use App\Models\Pasaje;
use App\Models\Persona;
use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PasajeController extends Controller
{
    private const ESTADOS = ['reservado', 'pagado', 'anulado', 'usado'];

    public function index(): View
    {
        $pasajes = Pasaje::with(['viaje.ciudadPartida', 'viaje.ciudadLlegada', 'persona'])
            ->latest('id')
            ->get();

        return view('pasaje.index', compact('pasajes'));
    }

    public function create(): View
    {
        return view('pasaje.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules($request));

        Pasaje::create($validated);

        return redirect()->route('pasajes.index')->with('success', 'Pasaje registrado correctamente.');
    }

    public function show(Pasaje $pasaje): View
    {
        $pasaje->load(['viaje.ciudadPartida', 'viaje.ciudadLlegada', 'persona']);

        return view('pasaje.show', compact('pasaje'));
    }

    public function edit(Pasaje $pasaje): View
    {
        return view('pasaje.edit', array_merge($this->formData(), compact('pasaje')));
    }

    public function update(Request $request, Pasaje $pasaje): RedirectResponse
    {
        $validated = $request->validate($this->rules($request, $pasaje));

        $pasaje->update($validated);

        return redirect()->route('pasajes.index')->with('success', 'Pasaje actualizado correctamente.');
    }

    public function destroy(Pasaje $pasaje): RedirectResponse
    {
        $pasaje->delete();

        return redirect()->route('pasajes.index')->with('success', 'Pasaje eliminado correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        $viajes = Viaje::with(['ciudadPartida', 'ciudadLlegada'])
            ->orderByDesc('fecha_partida')
            ->get();

        $personas = Persona::orderBy('apellidos')
            ->orderBy('nombre')
            ->get();

        return [
            'viajes' => $viajes,
            'personas' => $personas,
            'estados' => self::ESTADOS,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(Request $request, ?Pasaje $pasaje = null): array
    {
        $uniqueAsiento = Rule::unique('pasajes', 'numero_asiento')
            ->where(fn ($query) => $query->where('viaje_id', $request->input('viaje_id')));

        if ($pasaje !== null) {
            $uniqueAsiento->ignore($pasaje->id);
        }

        return [
            'viaje_id' => ['required', 'integer', 'exists:viajes,id_viaje'],
            'persona_dni' => ['required', 'string', 'exists:personas,dni'],
            'numero_asiento' => ['required', 'integer', 'min:1', $uniqueAsiento],
            'precio' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', Rule::in(self::ESTADOS)],
        ];
    }
}
