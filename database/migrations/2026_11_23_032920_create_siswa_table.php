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
        Schema::create('siswa', function (Blueprint $table) {
            $table->unsignedInteger('nis')->primary(); // NIS (angka 10 digit)
            $table->string('kelas', 10);               // contoh: XII RPL 1
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
