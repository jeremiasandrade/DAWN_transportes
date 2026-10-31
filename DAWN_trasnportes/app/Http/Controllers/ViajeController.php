<?php

namespace App\Http\Controllers;

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

        return view('viaje.show', ['trip' => $trip]);
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
