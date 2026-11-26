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
    
    // Route untuk kelola akun (CRUD Admin & Siswa)
    Route::get('/admin/akun', [AdminController::class, 'kelolaAkun'])->name('admin.users');
    Route::post('/admin/akun/tambah-admin', [AdminController::class, 'storeAdmin'])->name('admin.storeAdmin');
    Route::put('/admin/akun/admin/{username}', [AdminController::class, 'updateAdmin'])->name('admin.updateAdmin');
    Route::delete('/admin/akun/admin/{username}', [AdminController::class, 'destroyAdmin'])->name('admin.destroyAdmin');
    Route::post('/admin/akun/tambah-siswa', [AdminController::class, 'storeSiswa'])->name('admin.storeSiswa');
    Route::put('/admin/akun/siswa/{nis}', [AdminController::class, 'updateSiswa'])->name('admin.updateSiswa');
    Route::delete('/admin/akun/siswa/{nis}', [AdminController::class, 'destroySiswa'])->name('admin.destroySiswa');
    });

    // Halaman khusus SISWA
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/siswa/aspirasi', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/aspirasi/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/aspirasi/{id}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/aspirasi/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        
        // Tambahkan route ini agar siswa bisa membuka menu kelola/tambah akun
        Route::get('/siswa/akun', [SiswaController::class, 'kelolaAkun'])->name('siswa.akun');
        Route::post('/siswa/akun/tambah', [SiswaController::class, 'storeSiswa'])->name('siswa.storeSiswa');
    });