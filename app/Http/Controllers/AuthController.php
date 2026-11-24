<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('login');
    }

    // Memproses data yang dikirim dari form login
    public function login(Request $request)
    {
        // Kolom username/NIS wajib diisi
        $request->validate([
            'username' => 'required',
        ]);

        $input = $request->username;

        // 1. Cek apakah ini admin
        $admin = Admin::where('username', $input)->first();

        if ($admin) {
            // Bandingkan password yang diketik dengan password terenkripsi di database
            if (Hash::check((string) $request->password, $admin->password)) {
                $request->session()->regenerate(); // keamanan: buat session baru
                session(['role' => 'admin', 'user_id' => $admin->username]);
                return redirect('/admin');
            }

            return back()->with('error', 'Password salah.')->withInput();
        }

        // 2. Kalau bukan admin, cek apakah ini siswa (cukup NIS)
        $siswa = Siswa::where('nis', $input)->first();

        if ($siswa) {
            $request->session()->regenerate();
            session(['role' => 'siswa', 'user_id' => $siswa->nis]);
            return redirect('/siswa');
        }

        // 3. Tidak cocok dengan keduanya -> tampil notifikasi error
        return back()->with('error', 'Username / NIS salah.')->withInput();
    }

    // Keluar: hapus semua data session lalu kembali ke login
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }
}