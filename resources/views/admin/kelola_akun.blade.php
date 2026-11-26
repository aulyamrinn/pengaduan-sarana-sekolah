@extends('layouts.app')

@section('title', 'Kelola Akun Admin & Siswa')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Akun Pengguna</h1>
        <a href="/admin" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-medium hover:bg-gray-300">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-6 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- GRID FORM TAMBAH ADMIN & SISWA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        {{-- Card Tambah Admin --}}
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Admin Baru</h2>
            <form action="{{ route('admin.storeAdmin') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username Admin</label>
                    <input type="text" name="username" class="w-full border rounded p-2 text-sm" placeholder="Contoh: admin2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded p-2 text-sm" placeholder="Minimal 6 karakter" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">
                    Simpan Admin
                </button>
            </form>
        </div>

        {{-- Card Tambah Siswa --}}
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Siswa Baru</h2>
            <form action="{{ route('admin.storeSiswa') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                    <input type="text" name="nis" class="w-full border rounded p-2 text-sm" placeholder="Contoh: 1234567890" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                    <input type="text" name="nama" class="w-full border rounded p-2 text-sm" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <input type="text" name="kelas" class="w-full border rounded p-2 text-sm" placeholder="Contoh: XII RPL" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (Untuk Login Siswa)</label>
                    <input type="password" name="password" class="w-full border rounded p-2 text-sm" placeholder="Minimal 6 karakter" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">
                    Simpan Siswa
                </button>
            </form>
        </div>

    </div>

    {{-- TABEL DAFTAR AKUN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Tabel Daftar Admin --}}
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Admin Terdaftar</h2>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-2">Username Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $adm)
                    <tr class="border-b">
                        <td class="p-2 font-medium">{{ $adm->username }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tabel Daftar Siswa --}}
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Siswa Terdaftar</h2>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-2">NIS</th>
                        <th class="p-2">Nama</th>
                        <th class="p-2">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $sw)
                    <tr class="border-b">
                        <td class="p-2 font-medium">{{ $sw->nis }}</td>
                        <td class="p-2">{{ $sw->nama }}</td>
                        <td class="p-2">{{ $sw->kelas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection