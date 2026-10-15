<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;

Route::resource('usuarios', UserController::class);
Route::resource('vehiculos', VehiculoController::class);
Route::resource('destinos', DestinoController::class);
Route::resource('viajes', ViajeController::class);
Route::resource('reservas', ReservaController::class);
Route::resource('pagos', PagoController::class);


Route::view('/', 'welcome')->name('home');

// Ruta para el Dashboard / Página de inicio del usuario
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Ruta para mostrar la vista de Login
Route::get('/login', function () {
    return view('login'); 
})->name('login');

// Ruta para mostrar la vista de Registro 
Route::get('/register', function () {
    return view('register'); 
})->name('register');
//ruta general del sistema llamada "desarrollo", usada para ver todas las vistas del sistema desde un solo lugar

Route::get('/desarrollo', function () {
    return view('desarrollo'); // 'desarrollo' accede mediante botones a todas las vistas del sistema
});
