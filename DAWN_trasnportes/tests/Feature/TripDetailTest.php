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
]);

test('an unknown trip detail returns not found', function () {
    $this->get(route('viajes.show', 999))->assertNotFound();
});
