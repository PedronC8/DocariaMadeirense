<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EstatisticasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;

// Rota pública (login)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('orders.index');
    }

    return redirect()->route('login');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    
    // Encomendas - CRUD Completo
    Route::post('orders/{order}/quick-update', [OrderController::class, 'quickUpdate'])->name('orders.quick-update');
    Route::patch('orders/{order}/items/{item}/check', [OrderController::class, 'toggleItemCheck'])->name('orders.items.check');
    Route::resource('orders', OrderController::class);

    // Produtos (assumindo que vais criar depois)
    // Route::get('products/{product}/delete', [ProductController::class,'destroyconfirm'])->name('products.delete');
    // Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::resource('products', ProductController::class);

    // Clientes (assumindo que vais criar depois)
    Route::resource('clients', ClientController::class);

    // Apenas admin: Estatísticas e Administração
    Route::middleware('admin')->group(function () {
        Route::get('/statistics', [EstatisticasController::class, 'index'])->name('statistics');
        Route::resource('users', UserController::class)->except(['show']);
    });


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
