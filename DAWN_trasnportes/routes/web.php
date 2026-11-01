<?php

use App\Http\Controllers\DestinoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PasajeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ViajeController;
use App\Models\Ciudad;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::resource('usuarios', UserController::class);
Route::resource('vehiculos', VehiculoController::class);
Route::model('destino', Ciudad::class);
Route::resource('destinos', DestinoController::class);
Route::get('rutas/distancia', [RutaController::class, 'distancia'])->name('rutas.distancia');
Route::resource('rutas', RutaController::class);
Route::resource('pasajes', PasajeController::class);
Route::resource('viajes', ViajeController::class);
Route::resource('reservas', ReservaController::class);
Route::resource('pagos', PagoController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->middleware('auth');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/contrasena', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});

Route::redirect('/dashboard', '/')->name('dashboard.index');

// Ruta para mostrar la vista de Login
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// Ruta para mostrar la vista de Registro
Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/desarrollo', function () {
    $modules = [
        ['name' => 'Destinos', 'prefix' => 'destinos', 'view' => 'destino', 'ready' => true],
        ['name' => 'Rutas', 'prefix' => 'rutas', 'view' => 'ruta', 'ready' => true],
        ['name' => 'Pasajes', 'prefix' => 'pasajes', 'view' => 'pasaje', 'ready' => true],
        ['name' => 'Viajes', 'prefix' => 'viajes', 'view' => 'viaje', 'ready' => false],
        ['name' => 'Vehículos', 'prefix' => 'vehiculos', 'view' => 'vehiculo', 'ready' => false],
        ['name' => 'Usuarios', 'prefix' => 'usuarios', 'view' => 'usuario', 'ready' => false],
        ['name' => 'Reservas', 'prefix' => 'reservas', 'view' => 'reserva', 'ready' => false],
        ['name' => 'Pagos', 'prefix' => 'pagos', 'view' => 'pago', 'ready' => false],
        ['name' => 'Clientes', 'prefix' => 'clientes', 'view' => 'cliente', 'ready' => false],
        ['name' => 'Personas', 'prefix' => 'personas', 'view' => 'persona', 'ready' => false],
        ['name' => 'Viajes recomendados', 'prefix' => 'admin.viajes-recomendados', 'view' => 'admin.viajes-recomendados', 'ready' => true],
    ];

    $modules = array_map(function (array $module): array {
        $indexRoute = $module['prefix'].'.index';
        $createRoute = $module['prefix'].'.create';

        return [
            ...$module,
            'indexRoute' => $indexRoute,
            'createRoute' => $createRoute,
            'indexAvailable' => $module['ready'] && Route::has($indexRoute) && View::exists($module['view'].'.index'),
            'createAvailable' => $module['ready'] && Route::has($createRoute) && View::exists($module['view'].'.create'),
            'deleteAvailable' => $module['ready'] && Route::has($indexRoute) && Route::has($module['prefix'].'.destroy') && View::exists($module['view'].'.index'),
        ];
    }, $modules);

    return view('desarrollo', compact('modules'));
})->middleware(['auth', 'can:access-development'])->name('desarrollo');

use App\Http\Controllers\Admin\ViajeRecomendadoController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('viajes-recomendados', ViajeRecomendadoController::class)->except(['show']);
});
