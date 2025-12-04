<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilmController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes films protégées par authentification
Route::middleware('auth')->group(function () {
    Route::get('/films', [FilmController::class, 'index'])->name('films.index');
    Route::get('/films/create', [FilmController::class, 'create'])->name('films.create');
    Route::post('/films', [FilmController::class, 'store'])->name('films.store');
    Route::get('/films/{id}', [FilmController::class, 'show'])->name('films.show');
    Route::get('/films/{id}/edit', [FilmController::class, 'edit'])->name('films.edit');
    Route::put('/films/{id}', [FilmController::class, 'update'])->name('films.update');
    Route::delete('/films/{id}', [FilmController::class, 'destroy'])->name('films.destroy');

    // Routes pour la gestion du stock
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{storeId}', [App\Http\Controllers\InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/inventory/{storeId}/create', [App\Http\Controllers\InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory/{storeId}', [App\Http\Controllers\InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{storeId}/film/{filmId}', [App\Http\Controllers\InventoryController::class, 'detail'])->name('inventory.detail');
    Route::get('/inventory/{storeId}/film/{filmId}/edit', [App\Http\Controllers\InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{storeId}/film/{filmId}', [App\Http\Controllers\InventoryController::class, 'update'])->name('inventory.update');
});
