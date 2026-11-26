<?php

namespace App\Http\Controllers;

use App\Models\Admin;  // <-- Kembalikan ke model Admin
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $input = $request->username;

        // 1. Cek apakah ini admin (dari model Admin)
        $admin = Admin::where('username', $input)->first();

        if ($admin) {
            if (Hash::check((string) $request->password, $admin->password)) {
                $request->session()->regenerate();
                session(['role' => 'admin', 'user_id' => $admin->username]);
                return redirect('/admin');
            }

            return back()->with('error', 'Password admin salah.')->withInput();
        }

        // 2. Cek apakah ini siswa (berdasarkan NIS atau Nama)
        $siswa = Siswa::where('nis', $input)
                      ->orWhere('nama', $input)
                      ->first();

        if ($siswa) {
            if (Hash::check((string) $request->password, $siswa->password)) {
                $request->session()->regenerate();
                session([
                    'role'    => 'siswa',
                    'user_id' => $siswa->nis,
                    'nama'    => $siswa->nama ?? $siswa->nis,
                ]);
                return redirect('/siswa');
            }

            return back()->with('error', 'Password siswa salah.')->withInput();
        }

        // 3. Tidak cocok dengan keduanya
        return back()->with('error', 'Username / NIS / Nama tidak ditemukan.')->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }
}