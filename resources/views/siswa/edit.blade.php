@extends('layouts.app')

@section('title', 'Edit Aspirasi')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Aspirasi</h1>

    <form action="/siswa/aspirasi/{{ $laporan->id_pelaporan }}" method="POST" class="bg-white p-6 rounded shadow mb-8">
        @csrf
        @method('PUT')

        <label class="block mb-1">Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi', $laporan->lokasi) }}" maxlength="50"
               class="w-full border rounded p-2 mb-4" required>

        <label class="block mb-1">Kategori</label>
        <select name="id_kategori" class="w-full border rounded p-2 mb-4" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $laporan->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                    {{ $k->ket_kategori }}
                </option>
            @endforeach
        </select>

        <label class="block mb-1">Deskripsi (maks. 50 karakter)</label>
        <input type="text" name="ket" value="{{ old('ket', $laporan->ket) }}" maxlength="50"
               class="w-full border rounded p-2 mb-4" required>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Simpan Perubahan</button>
            <a href="/siswa" class="bg-gray-500 text-white px-6 py-2 rounded inline-block">Batal</a>
        </div>
    </form>
@endsection