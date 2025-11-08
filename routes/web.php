<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PemateriController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\AccountController;

// Login Routes

use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== ADMIN =====
Route::middleware(['auth'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/filter', [AdminController::class, 'filterData'])->name('filterData');

        
        Route::get('/workshops',              [WorkshopController::class, 'index'])->name('workshop.index');
        Route::get('/workshops/create',       [WorkshopController::class, 'create'])->name('workshop.create');
        Route::post('/workshops/store',       [WorkshopController::class, 'store'])->name('workshop.store');
        Route::get('/workshops/{workshop}',   [WorkshopController::class, 'show'])->name('workshop.show');
        Route::get('/workshops/{workshop}/pendaftar', [PendaftaranController::class, 'index'])->name('workshop.pendaftar');
        Route::get('/workshops/{workshop}/edit', [WorkshopController::class, 'edit'])->name('workshop.edit');
        Route::put('/workshops/{workshop}', [WorkshopController::class, 'update'])->name('workshop.update');
        Route::delete('/workshops/{workshop}', [WorkshopController::class, 'destroy'])->name('workshop.destroy');
        
        // Account Management
        Route::get('/account/manage', [AccountController::class, 'index'])->name('account.manage');
        Route::post('/account/{user}/promote', [AccountController::class, 'promote'])->name('account.promote');
        Route::post('/account/{user}/demote', [AccountController::class, 'demote'])->name('account.demote');
        Route::delete('/account/{user}', [AccountController::class, 'destroy'])->name('account.destroy');
        
        // Profile Management
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/verify', [\App\Http\Controllers\Admin\ProfileController::class, 'verifyPassword'])->name('profile.verify');
        Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    });


// ===== PEMATERI =====
Route::middleware(['pemateri'])->group(function () {
    Route::get('/pemateri/dashboard', [PemateriController::class, 'index'])->name('pemateri.dashboard');
});

// ===== PENGGUNA =====
Route::middleware(['pengguna'])->group(function () {
    Route::get('/pengguna/dashboard', [PenggunaController::class, 'index'])->name('pengguna.dashboard');
});
