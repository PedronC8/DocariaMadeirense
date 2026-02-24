<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return view('welcome');
});


    Route::resource('clients', ClientController::class);
   

// Rota de logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');