@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    {{-- ===== 1. KOTAK DASHBOARD STATISTIK DI ATAS ===== --}}
    <h1 class="text-2xl font-bold mb-4">Dashboard Admin - Pengaduan Sarana</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow border-l-4 border-gray-500">
            <p class="text-gray-500 text-sm">Total Laporan Masuk</p>
            <p class="text-2xl font-bold">{{ $laporan->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Menunggu Tindakan</p>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $laporan->filter(fn($i) => ($i->aspirasi?->status ?? 'Menunggu') == 'Menunggu')->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Sedang Diproses</p>
            <p class="text-2xl font-bold text-blue-600">
                {{ $laporan->filter(fn($i) => $i->aspirasi?->status == 'Proses')->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Selesai</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $laporan->filter(fn($i) => $i->aspirasi?->status == 'Selesai')->count() }}
            </p>
        </div>
    </div>

    {{-- ===== 2. KOTAK KELOLA ASPIRASI & FILTER LENGKAP ===== --}}
    <h2 class="text-xl font-bold mb-3">Kelola Aspirasi</h2>
    
    <div class="bg-white p-6 rounded shadow mb-8">
        <form action="/admin" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            {{-- Filter Per Tanggal --}}
            <div>
                <label class="block text-sm font-medium mb-1">Per Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full border rounded p-2 text-sm">
            </div>

            {{-- Filter Per Bulan --}}
            <div>
                <label class="block text-sm font-medium mb-1">Per Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}" class="w-full border rounded p-2 text-sm">
            </div>

            {{-- Filter Per Siswa --}}
            <div>
                <label class="block text-sm font-medium mb-1">Per Siswa</label>
                <select name="nis" class="w-full border rounded p-2 text-sm">
                    <option value="">-- Semua Siswa --</option>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->nis }}" {{ request('nis') == $s->nis ? 'selected' : '' }}>{{ $s->nis }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Per Kategori --}}
            <div>
                <label class="block text-sm font-medium mb-1">Per Kategori</label>
                <select name="id_kategori" class="w-full border rounded p-2 text-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ request('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->ket_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Aksi Filter --}}
            <div class="flex gap-2 md:col-span-4 mt-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm font-medium hover:bg-blue-700">Filter</button>
                <a href="/admin" class="bg-gray-200 text-gray-700 px-6 py-2 rounded text-sm font-medium inline-flex items-center hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>

    {{-- ===== 3. TABEL DAFTAR ASPIRASI (DENGAN HARI & TANGGAL) ===== --}}
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-bold">Daftar Aspirasi Siswa</h3>
        <a href="{{ route('admin.cetakPdf', request()->query()) }}" target="_blank"
           class="bg-red-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-red-700 inline-flex items-center gap-2">
            Cetak PDF
        </a>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Hari</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">NIS / Pelapor</th>
                    <th class="p-3">Lokasi</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3">Tindak Lanjut (Status & Feedback)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                    @php
                        $status = $item->aspirasi?->status ?? 'Menunggu';
                        
                        $days = [
                            'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 
                            'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'
                        ];
                        $hari = $days[$item->created_at->format('D')] ?? $item->created_at->format('l');
                    @endphp
                    <tr class="border-t">
                        <td class="p-3">{{ $hari }}</td>
                        <td class="p-3">{{ $item->created_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->nis }}</td>
                        <td class="p-3">{{ $item->lokasi }}</td>
                        <td class="p-3">{{ $item->kategori->ket_kategori }}</td>
                        <td class="p-3">{{ $item->ket }}</td>
                        <td class="p-3">
                            <form action="/admin/aspirasi/{{ $item->id_pelaporan }}" method="POST" class="flex flex-col md:flex-row gap-2">
                                @csrf
                                <select name="status" class="border rounded p-1 text-sm">
                                    <option value="Menunggu" {{ $status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Proses" {{ $status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>

                                <input type="text" name="feedback" value="{{ $item->aspirasi?->feedback }}" 
                                       placeholder="Tulis feedback..." class="border rounded p-1 text-sm">

                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data laporan yang sesuai dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection