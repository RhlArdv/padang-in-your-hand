<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect /dashboard ke /admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// ADMIN PANEL — hanya super_admin, admin, operator
// ============================================================
Route::middleware(['auth', 'CheckRole:super_admin,admin,operator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Master Lokasi
        Route::resource('lokasi', LokasiController::class);

        // Approval Kontributor
        Route::get('approval', [ApprovalController::class, 'index'])->name('approval.index');
        Route::get('approval/{id}', [ApprovalController::class, 'show'])->name('approval.show');
        Route::post('approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
        Route::post('approval/{id}/revision', [ApprovalController::class, 'revision'])->name('approval.revision');

        // Event Kota
        Route::resource('events', EventController::class);

        // Banner Mobile
        Route::resource('banners', BannerController::class);

        // GIS & Maps
        Route::get('map', [MapController::class, 'index'])->name('map.index');
        Route::get('map/lokasi/{id}/popup', [MapController::class, 'popupData'])->name('map.popup');

        // Pengaduan
        Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::put('pengaduan/{id}', [PengaduanController::class, 'update'])->name('pengaduan.update');

        // Manajemen User — hanya super_admin & admin
        Route::middleware('CheckRole:super_admin,admin')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::put('users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
            Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        });
    });

require __DIR__ . '/auth.php';
