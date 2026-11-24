<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Menampilkan daftar aspirasi (dengan filter)
    public function index(Request $request)
    {
        // Data untuk pilihan dropdown filter
        $kategori = Kategori::all();
        $siswa = Siswa::all();

        // Mulai query: ambil semua laporan beserta relasinya
        $query = InputAspirasi::with(['kategori', 'siswa', 'aspirasi']);

        // Filter per TANGGAL (contoh isian: 2026-11-23)
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter per BULAN (isian dari input month berformat 2026-11)
        if ($request->bulan) {
            $tahun = substr($request->bulan, 0, 4);  // ambil '2026'
            $bulan = substr($request->bulan, 5, 2);  // ambil '11'
            $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
        }

        // Filter per SISWA (berdasarkan NIS)
        if ($request->nis) {
            $query->where('nis', $request->nis);
        }

        // Filter per KATEGORI
        if ($request->id_kategori) {
            $query->where('id_kategori', $request->id_kategori);
        }

        // Jalankan query, urutkan dari yang terbaru
        $laporan = $query->latest()->get();

        return view('admin.index', compact('laporan', 'kategori', 'siswa'));
    }

    // Menyimpan perubahan status & feedback dari admin
    public function update(Request $request, $id)
    {
        // Status harus salah satu dari tiga pilihan
        $request->validate([
            'status'   => 'required|in:Menunggu,Proses,Selesai',
            'feedback' => 'nullable|max:500',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
            'feedback.max'    => 'Feedback maksimal 500 karakter.',
        ]);

        // Cari data aspirasi berdasarkan id laporan (kalau tidak ada -> error 404)
        $aspirasi = Aspirasi::where('id_pelaporan', $id)->firstOrFail();

        // Simpan perubahan
        $aspirasi->update([
            'status'   => $request->status,
            'feedback' => $request->feedback,
        ]);

        // back() = kembali ke halaman sebelumnya (filter tetap terjaga)
        return back()->with('success', 'Status dan feedback berhasil diperbarui.');
    }
}
