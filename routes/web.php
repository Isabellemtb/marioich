<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return redirect()->route('login');
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
    Route::post('/inventory/{storeId}/film/{filmId}/transfer', [App\Http\Controllers\InventoryController::class, 'transfer'])->name('inventory.transfer');
    Route::delete('/inventory/{storeId}/film/{filmId}/item/{inventoryId}', [App\Http\Controllers\InventoryController::class, 'deleteItem'])->name('inventory.delete-item');

    // Routes pour la gestion des locations
    Route::resource('rental', App\Http\Controllers\RentalController::class);
    Route::post('/rental/{id}/return', [App\Http\Controllers\RentalController::class, 'returnRental'])->name('rental.return');

    //Routes pour la gestion des utilisateurs
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');

    Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Routes pour la gestion des comptes bloqués
    Route::get('/admin/comptes-bloques', [AdminController::class, 'index'])->name('admin.locked-accounts');
    Route::get('/api/admin/locked-accounts', [AdminController::class, 'getLockedAccounts']);
    Route::post('/api/admin/unlock/{id}', [AdminController::class, 'unlockAccount']);
});
