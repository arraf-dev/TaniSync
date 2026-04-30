<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/dashboard', function () {
    return redirect(auth()->user()?->dashboardRoute() ?? route('login'));
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/commodities', [AdminController::class, 'commodities'])->name('commodities');
    Route::post('/commodities', [AdminController::class, 'storeCommodity'])->name('commodities.store');
    Route::patch('/commodities/{commodity}', [AdminController::class, 'updateCommodity'])->name('commodities.update');
    Route::patch('/commodities/{commodity}/toggle', [AdminController::class, 'toggleCommodity'])->name('commodities.toggle');
    Route::get('/prices', [AdminController::class, 'prices'])->name('prices');
    Route::post('/prices', [AdminController::class, 'storePrice'])->name('prices.store');
    Route::get('/harvests', [AdminController::class, 'harvests'])->name('harvests');
    Route::patch('/harvests/{harvestLog}/status', [AdminController::class, 'updateHarvestStatus'])->name('harvests.status');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

Route::middleware(['auth', 'role:petani'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [FarmerController::class, 'dashboard'])->name('dashboard');
    Route::get('/prices', [FarmerController::class, 'prices'])->name('prices');
    Route::get('/harvests', [FarmerController::class, 'harvests'])->name('harvests');
    Route::get('/harvests/new', [FarmerController::class, 'createHarvest'])->name('harvests.create');
    Route::post('/harvests', [FarmerController::class, 'storeHarvest'])->name('harvests.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
