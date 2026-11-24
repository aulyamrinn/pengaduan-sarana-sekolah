<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // Nama tabel dan kunci utamanya
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    // Tabel kategori tidak punya created_at / updated_at
    public $timestamps = false;
}