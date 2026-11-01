<?php

namespace App\Http\Controllers;

use App\Models\ViajeRecomendado;
use App\Services\ViajeCatalogoService;
use Illuminate\View\View;

class ViajeController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }

    public function show(string $id, ViajeCatalogoService $viajeCatalogoService): View
    {
        $trip = $viajeCatalogoService->buscar($id);

        abort_if($trip === null, 404);

        $coverPath = ViajeRecomendado::query()
            ->where('viaje_id', $id)
            ->orWhere(fn ($query) => $query->whereNull('viaje_id')->where('id', $id))
            ->value('imagen_portada');

        return view('viaje.show', [
            'trip' => $trip,
            'coverImage' => $coverPath ? asset('storage/'.$coverPath) : null,
        ]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
