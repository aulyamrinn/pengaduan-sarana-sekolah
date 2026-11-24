<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->increments('id_pelaporan');            // ID laporan otomatis
            $table->unsignedInteger('nis');                // siswa pelapor
            $table->unsignedInteger('id_kategori');        // kategori laporan
            $table->string('lokasi', 50);                  // lokasi kerusakan
            $table->string('ket', 50);                     // keterangan/deskripsi
            $table->timestamps();                          // created_at (untuk filter tanggal)

            // Foreign Key: harus cocok dengan data di tabel induk
            $table->foreign('nis')->references('nis')->on('siswa');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_aspirasi');
    }
};
