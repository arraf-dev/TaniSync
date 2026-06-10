<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/dashboard', function () {
    return redirect(auth()->user()?->dashboardRoute() ?? route('login'));
})->middleware('auth')->name('dashboard');

// ─── Admin Routes ────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Commodities CRUD
    Route::get('/commodities', [AdminController::class, 'commodities'])->name('commodities');
    Route::get('/commodities/create', [AdminController::class, 'createCommodity'])->name('commodities.create');
    Route::post('/commodities', [AdminController::class, 'storeCommodity'])->name('commodities.store');
    Route::get('/commodities/{commodity}/edit', [AdminController::class, 'editCommodity'])->name('commodities.edit');
    Route::put('/commodities/{commodity}', [AdminController::class, 'updateCommodity'])->name('commodities.update');
    Route::delete('/commodities/{commodity}', [AdminController::class, 'deleteCommodity'])->name('commodities.delete');

    // Prices
    Route::get('/prices', [AdminController::class, 'prices'])->name('prices');
    Route::get('/prices/create', [AdminController::class, 'createPrice'])->name('prices.create');
    Route::post('/prices', [AdminController::class, 'storePrice'])->name('prices.store');

    // Harvests
    Route::get('/harvests', [AdminController::class, 'harvests'])->name('harvests');
    Route::patch('/harvests/{harvest}/status', [AdminController::class, 'updateHarvestStatus'])->name('harvests.status');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export-csv', [AdminController::class, 'exportCsv'])->name('reports.export-csv');
});

// ─── SuperAdmin Routes ───────────────────────────────────────
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}', [SuperAdminController::class, 'deleteUser'])->name('users.delete');
});

// ─── Petani Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:petani'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [FarmerController::class, 'dashboard'])->name('dashboard');
    Route::get('/prices', [FarmerController::class, 'prices'])->name('prices');
    Route::get('/harvests', [FarmerController::class, 'harvests'])->name('harvests');
    Route::get('/harvests/new', [FarmerController::class, 'createHarvest'])->name('harvests.create');
    Route::post('/harvests', [FarmerController::class, 'storeHarvest'])->name('harvests.store');
});

// ─── Profile ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
