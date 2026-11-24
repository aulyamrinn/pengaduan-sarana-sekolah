<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputAspirasi extends Model
{
    protected $table = 'input_aspirasi';
    protected $primaryKey = 'id_pelaporan';

    // Kolom yang boleh diisi saat menyimpan data
    protected $fillable = ['nis', 'id_kategori', 'lokasi', 'ket'];

    // Satu laporan dimiliki satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Satu laporan dikirim oleh satu siswa (BARU)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    // Satu laporan punya satu data aspirasi (status & feedback)
    public function aspirasi()
    {
        return $this->hasOne(Aspirasi::class, 'id_pelaporan', 'id_pelaporan');
    }
}