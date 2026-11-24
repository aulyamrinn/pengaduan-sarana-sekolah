<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $table = 'aspirasi';
    protected $primaryKey = 'id_aspirasi';

    // Kolom yang boleh diisi
    protected $fillable = ['id_pelaporan', 'id_kategori', 'status', 'feedback'];
}
