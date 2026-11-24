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
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->increments('id_aspirasi');
            $table->unsignedInteger('id_pelaporan')->unique(); // laporan yang ditanggapi
            $table->unsignedInteger('id_kategori');
            // enum = pilihan terbatas, awalnya 'Menunggu'
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->text('feedback')->nullable();              // boleh kosong dulu
            $table->timestamps();

            $table->foreign('id_pelaporan')->references('id_pelaporan')->on('input_aspirasi');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasi');
    }
};
