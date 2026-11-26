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

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-6 text-sm">
            {{ session('error') }}
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
            <form action="{{ route('admin.storeAdmin') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username Admin</label>
                    <input type="text" name="username" class="w-full border rounded p-2 text-sm" placeholder="Contoh: admin2" autocomplete="off" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded p-2 text-sm" placeholder="Minimal 6 karakter" autocomplete="new-password" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">
                    Simpan Admin
                </button>
            </form>
        </div>

        {{-- Card Tambah Siswa --}}
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Siswa Baru</h2>
            <form action="{{ route('admin.storeSiswa') }}" method="POST" autocomplete="off">
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
                    <input type="text" name="kelas" class="w-full border rounded p-2 text-sm" placeholder="Contoh: XII RPL 1" autocomplete="off" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (Untuk Login Siswa)</label>
                    <input type="password" name="password" class="w-full border rounded p-2 text-sm" placeholder="Minimal 6 karakter" autocomplete="new-password" required>
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
        <div class="bg-white p-6 rounded shadow overflow-x-auto">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Admin Terdaftar</h2>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-2">Username Admin</th>
                        <th class="p-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $adm)
                    <tr class="border-b">
                        <td class="p-2 font-medium">
                            {{ $adm->username }}
                            @if(session('user_id') === $adm->username)
                                <span class="ml-1 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Anda</span>
                            @endif
                        </td>
                        <td class="p-2 text-right">
                            <div class="inline-flex items-center gap-2">
                                <button type="button"
                                    onclick="openEditAdminModal(this)"
                                    data-username="{{ $adm->username }}"
                                    data-action="{{ route('admin.updateAdmin', $adm->username) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2.5 py-1 rounded text-xs font-medium">
                                    Edit
                                </button>
                                @if(session('user_id') !== $adm->username)
                                    <form action="{{ route('admin.destroyAdmin', $adm->username) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus admin {{ $adm->username }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded text-xs font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="p-3 text-center text-gray-500">Belum ada data admin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel Daftar Siswa --}}
        <div class="bg-white p-6 rounded shadow overflow-x-auto">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Siswa Terdaftar</h2>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-2">NIS</th>
                        <th class="p-2">Nama</th>
                        <th class="p-2">Kelas</th>
                        <th class="p-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $sw)
                    <tr class="border-b">
                        <td class="p-2 font-medium">{{ $sw->nis }}</td>
                        <td class="p-2">{{ $sw->nama ?? '-' }}</td>
                        <td class="p-2">{{ $sw->kelas }}</td>
                        <td class="p-2 text-right">
                            <div class="inline-flex items-center gap-2">
                                <button type="button"
                                    onclick="openEditSiswaModal(this)"
                                    data-nis="{{ $sw->nis }}"
                                    data-nama="{{ $sw->nama ?? '' }}"
                                    data-kelas="{{ $sw->kelas }}"
                                    data-action="{{ route('admin.updateSiswa', $sw->nis) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2.5 py-1 rounded text-xs font-medium">
                                    Edit
                                </button>
                                <form action="{{ route('admin.destroySiswa', $sw->nis) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus siswa NIS {{ $sw->nis }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded text-xs font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">Belum ada data siswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- MODAL EDIT ADMIN --}}
<div id="editAdminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit Akun Admin</h3>
            <button type="button" onclick="closeModal('editAdminModal')" class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>
        <form id="editAdminForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Username Admin</label>
                <input type="text" name="username" id="editAdminUsername" class="w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-xs text-gray-500">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="w-full border rounded p-2 text-sm" placeholder="Minimal 6 karakter" autocomplete="new-password">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editAdminModal')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT SISWA --}}
<div id="editSiswaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit Akun Siswa</h3>
            <button type="button" onclick="closeModal('editSiswaModal')" class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>
        <form id="editSiswaForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                <input type="text" name="nis" id="editSiswaNis" class="w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                <input type="text" name="nama" id="editSiswaNama" class="w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <input type="text" name="kelas" id="editSiswaKelas" class="w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-xs text-gray-500">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="w-full border rounded p-2 text-sm" placeholder="Minimal 6 karakter" autocomplete="new-password">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editSiswaModal')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
    }

    function openEditAdminModal(button) {
        document.getElementById('editAdminForm').action = button.dataset.action;
        document.getElementById('editAdminUsername').value = button.dataset.username;
        openModal('editAdminModal');
    }

    function openEditSiswaModal(button) {
        document.getElementById('editSiswaForm').action = button.dataset.action;
        document.getElementById('editSiswaNis').value = button.dataset.nis;
        document.getElementById('editSiswaNama').value = button.dataset.nama;
        document.getElementById('editSiswaKelas').value = button.dataset.kelas;
        openModal('editSiswaModal');
    }
</script>
@endsection
