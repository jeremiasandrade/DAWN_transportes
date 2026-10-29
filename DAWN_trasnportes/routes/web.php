<?php

use App\Http\Controllers\DestinoController;
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
Route::resource('rutas', RutaController::class);
Route::resource('pasajes', PasajeController::class);
Route::resource('viajes', ViajeController::class);
Route::resource('reservas', ReservaController::class);
Route::resource('pagos', PagoController::class);

Route::view('/', 'home')->name('home');

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
        ['name' => 'Destinos', 'prefix' => 'destinos', 'view' => 'destino', 'uri' => 'destinos', 'parameter' => 'destino'],
        ['name' => 'Rutas', 'prefix' => 'rutas', 'view' => 'ruta', 'uri' => 'rutas', 'parameter' => 'ruta'],
        ['name' => 'Pasajes', 'prefix' => 'pasajes', 'view' => 'pasaje', 'uri' => 'pasajes', 'parameter' => 'pasaje'],
        ['name' => 'Viajes', 'prefix' => 'viajes', 'view' => 'viaje', 'uri' => 'viajes', 'parameter' => 'viaje'],
        ['name' => 'Vehículos', 'prefix' => 'vehiculos', 'view' => 'vehiculo', 'uri' => 'vehiculos', 'parameter' => 'vehiculo'],
        ['name' => 'Usuarios', 'prefix' => 'usuarios', 'view' => 'usuario', 'uri' => 'usuarios', 'parameter' => 'usuario'],
        ['name' => 'Reservas', 'prefix' => 'reservas', 'view' => 'reserva', 'uri' => 'reservas', 'parameter' => 'reserva'],
        ['name' => 'Pagos', 'prefix' => 'pagos', 'view' => 'pago', 'uri' => 'pagos', 'parameter' => 'pago'],
        ['name' => 'Clientes', 'prefix' => 'clientes', 'view' => 'cliente', 'uri' => 'clientes', 'parameter' => 'cliente'],
        ['name' => 'Personas', 'prefix' => 'personas', 'view' => 'persona', 'uri' => 'personas', 'parameter' => 'persona'],
    ];

    $actions = [
        'index' => ['label' => 'Listado', 'method' => 'GET', 'uri' => ''],
        'create' => ['label' => 'Formulario de creación', 'method' => 'GET', 'uri' => '/create'],
        'store' => ['label' => 'Guardar nuevo registro', 'method' => 'POST', 'uri' => ''],
        'show' => ['label' => 'Detalle', 'method' => 'GET', 'uri' => '/{parameter}'],
        'edit' => ['label' => 'Formulario de edición', 'method' => 'GET', 'uri' => '/{parameter}/edit'],
        'update' => ['label' => 'Actualizar registro', 'method' => 'PUT/PATCH', 'uri' => '/{parameter}'],
        'destroy' => ['label' => 'Eliminar registro', 'method' => 'DELETE', 'uri' => '/{parameter}'],
    ];

    $modules = array_map(function (array $module) use ($actions): array {
        $module['actions'] = array_map(function (string $action, array $details) use ($module): array {
            $routeName = $module['prefix'].'.'.$action;
            $viewName = in_array($action, ['index', 'create', 'show', 'edit'], true)
                ? $module['view'].'.'.$action
                : null;

            return [
                ...$details,
                'uri' => '/'.$module['uri'].str_replace('{parameter}', '{'.$module['parameter'].'}', $details['uri']),
                'routeName' => $routeName,
                'routeExists' => Route::has($routeName),
                'viewName' => $viewName,
                'viewExists' => $viewName !== null && View::exists($viewName),
            ];
        }, array_keys($actions), array_values($actions));

        return $module;
    }, $modules);

    $pages = [
        ['name' => 'Inicio', 'route' => 'home', 'view' => 'home'],
        ['name' => 'Iniciar sesión', 'route' => 'login', 'view' => 'login'],
        ['name' => 'Registro', 'route' => 'register', 'view' => 'register'],
        ['name' => 'Perfil', 'route' => 'profile', 'view' => 'usuario.perfil'],
        ['name' => 'Desarrollo', 'route' => 'desarrollo', 'view' => 'desarrollo'],
    ];

    $pages = array_map(fn (array $page): array => [
        ...$page,
        'routeExists' => Route::has($page['route']),
        'viewExists' => View::exists($page['view']),
    ], $pages);

    $registeredRoutes = collect(Route::getRoutes())->map(fn ($route): array => [
        'methods' => implode('|', $route->methods()),
        'uri' => '/'.$route->uri(),
        'name' => $route->getName() ?? 'Sin nombre',
    ])->sortBy('uri')->values();

    return view('desarrollo', compact('modules', 'pages', 'registeredRoutes'));
})->middleware(['auth', 'can:access-development'])->name('desarrollo');
