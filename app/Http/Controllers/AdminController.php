<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\InputAspirasi;
use App\Models\Kategori;
use App\Models\Siswa;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Query laporan terfilter untuk dashboard dan cetak PDF.
     */
    private function getFilteredLaporan(Request $request)
    {
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

        return $query->latest()->get();
    }

    // Menampilkan daftar aspirasi (dengan filter)
    public function index(Request $request)
    {
        // Data untuk pilihan dropdown filter
        $kategori = Kategori::all();
        $siswa = Siswa::all();

        // Jalankan query, urutkan dari yang terbaru
        $laporan = $this->getFilteredLaporan($request);

        return view('admin.index', compact('laporan', 'kategori', 'siswa'));
    }

    // Cetak daftar aspirasi siswa ke PDF sesuai filter aktif
    public function cetakPdf(Request $request)
    {
        $laporan = $this->getFilteredLaporan($request);

        $filterInfo = [];
        if ($request->tanggal) {
            $filterInfo[] = 'Tanggal: ' . date('d-m-Y', strtotime($request->tanggal));
        }
        if ($request->bulan) {
            $filterInfo[] = 'Bulan: ' . $request->bulan;
        }
        if ($request->nis) {
            $filterInfo[] = 'NIS Siswa: ' . $request->nis;
        }
        if ($request->id_kategori) {
            $kat = Kategori::find($request->id_kategori);
            if ($kat) {
                $filterInfo[] = 'Kategori: ' . $kat->ket_kategori;
            }
        }

        $pdf = Pdf::loadView('admin.pdf_aspirasi', compact('laporan', 'filterInfo'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-aspirasi-siswa.pdf');
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

    // Menampilkan halaman kelola akun
    public function kelolaAkun()
    {
        $admins = Admin::all();
        $siswas = Siswa::all();
        return view('admin.kelola_akun', compact('admins', 'siswas'));
    }

    // Admin bisa tambah admin baru
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admin,username', // Diubah dari 'admins' menjadi 'admin'
            'password' => 'required|min:6',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Admin baru berhasil ditambahkan!');
    }

    // Admin juga bisa tambah siswa baru
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nis'      => 'required|string|unique:siswa,nis',
            'nama'     => 'required|string|max:255',
            'kelas'    => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Gunakan Siswa::create, BUKAN Admin::create!
        Siswa::create([
            'nis'      => $request->nis,
            'nama'     => $request->nama,
            'kelas'    => $request->kelas,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Siswa baru berhasil ditambahkan!');
    }
}