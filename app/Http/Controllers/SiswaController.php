<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Menampilkan halaman siswa
    public function index()
    {
        // Ambil semua kategori untuk pilihan dropdown
        $kategori = Kategori::all();

        // Ambil laporan HANYA milik siswa yang sedang login (nis disimpan di session),
        // urut dari yang terbaru. 'with' memuat relasi sekaligus agar query lebih efisien.
        $histori = InputAspirasi::with(['kategori', 'aspirasi'])
            ->where('nis', session('user_id'))
            ->latest()
            ->get();

        return view('siswa.index', compact('kategori', 'histori'));
    }

    // Menyimpan aspirasi baru dari form
    public function store(Request $request)
    {
        // Periksa isian form, kalau salah akan kembali dengan pesan error
        $request->validate([
            'lokasi'      => 'required|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'ket'         => 'required|max:50',
        ], [
            'lokasi.required'      => 'Lokasi wajib diisi.',
            'lokasi.max'           => 'Lokasi maksimal 50 karakter.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori tidak valid.',
            'ket.required'         => 'Deskripsi wajib diisi.',
            'ket.max'              => 'Deskripsi maksimal 50 karakter.',
        ]);

        // 1. Simpan laporan ke tabel input_aspirasi
        $laporan = InputAspirasi::create([
            'nis'         => session('user_id'),
            'id_kategori' => $request->id_kategori,
            'lokasi'      => $request->lokasi,
            'ket'         => $request->ket,
        ]);

        // 2. Buat data aspirasi dengan status awal 'Menunggu' (Test Case 2)
        Aspirasi::create([
            'id_pelaporan' => $laporan->id_pelaporan,
            'id_kategori'  => $request->id_kategori,
            'status'       => 'Menunggu',
        ]);

        // Kembali ke halaman siswa dengan pesan sukses
        return redirect('/siswa')->with('success', 'Aspirasi berhasil dikirim.');
    }
}
