<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Nama tabel di database
    protected $table = 'siswa';

    // Kunci utama tabel ini adalah 'nis'
    protected $primaryKey = 'nis';

    // NIS diisi manual (bukan nomor otomatis)
    public $incrementing = false;

    // Tabel siswa tidak punya created_at / updated_at
    public $timestamps = false;
}