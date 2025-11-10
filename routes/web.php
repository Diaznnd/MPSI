<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Pemateri\PemateriController;
use App\Http\Controllers\Pengguna\PenggunaController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\RequestWorkshopController;

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
        Route::put('/workshops/{workshop}/status', [WorkshopController::class, 'updateStatus'])->name('workshop.updateStatus');
        Route::delete('/workshops/{workshop}', [WorkshopController::class, 'destroy'])->name('workshop.destroy');
        
        // Request Workshop Management
        Route::get('/request', [RequestWorkshopController::class, 'index'])->name('request.index');
        Route::get('/request/{request_id}', [RequestWorkshopController::class, 'show'])->name('request.show');
        Route::put('/request/{request_id}/status', [RequestWorkshopController::class, 'updateStatus'])->name('request.updateStatus');
        
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
Route::middleware(['auth', 'pemateri'])->group(function () {
    Route::get('/pemateri/dashboard', [PemateriController::class, 'index'])->name('pemateri.dashboard');
    
    // Materi Workshop Routes
    Route::get('/pemateri/materi', [\App\Http\Controllers\Pemateri\MateriController::class, 'index'])->name('pemateri.materi.index');
    Route::get('/pemateri/materi/{workshop_id}/create', [\App\Http\Controllers\Pemateri\MateriController::class, 'create'])->name('pemateri.materi.create');
    Route::post('/pemateri/materi/{workshop_id}/store', [\App\Http\Controllers\Pemateri\MateriController::class, 'store'])->name('pemateri.materi.store');
    Route::delete('/pemateri/materi/{materi_id}', [\App\Http\Controllers\Pemateri\MateriController::class, 'destroy'])->name('pemateri.materi.destroy');
});

// ===== PENGGUNA =====
Route::middleware(['auth', 'pengguna'])->group(function () {
    Route::get('/pengguna/dashboard', [PenggunaController::class, 'index'])->name('pengguna.dashboard');
    Route::get('/pengguna/my-workshop', [PenggunaController::class, 'myWorkshop'])->name('pengguna.my-workshop');
    Route::get('/pengguna/daftar-workshop', [PenggunaController::class, 'daftarWorkshop'])->name('pengguna.daftar-workshop');
    Route::get('/pengguna/request-workshop', [PenggunaController::class, 'requestWorkshop'])->name('pengguna.request-workshop');
    Route::post('/pengguna/request-workshop', [PenggunaController::class, 'storeRequestWorkshop'])->name('pengguna.request-workshop.store');
    Route::get('/pengguna/workshop/{workshop_id}/detail', [PenggunaController::class, 'workshopDetail'])->name('pengguna.workshop.detail');
    Route::post('/pengguna/workshop/{workshop_id}/register', [PenggunaController::class, 'registerWorkshop'])->name('pengguna.workshop.register');
});

// ===== DOWNLOAD MATERI (for all authenticated users - access control in controller) =====
Route::middleware(['auth'])->group(function () {
    Route::get('/pengguna/materi/{materi_id}/download', [\App\Http\Controllers\Pemateri\MateriController::class, 'download'])->name('pengguna.materi.download');
});
