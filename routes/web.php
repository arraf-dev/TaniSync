<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountStatusController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/dashboard', function () {
    return redirect(auth()->user()?->dashboardRoute() ?? route('login'));
})->middleware('auth')->name('dashboard');

Route::get('/account/pending', [AccountStatusController::class, 'pending'])
    ->middleware('auth')
    ->name('account.pending');

Route::middleware(['auth', 'role:super_admin'])->prefix('platform')->name('platform.')->group(function () {
    Route::get('/dashboard', [PlatformController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizations', [PlatformController::class, 'organizations'])->name('organizations');
    Route::patch('/organizations/{organization}/approve', [PlatformController::class, 'approveOrganization'])->name('organizations.approve');
    Route::patch('/organizations/{organization}/reject', [PlatformController::class, 'rejectOrganization'])->name('organizations.reject');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/access-requests', [AdminController::class, 'accessRequests'])->name('access-requests');
    Route::patch('/access-requests/{user}/approve', [AdminController::class, 'approveAccessRequest'])->name('access-requests.approve');
    Route::patch('/access-requests/{user}/reject', [AdminController::class, 'rejectAccessRequest'])->name('access-requests.reject');
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
    Route::get('/commodities', [AdminController::class, 'commodities'])->name('commodities');
    Route::post('/commodities', [AdminController::class, 'storeCommodity'])->name('commodities.store');
    Route::patch('/commodities/{commodity}', [AdminController::class, 'updateCommodity'])->name('commodities.update');
    Route::patch('/commodities/{commodity}/toggle', [AdminController::class, 'toggleCommodity'])->name('commodities.toggle');
    Route::get('/prices', [AdminController::class, 'prices'])->name('prices');
    Route::post('/prices', [AdminController::class, 'storePrice'])->name('prices.store');
    Route::get('/harvests', [AdminController::class, 'harvests'])->name('harvests');
    Route::patch('/harvests/{harvestLog}/status', [AdminController::class, 'updateHarvestStatus'])->name('harvests.status');
    Route::get('/reports/print', [AdminController::class, 'reportPrint'])->name('reports.print');
    Route::get('/reports/export-pdf', [AdminController::class, 'reportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-xlsx', [AdminController::class, 'reportXlsx'])->name('reports.export-xlsx');
    Route::get('/reports/export-csv', [AdminController::class, 'reportCsv'])->name('reports.export-csv');
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
