<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingsController;

// ── Auth (public) ─────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Protected (requires login) ─────────────────────────────────
Route::middleware('auth.company')->group(function () {
    Route::get('/',           [DashboardController::class,  'index'])->name('dashboard');
    Route::resource('parts',        PartController::class);
    Route::resource('suppliers',    SupplierController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories',   CategoryController::class);
    Route::get('/settings',         [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings',        [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/export/manifest',  [SettingsController::class, 'exportManifest'])->name('export.manifest');
});

// ── Super Admin (Portal Control) ────────────────────────────────
Route::middleware(['auth'])->prefix('control-tower')->name('super-admin.')->group(function () {
    Route::get('/dashboard',        [\App\Http\Controllers\SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tenants/{id}',      [\App\Http\Controllers\SuperAdminController::class, 'showTenant'])->name('tenants.show');
    Route::get('/impersonate/{id}', [\App\Http\Controllers\SuperAdminController::class, 'impersonate'])->name('impersonate');
    Route::get('/stop-impersonation', [\App\Http\Controllers\SuperAdminController::class, 'stopImpersonating'])->name('stop-impersonate');
    Route::get('/companies/create', [\App\Http\Controllers\SuperAdminController::class, 'createCompany'])->name('companies.create');
    Route::post('/companies',       [\App\Http\Controllers\SuperAdminController::class, 'storeCompany'])->name('companies.store');
});
