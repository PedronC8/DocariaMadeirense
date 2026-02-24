<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

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