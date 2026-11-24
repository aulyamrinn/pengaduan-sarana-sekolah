@extends('layouts.app')

@section('title', 'Halaman Siswa')

@section('content')

    {{-- Pesan sukses setelah mengirim aspirasi --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error validasi (kalau ada isian yang salah) --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== BAGIAN 1: FORM INPUT ASPIRASI ===== --}}
    <h1 class="text-2xl font-bold mb-4">Form Aspirasi</h1>

    <form action="/siswa/aspirasi" method="POST" class="bg-white p-6 rounded shadow mb-8">
        @csrf

        <label class="block mb-1">Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi') }}" maxlength="50"
               placeholder="Contoh: Ruang Kelas XII RPL 1"
               class="w-full border rounded p-2 mb-4">

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
               placeholder="Contoh: Lampu kelas mati"
               class="w-full border rounded p-2 mb-4">

        <button class="bg-blue-600 text-white px-6 py-2 rounded">Kirim Aspirasi</button>
    </form>

    {{-- ===== BAGIAN 2: HISTORI ASPIRASI ===== --}}
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
                </tr>
            </thead>
            <tbody>
                {{-- forelse = perulangan, dengan @empty untuk kondisi data kosong --}}
                @forelse ($histori as $item)
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

                    <tr class="border-t">
                        <td class="p-3">{{ $item->created_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->lokasi }}</td>
                        <td class="p-3">{{ $item->kategori->ket_kategori }}</td>
                        <td class="p-3">{{ $item->ket }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-sm {{ $warna }}">{{ $status }}</span>
                        </td>
                        <td class="p-3">{{ $item->aspirasi?->feedback ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            Belum ada aspirasi yang dikirim.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection