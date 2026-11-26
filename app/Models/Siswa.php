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

    // Karena NIS berupa string/karakter, tentukan tipe datanya (opsional tapi aman)
    protected $keyType = 'string';

    // Tabel siswa tidak punya created_at / updated_at
    public $timestamps = false;

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nis',
        'password', // <--- TAMBAHKAN INI
        'nama',
        'kelas',    // sesuaikan dengan kolom lain yang ada di tabel kamu
    ];

    // Menyembunyikan password saat data dikonversi ke JSON/Array
    protected $hidden = [
        'password',
    ];

    // Otomatis mengenkripsi password (hash) saat disimpan
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}