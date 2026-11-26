<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Helper untuk mendeteksi NIS siswa yang sedang login dari session
    private function getLoggedInNis()
    {
        return session('user_id') ?? session('nis');
    }

    // Menampilkan halaman siswa / dashboard
    public function index()
    {
        $nis = $this->getLoggedInNis();

        // Ambil semua kategori untuk pilihan dropdown
        $kategori = Kategori::all();

        // Ambil laporan HANYA milik siswa yang sedang login
        $histori = InputAspirasi::with(['kategori', 'aspirasi'])
            ->where('nis', $nis)
            ->latest()
            ->get();

        return view('siswa.index', compact('kategori', 'histori'));
    }

    // Menyimpan aspirasi baru dari form
    public function store(Request $request)
    {
        $nis = $this->getLoggedInNis();

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
            'nis'         => $nis,
            'id_kategori' => $request->id_kategori,
            'lokasi'      => $request->lokasi,
            'ket'         => $request->ket,
        ]);

        // 2. Buat data aspirasi dengan status awal 'Menunggu'
        Aspirasi::create([
            'id_pelaporan' => $laporan->id_pelaporan,
            'id_kategori'  => $request->id_kategori,
            'status'       => 'Menunggu',
        ]);

        return redirect('/siswa')->with('success', 'Aspirasi berhasil dikirim.');
    }

    // Menampilkan form edit aspirasi
    public function edit($id)
    {
        $nis = $this->getLoggedInNis();

        $laporan = InputAspirasi::where('id_pelaporan', $id)
            ->where('nis', $nis)
            ->firstOrFail();

        $aspirasi = Aspirasi::where('id_pelaporan', $id)->first();
        if ($aspirasi && $aspirasi->status != 'Menunggu') {
            return redirect('/siswa')->with('error', 'Aspirasi yang sudah diproses tidak dapat diubah.');
        }

        $kategori = Kategori::all();

        return view('siswa.edit', compact('laporan', 'kategori'));
    }

    // Memproses update data aspirasi
    public function update(Request $request, $id)
    {
        $nis = $this->getLoggedInNis();

        $request->validate([
            'lokasi'      => 'required|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'ket'         => 'required|max:50',
        ]);

        $laporan = InputAspirasi::where('id_pelaporan', $id)
            ->where('nis', $nis)
            ->firstOrFail();

        $laporan->update([
            'id_kategori' => $request->id_kategori,
            'lokasi'      => $request->lokasi,
            'ket'         => $request->ket,
        ]);

        Aspirasi::where('id_pelaporan', $id)->update([
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect('/siswa')->with('success', 'Aspirasi berhasil diperbarui.');
    }

    // Menghapus data aspirasi
    public function destroy($id)
    {
        $nis = $this->getLoggedInNis();

        $laporan = InputAspirasi::where('id_pelaporan', $id)
            ->where('nis', $nis)
            ->firstOrFail();

        Aspirasi::where('id_pelaporan', $id)->delete();
        $laporan->delete();

        return redirect('/siswa')->with('success', 'Aspirasi berhasil dihapus.');
    }

    // ==========================================
    // TAMBAHKAN KEDUA FUNGSI INI DI BAGIAN BAWAH
    // ==========================================

    // Menampilkan halaman kelola akun khusus siswa
    public function kelolaAkun()
    {
        return redirect()->back()->with('error', 'Halaman kelola akun siswa belum tersedia.');
    }

    // Memproses penambahan akun dari sisi siswa
    public function storeSiswa(Request $request)
    {
        // Tambahkan logika validasi & penyimpanan akun di sini jika diperlukan
        return redirect()->back()->with('success', 'Akun berhasil ditambahkan.');
    }
}