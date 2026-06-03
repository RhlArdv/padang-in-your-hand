<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RiwayatKunjunganController;
use App\Http\Controllers\Api\KontributorController;
use App\Http\Controllers\Api\Admin\ApprovalController;
use App\Http\Controllers\Api\NavigasiController;

// ============================================================
// PUBLIC ROUTES — tidak perlu login
// ============================================================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Publik: lihat lokasi & kategori (tidak perlu login)
Route::get('lokasi', [LokasiController::class, 'index']);
Route::get('lokasi/nearby', [LokasiController::class, 'nearby']);
Route::get('lokasi/search', [LokasiController::class, 'search']);
Route::get('lokasi/{id}', [LokasiController::class, 'show']);
Route::get('kategori', [KategoriController::class, 'index']);
Route::get('kategori/{id}/lokasi', [KategoriController::class, 'lokasi']);
Route::get('event', [EventController::class, 'index']);
Route::get('banners', [BannerController::class, 'index']);

// Navigasi — public, tidak perlu login
Route::get('navigasi/{idLokasi}', [NavigasiController::class, 'rute']);
Route::get('navigasi/{idLokasi}/semua-mode', [NavigasiController::class, 'semuaMode']);

// Wilayah: kelurahan per kecamatan (untuk dropdown form)
Route::get('kecamatan/{id}/kelurahan', function (int $id) {
    $kelurahan = \App\Models\Kelurahan::where('id_kecamatan', $id)
        ->orderBy('nama_kelurahan')
        ->get(['id_kelurahan', 'nama_kelurahan']);

    return response()->json(['data' => $kelurahan]);
});

Route::get('kecamatan', function () {
    $kecamatan = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get(['id_kecamatan', 'nama_kecamatan']);
    return response()->json(['success' => true, 'data' => $kecamatan]);
});
Route::get('event/{id}', [EventController::class, 'show']);

// ============================================================
// PROTECTED ROUTES — wajib login (semua role)
// ============================================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('profile', [AuthController::class, 'updateProfile']); // Support POST for easier multipart file upload in mobile apps
    });

    // Review
    Route::post('lokasi/{id}/review', [ReviewController::class, 'store']);
    Route::put('review/{id}', [ReviewController::class, 'update']);
    Route::delete('review/{id}', [ReviewController::class, 'destroy']);

    // Favorit
    Route::get('favorit', [FavoritController::class, 'index']);
    Route::post('favorit/{idLokasi}', [FavoritController::class, 'toggle']); // toggle: add/remove
    Route::get('favorit/check/{idLokasi}', [FavoritController::class, 'check']);

    // Riwayat Kunjungan — tambah 2 route baru
    Route::get('riwayat', [RiwayatKunjunganController::class, 'index']);
    Route::get('riwayat/aktif', [RiwayatKunjunganController::class, 'cekAktif']);
    Route::post('riwayat/mulai/{idLokasi}', [RiwayatKunjunganController::class, 'mulaiNavigasi']);
    Route::post('riwayat/{id}/tiba', [RiwayatKunjunganController::class, 'tandaiTiba']);
    Route::delete('riwayat/{id}/batal', [RiwayatKunjunganController::class, 'batalNavigasi']);

    // Pengaduan
    Route::get('pengaduan', [PengaduanController::class, 'index']);
    Route::post('pengaduan', [PengaduanController::class, 'store']);
    Route::get('pengaduan/{id}', [PengaduanController::class, 'show']);

    // -------------------------------------------------------
    // KONTRIBUTOR — role: kontributor, admin, operator, super_admin
    // -------------------------------------------------------
    Route::middleware('role:kontributor,operator,admin,super_admin')->group(function () {
        Route::get('kontributor/lokasi', [KontributorController::class, 'index']);
        Route::post('kontributor/lokasi', [KontributorController::class, 'store']);
        Route::put('kontributor/lokasi/{id}', [KontributorController::class, 'update']);
        Route::delete('kontributor/lokasi/{id}', [KontributorController::class, 'destroy']);
        Route::post('kontributor/lokasi/{id}/foto', [KontributorController::class, 'uploadFoto']);
        Route::delete('kontributor/lokasi/{id}/foto/{idFoto}', [KontributorController::class, 'hapusFoto']);
    });

    // -------------------------------------------------------
    // ADMIN & OPERATOR — kelola approval, event, pengaduan
    // -------------------------------------------------------
    Route::middleware('role:operator,admin,super_admin')->prefix('admin')->group(function () {

        // Approval kontributor
        Route::get('approval', [ApprovalController::class, 'index']);
        Route::post('approval/{id}/approve', [ApprovalController::class, 'approve']);
        Route::post('approval/{id}/tolak', [ApprovalController::class, 'tolak']);
        Route::post('approval/{id}/revisi', [ApprovalController::class, 'revisi']);

        // Kelola event
        Route::post('event', [EventController::class, 'store']);
        Route::put('event/{id}', [EventController::class, 'update']);
        Route::delete('event/{id}', [EventController::class, 'destroy']);

        // Tindak lanjut pengaduan
        Route::put('pengaduan/{id}', [PengaduanController::class, 'update']);
    });

    // -------------------------------------------------------
    // SUPER ADMIN & ADMIN — kelola master data & user
    // -------------------------------------------------------
    Route::middleware('role:admin,super_admin')->prefix('admin')->group(function () {
        Route::apiResource('kategori', KategoriController::class)
            ->except(['index', 'show']);

        Route::get('users', [\App\Http\Controllers\Api\Admin\UserController::class, 'index']);
        Route::put('users/{id}/role', [\App\Http\Controllers\Api\Admin\UserController::class, 'updateRole']);
        Route::delete('users/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'destroy']);
    });
});