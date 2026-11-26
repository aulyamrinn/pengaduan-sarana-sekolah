@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
        <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>

        {{-- Notifikasi error (Test Case 1) --}}
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @error('username')
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">Username / NIS wajib diisi.</div>
        @enderror

        {{-- Tambahan pesan error jika password kosong/salah --}}
        @error('password')
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $message }}</div>
        @enderror

        <form action="/login" method="POST">
            @csrf

            <label class="block mb-1">Username / NIS</label>
            <input type="text" name="username" value="{{ old('username') }}"
                   class="w-full border rounded p-2 mb-4" required>

            {{-- UBAH BAGIAN INI (Hapus tulisan khusus admin dan tambahkan required) --}}
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded p-2 mb-4" required>

            <button class="w-full bg-blue-600 text-white p-2 rounded">Masuk</button>
        </form>
    </div>
@endsection