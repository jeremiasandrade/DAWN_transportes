<?php

use App\Http\Controllers\DestinoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ViajeController;
use Illuminate\Support\Facades\Route;

Route::resource('usuarios', UserController::class);
Route::resource('vehiculos', VehiculoController::class);
Route::resource('destinos', DestinoController::class);
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
// ruta general del sistema llamada "desarrollo", usada para ver todas las vistas del sistema desde un solo lugar

Route::get('/desarrollo', function () {
    return view('desarrollo'); // 'desarrollo' accede mediante botones a todas las vistas del sistema
});
