<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ViajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $trips = [
            '1' => [
                'destination' => 'Posadas',
                'description' => 'La capital de la provincia de Misiones te espera con su hermosa costanera a orillas del río Paraná, su rica historia jesuítica y una oferta gastronómica inigualable. Ideal para una escapada de fin de semana.',
                'price' => '$8.500',
                'company' => 'Dawn Transportes VIP',
                'vehicle' => 'Minibús Ejecutivo',
                'distance' => '90 km',
                'departure' => '08:30 AM',
                'arrival' => '10:00 AM',
                'availability' => '12 boletos',
            ],
            '2' => [
                'destination' => 'Córdoba',
                'description' => 'Descubrí Córdoba, su arquitectura histórica, sus paseos urbanos y la energía de una de las ciudades más emblemáticas del país.',
                'price' => '$45.000',
                'company' => 'Dawn Transportes VIP',
                'vehicle' => 'Ómnibus de doble piso',
                'distance' => '1.180 km',
                'departure' => '08:30 AM',
                'arrival' => '10:00 PM',
                'availability' => '12 boletos',
            ],
            '3' => [
                'destination' => 'Buenos Aires',
                'description' => 'Viví Buenos Aires, una ciudad llena de cultura, gastronomía, espacios verdes y rincones para descubrir en cada visita.',
                'price' => '$55.000',
                'company' => 'Dawn Transportes VIP',
                'vehicle' => 'Ómnibus de piso elevado',
                'distance' => '1.050 km',
                'departure' => '08:30 AM',
                'arrival' => '08:30 PM',
                'availability' => '12 boletos',
            ],
        ];

        abort_unless(isset($trips[$id]), 404);

        return view('viaje.show', ['trip' => [...$trips[$id], 'id' => $id]]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
