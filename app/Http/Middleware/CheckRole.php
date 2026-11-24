<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // $role adalah role yang dibutuhkan halaman, contoh: 'admin' atau 'siswa'
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Role pengguna yang sedang login disimpan di session (nanti diisi saat login).
        // Kalau tidak cocok dengan role yang dibutuhkan, kembalikan ke halaman login.
        if (session('role') !== $role) {
            return redirect('/login')->with('error', 'Anda tidak punya akses ke halaman ini.');
        }

        // Kalau cocok, lanjutkan ke halaman tujuan
        return $next($request);
    }
}