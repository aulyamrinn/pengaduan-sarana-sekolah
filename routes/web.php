<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;

// Halaman utama / redirect
Route::get('/', function () {
    return redirect('/login');
});

// Login & logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Halaman khusus ADMIN (dijaga middleware CheckRole)
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/aspirasi/cetak-pdf', [AdminController::class, 'cetakPdf'])->name('admin.cetakPdf');
    Route::post('/admin/aspirasi/{id}', [AdminController::class, 'update']);
    
    // Route untuk kelola akun
    Route::get('/admin/akun', [AdminController::class, 'kelolaAkun'])->name('admin.users');
    Route::post('/admin/akun/tambah-admin', [AdminController::class, 'storeAdmin'])->name('admin.storeAdmin');
    Route::post('/admin/akun/tambah-siswa', [AdminController::class, 'storeSiswa'])->name('admin.storeSiswa');
    });

    // Halaman khusus SISWA
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/siswa/aspirasi', [SsiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{id}/edit', [SsiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{id}', [SsiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{id}', [SsiswaController::class, 'destroy'])->name('siswa.destroy');
        
        // Tambahkan route ini agar siswa bisa membuka menu kelola/tambah akun
        Route::get('/siswa/akun', [SiswaController::class, 'kelolaAkun'])->name('siswa.akun');
        Route::post('/siswa/akun/tambah', [SiswaController::class, 'storeSiswa'])->name('siswa.storeSiswa');
    });