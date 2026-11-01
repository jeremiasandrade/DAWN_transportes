<?php

use App\Models\User;
use App\Models\ViajeRecomendado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('an administrator can upload separate card and cover images', function () {
    Storage::fake('public');
    $administrator = User::factory()->administrator()->create();
    $cardImage = UploadedFile::fake()->image('card.jpg');
    $coverImage = UploadedFile::fake()->image('cover.jpg');

    $response = $this->actingAs($administrator)->post(route('admin.viajes-recomendados.store'), [
        'titulo' => 'Escapada a Posadas',
        'ruta' => 'Alem - Posadas',
        'precio' => 8500,
        'imagen' => $cardImage,
        'imagen_portada' => $coverImage,
        'activo' => '1',
    ]);

    $recommendation = ViajeRecomendado::query()->firstOrFail();

    $response->assertRedirect(route('admin.viajes-recomendados.index'));
    expect($recommendation->imagen)->not->toBeNull();
    expect($recommendation->imagen_portada)->not->toBeNull();
    expect(Storage::disk('public')->exists($recommendation->imagen))->toBeTrue();
    expect(Storage::disk('public')->exists($recommendation->imagen_portada))->toBeTrue();

    $this->get(route('home'))
        ->assertRedirect(route('desarrollo'));
});

test('the trip detail displays its stored cover image', function () {
    Storage::fake('public');
    $coverPath = 'viajes-recomendados/cover.jpg';
    Storage::disk('public')->put($coverPath, 'image-content');
    $recommendation = ViajeRecomendado::query()->create([
        'titulo' => 'Escapada a Posadas',
        'imagen_portada' => $coverPath,
        'precio' => 8500,
    ]);

    $response = $this->get(route('viajes.show', $recommendation->id));

    $response->assertOk()
        ->assertSee(asset('storage/'.$coverPath), false);
});
