<?php

namespace App\Http\Controllers;

use App\Mail\PasajeCompradoMail;
use App\Models\Pago;
use App\Services\ViajeCatalogoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(Request $request): View
    {
        $pagos = $request->user()
            ->pagos()
            ->latest()
            ->get();

        return view('pago.index', compact('pagos'));
    }

    public function create(Request $request, ViajeCatalogoService $viajeCatalogoService): View
    {
        $viaje = $viajeCatalogoService->buscar($request->string('viaje_id')->toString());

        abort_if($viaje === null, 404);

        return view('pago.create', [
            'viaje' => $viaje,
            'metodosPago' => Pago::METODOS_PAGO,
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request, ViajeCatalogoService $viajeCatalogoService): RedirectResponse
    {
        $validated = $request->validate([
            'viaje_id' => ['required', 'string', Rule::in($viajeCatalogoService->ids())],
            'nombre_pasajero' => ['required', 'string', 'max:100'],
            'dni_pasajero' => ['required', 'string', 'max:32'],
            'metodo_pago' => ['required', Rule::in(array_keys(Pago::METODOS_PAGO))],
        ]);

        $viaje = $viajeCatalogoService->buscar($validated['viaje_id']);

        abort_if($viaje === null, 404);

        $user = $request->user();

        $pago = $user->pagos()->create([
            'referencia' => 'DAWN-'.Str::ulid(),
            'viaje_codigo' => $viaje['id'],
            'origen' => $viaje['origin'],
            'destino' => $viaje['destination'],
            'monto' => $viaje['precio'],
            'metodo_pago' => $validated['metodo_pago'],
            'estado' => $validated['metodo_pago'] === 'transferencia' ? 'pendiente' : 'aprobado',
            'nombre_pasajero' => $validated['nombre_pasajero'],
            'dni_pasajero' => $validated['dni_pasajero'],
        ]);

        Mail::to($user)->send(new PasajeCompradoMail($pago, $user));

        return redirect()
            ->route('pagos.show', $pago)
            ->with('success', 'Compra registrada. Te enviamos el pasaje a tu correo.');
    }

    public function show(Request $request, Pago $pago): View
    {
        abort_unless($pago->user_id === $request->user()->id, 403);

        return view('pago.show', [
            'pago' => $pago,
            'metodosPago' => Pago::METODOS_PAGO,
        ]);
    }
}
