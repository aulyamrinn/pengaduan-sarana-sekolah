<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data admin. Hash::make mengenkripsi password agar tidak tersimpan polos
        DB::table('admin')->insert([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // 2. Data siswa
        DB::table('siswa')->insert([
            'nis'   => 1234567890,
            'kelas' => 'XII RPL 1',
        ]);

        // 3. Data kategori (4 baris sekaligus)
        DB::table('kategori')->insert([
            ['ket_kategori' => 'Infrastruktur'],
            ['ket_kategori' => 'Kebersihan'],
            ['ket_kategori' => 'Keamanan'],
            ['ket_kategori' => 'Fasilitas Kelas'],
        ]);
    }
}