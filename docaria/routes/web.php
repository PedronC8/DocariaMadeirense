<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EstatisticasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Rota pública (login)
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Estatísticas
    Route::get('/statistics', [EstatisticasController::class, 'index'])->name('statistics');

    // Encomendas - CRUD Completo
    Route::resource('orders', OrderController::class);

    // Produtos (assumindo que vais criar depois)
    Route::resource('products', ProductController::class);

    // Clientes (assumindo que vais criar depois)
    Route::resource('clients', ClientController::class);
});

// Rota de logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
