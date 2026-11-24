<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// Login & logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Halaman khusus ADMIN (dijaga middleware CheckRole)
Route::middleware('role:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);                // tampil list + filter
    Route::post('/admin/aspirasi/{id}', [AdminController::class, 'update']); // simpan status & feedback
});

// Halaman khusus SISWA
Route::middleware('role:siswa')->group(function () {
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::post('/siswa/aspirasi', [SiswaController::class, 'store']);
});