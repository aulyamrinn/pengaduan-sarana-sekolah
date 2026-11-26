<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    // Nama tabel di database
    protected $table = 'admin';

    // Kunci utama tabel ini adalah 'username', bukan 'id'
    protected $primaryKey = 'username';

    // Kunci utama bukan angka otomatis, tapi teks
    public $incrementing = false;
    protected $keyType = 'string';

    // Tabel admin tidak punya kolom created_at / updated_at
    public $timestamps = false;

    // TAMBAHKAN BARIS INI AGAR BISA DI-CREATE
    protected $fillable = [
        'username',
        'password',
    ];
}