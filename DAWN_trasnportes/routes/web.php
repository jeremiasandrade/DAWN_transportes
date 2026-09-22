<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;


Route::resource('reservas', ReservaController::class);
Route::resource('usuarios', UserController::class);
Route::resource('vehiculos', VehiculoController::class);
Route::resource('destinos', DestinoController::class);
Route::resource('viajes', ViajeController::class);
Route::resource('reservas', ReservaController::class);
Route::resource('pagos', PagoController::class);

// Ruta para el Dashboard / Página de inicio del usuario
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/', function () {
    return view('welcome'); // 'welcome' sería el nombre de tu archivo blade (welcome.blade.php)
});


