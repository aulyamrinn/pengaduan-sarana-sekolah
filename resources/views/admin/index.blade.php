@extends('layouts.app')

@section('title', 'Halaman Admin')

@section('content')

    {{-- Pesan sukses (hilang otomatis lewat script.js) --}}
    @if (session('success'))
        <div class="pesan-sukses bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">Kelola Aspirasi</h1>

    {{-- ===== BAGIAN 1: FORM FILTER ===== --}}
    {{-- request('nama') mengambil isian filter sebelumnya supaya pilihan tidak hilang --}}
    <form action="/admin" method="GET" class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">

            <div>
                <label class="block mb-1 text-sm">Per Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-1 text-sm">Per Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}"
                       class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-1 text-sm">Per Siswa</label>
                <select name="nis" class="w-full border rounded p-2">
                    <option value="">-- Semua Siswa --</option>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->nis }}" {{ request('nis') == $s->nis ? 'selected' : '' }}>
                            {{ $s->nis }} - {{ $s->kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm">Per Kategori</label>
                <select name="id_kategori" class="w-full border rounded p-2">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id_kategori }}"
                            {{ request('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                            {{ $k->ket_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        <a href="/admin" class="ml-2 text-gray-600 underline">Reset</a>
    </form>

    {{-- ===== BAGIAN 2: TABEL ASPIRASI ===== --}}
    <p class="mb-2 text-gray-600">Total: {{ $laporan->count() }} aspirasi</p>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Siswa</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Lokasi</th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Ubah Status & Feedback</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                    @php
                        // Ambil status (kalau belum ada, anggap 'Menunggu')
                        $status = $item->aspirasi?->status ?? 'Menunggu';

                        // Warna label sesuai status
                        $warna = [
                            'Menunggu' => 'bg-yellow-100 text-yellow-800',
                            'Proses'   => 'bg-blue-100 text-blue-800',
                            'Selesai'  => 'bg-green-100 text-green-800',
                        ][$status];
                    @endphp

                    <tr class="border-t align-top">
                        <td class="p-3">{{ $item->created_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->nis }}<br><span class="text-gray-500">{{ $item->siswa?->kelas }}</span></td>
                        <td class="p-3">{{ $item->kategori->ket_kategori }}</td>
                        <td class="p-3">{{ $item->lokasi }}</td>
                        <td class="p-3">{{ $item->ket }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded {{ $warna }}">{{ $status }}</span>
                        </td>

                        {{-- Form kecil untuk mengubah status & mengisi feedback --}}
                        <td class="p-3">
                            <form action="/admin/aspirasi/{{ $item->id_pelaporan }}" method="POST"
                                  class="flex flex-col gap-2">
                                @csrf

                                <select name="status" class="border rounded p-1">
                                    @foreach (['Menunggu', 'Proses', 'Selesai'] as $pilihan)
                                        <option value="{{ $pilihan }}" {{ $status == $pilihan ? 'selected' : '' }}>
                                            {{ $pilihan }}
                                        </option>
                                    @endforeach
                                </select>

                                <input type="text" name="feedback" value="{{ $item->aspirasi?->feedback }}"
                                       placeholder="Tulis feedback untuk siswa..."
                                       class="border rounded p-1">

                                <button class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            Tidak ada aspirasi yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection