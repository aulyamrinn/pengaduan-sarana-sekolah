@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="text-center mt-20">
        <h1 class="text-3xl font-bold mb-2">Aplikasi Pengaduan Sarana Sekolah</h1>
        <p class="text-gray-600 mb-6">Sampaikan keluhan sarana dan prasarana sekolah dengan mudah.</p>

        {{-- Kalau sudah login, tombol menuju halaman sesuai role. Kalau belum, tombol Login --}}
        @if (session('role') == 'admin')
            <a href="/admin" class="bg-blue-600 text-white px-6 py-2 rounded">Ke Halaman Admin</a>
        @elseif (session('role') == 'siswa')
            <a href="/siswa" class="bg-blue-600 text-white px-6 py-2 rounded">Ke Halaman Siswa</a>
        @else
            <a href="/login" class="bg-blue-600 text-white px-6 py-2 rounded">Login</a>
        @endif
    </div>
@endsection