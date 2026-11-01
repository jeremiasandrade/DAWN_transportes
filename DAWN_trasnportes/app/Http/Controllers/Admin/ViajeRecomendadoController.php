<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use App\Models\ViajeRecomendado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ViajeRecomendadoController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $viajesRecomendados = ViajeRecomendado::with('viaje.ciudadPartida', 'viaje.ciudadLlegada', 'viaje.vehiculo')
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        return view('admin.viajes-recomendados.index', compact('viajesRecomendados'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        $viajes = Viaje::with('ciudadPartida', 'ciudadLlegada', 'vehiculo')->get();

        return view('admin.viajes-recomendados.create', compact('viajes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'titulo' => ['nullable', 'string', 'max:255'],
            'ruta' => ['nullable', 'string', 'max:255'],
            'viaje_id' => ['nullable', 'exists:viajes,id_viaje'],
            'precio' => ['required', 'numeric', 'min:0'],
            'descuento_porcentaje' => ['nullable', 'integer', 'min:0', 'max:100'],
            'vehiculo' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen_portada' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $validated = $this->storeImages($request, $validated);

        $validated['activo'] = $request->boolean('activo');
        $validated['descuento_porcentaje'] = $validated['descuento_porcentaje'] ?? 0;
        $validated['orden'] = $validated['orden'] ?? 0;

        ViajeRecomendado::create($validated);

        return redirect()->route('admin.viajes-recomendados.index')
            ->with('success', 'Viaje recomendado creado correctamente.');
    }

    public function edit(ViajeRecomendado $viajeRecomendado): View
    {
        $this->authorizeAdmin();

        $viajes = Viaje::with('ciudadPartida', 'ciudadLlegada', 'vehiculo')->get();

        return view('admin.viajes-recomendados.edit', compact('viajeRecomendado', 'viajes'));
    }

    public function update(Request $request, ViajeRecomendado $viajeRecomendado): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'titulo' => ['nullable', 'string', 'max:255'],
            'ruta' => ['nullable', 'string', 'max:255'],
            'viaje_id' => ['nullable', 'exists:viajes,id_viaje'],
            'precio' => ['required', 'numeric', 'min:0'],
            'descuento_porcentaje' => ['nullable', 'integer', 'min:0', 'max:100'],
            'vehiculo' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen_portada' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $validated = $this->storeImages($request, $validated, $viajeRecomendado);

        $validated['activo'] = $request->boolean('activo');
        $validated['descuento_porcentaje'] = $validated['descuento_porcentaje'] ?? 0;
        $validated['orden'] = $validated['orden'] ?? 0;

        $viajeRecomendado->update($validated);

        return redirect()->route('admin.viajes-recomendados.index')
            ->with('success', 'Viaje recomendado actualizado correctamente.');
    }

    public function destroy(ViajeRecomendado $viajeRecomendado): RedirectResponse
    {
        $this->authorizeAdmin();

        foreach (['imagen', 'imagen_portada'] as $imageAttribute) {
            if ($viajeRecomendado->{$imageAttribute} && Storage::disk('public')->exists($viajeRecomendado->{$imageAttribute})) {
                Storage::disk('public')->delete($viajeRecomendado->{$imageAttribute});
            }
        }

        $viajeRecomendado->delete();

        return redirect()->route('admin.viajes-recomendados.index')
            ->with('success', 'Viaje recomendado eliminado correctamente.');
    }

    private function authorizeAdmin(): void
    {
        $user = auth()->user();

        if ($user === null) {
            abort(403);
        }

        $isAdmin = filled($user->dni)
            && DB::table('administradores')->where('persona_dni', $user->dni)->exists();

        if (! $isAdmin) {
            abort(403);
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function storeImages(Request $request, array $validated, ?ViajeRecomendado $viajeRecomendado = null): array
    {
        foreach (['imagen', 'imagen_portada'] as $imageAttribute) {
            if (! $request->hasFile($imageAttribute)) {
                continue;
            }

            $oldImage = $viajeRecomendado?->{$imageAttribute};
            $newImage = $request->file($imageAttribute)->store('viajes-recomendados', 'public');

            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $validated[$imageAttribute] = $newImage;
        }

        return $validated;
    }
}
