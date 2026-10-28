<?php

test('each featured trip opens its detail page', function (string $id, string $destination, string $price) {
    $response = $this->get(route('viajes.show', $id));

    $response->assertOk()
        ->assertSee("Viaje a {$destination}")
        ->assertSee($price)
        ->assertSee('Ficha Técnica del Viaje')
        ->assertSee('Comprar pasaje')
        ->assertDontSee('<img', false);
})->with([
    ['1', 'Posadas', '$8.500'],
    ['2', 'Córdoba', '$45.000'],
    ['3', 'Buenos Aires', '$55.000'],
    ['4', 'Corrientes', '$12.000'],
    ['5', 'Resistencia', '$18.500'],
    ['6', 'Formosa', '$22.000'],
    ['7', 'Tucumán', '$39.000'],
    ['8', 'Salta', '$46.000'],
    ['9', 'Mendoza', '$58.000'],
]);

test('an unknown trip detail returns not found', function () {
    $this->get(route('viajes.show', 999))->assertNotFound();
});
