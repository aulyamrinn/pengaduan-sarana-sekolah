@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== HEADER & TOMBOL KELOLA AKUN ===== --}}
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
        <a href="{{ route('siswa.akun') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">
            + Kelola Akun
        </a>
    </div>

    {{-- ===== RINGKASAN DASHBOARD SISWA ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow border-l-4 border-gray-500">
            <p class="text-gray-500 text-sm">Total Aspirasi Saya</p>
            <p class="text-2xl font-bold">{{ $histori->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Menunggu</p>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $histori->filter(fn($i) => ($i->aspirasi?->status ?? 'Menunggu') == 'Menunggu')->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Diproses</p>
            <p class="text-2xl font-bold text-blue-600">
                {{ $histori->filter(fn($i) => $i->aspirasi?->status == 'Proses')->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Selesai</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $histori->filter(fn($i) => $i->aspirasi?->status == 'Selesai')->count() }}
            </p>
        </div>
    </div>

    {{-- ===== FORM INPUT ASPIRASI ===== --}}
    <h2 class="text-xl font-bold mb-3">Buat Aspirasi Baru</h2>
    <form action="/siswa/aspirasi" method="POST" class="bg-white p-6 rounded shadow mb-8">
        @csrf
        <label class="block mb-1">Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi') }}" maxlength="50"
               placeholder="Contoh: Ruang Kelas XII RPL 1" class="w-full border rounded p-2 mb-4">

        <label class="block mb-1">Kategori</label>
        <select name="id_kategori" class="w-full border rounded p-2 mb-4">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                    {{ $k->ket_kategori }}
                </option>
            @endforeach
        </select>

        <label class="block mb-1">Deskripsi (maks. 50 karakter)</label>
        <input type="text" name="ket" value="{{ old('ket') }}" maxlength="50"
               placeholder="Contoh: Lampu kelas mati" class="w-full border rounded p-2 mb-4">

        <button class="bg-blue-600 text-white px-6 py-2 rounded">Kirim Aspirasi</button>
    </form>

    {{-- ===== TABEL HISTORI ASPIRASI ===== --}}
    <h2 class="text-xl font-bold mb-3">Histori Aspirasi Saya</h2>
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Lokasi</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Feedback Admin</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histori as $item)
                    @php
                        $status = $item->aspirasi?->status ?? 'Menunggu';
                        $warna = [
                            'Menunggu' => 'bg-yellow-100 text-yellow-800',
                            'Proses'   => 'bg-blue-100 text-blue-800',
                            'Selesai'  => 'bg-green-100 text-green-800',
                        ][$status];
                    @endphp
                    <tr class="border-t">
                        <td class="p-3">{{ $item->created_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->lokasi }}</td>
                        <td class="p-3">{{ $item->kategori->ket_kategori }}</td>
                        <td class="p-3">{{ $item->ket }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-sm {{ $warna }}">{{ $status }}</span>
                        </td>
                        <td class="p-3">{{ $item->aspirasi?->feedback ?? '-' }}</td>
                        <td class="p-3 flex space-x-2">
                            @if($status == 'Menunggu')
                                <a href="/siswa/aspirasi/{{ $item->id_pelaporan }}/edit" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>
                                <form action="/siswa/aspirasi/{{ $item->id_pelaporan }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                </form>
                            @else
                                <span class="text-gray-400 text-sm italic">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Belum ada aspirasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection