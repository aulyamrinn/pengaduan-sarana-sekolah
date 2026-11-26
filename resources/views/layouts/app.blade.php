<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Pengaduan Sarana Sekolah</title>

    {{-- Tailwind CSS lewat CDN (pengganti Vite) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- CSS buatan sendiri --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="min-h-screen">

    {{-- Navbar hanya tampil kalau sudah login --}}
    @if (session('role'))
        <nav class="bg-blue-600 text-white px-6 py-3 flex justify-between items-center">
            <span class="font-bold">Pengaduan Sarana Sekolah</span>

            <div class="flex items-center gap-4">
                {{-- TOMBOL KELOLA AKUN (Hanya muncul jika yang login adalah admin) --}}
                @if (session('role') == 'admin')
                    <a href="{{ route('admin.users') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 rounded text-sm font-medium">
                        Kelola Akun
                    </a>
                @endif

                <span>{{ session('role') == 'admin' ? 'Admin' : 'Siswa' }}: {{ session('user_id') }}</span>

                {{-- Logout memakai form POST supaya aman --}}
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button class="bg-white text-blue-600 px-3 py-1 rounded">Logout</button>
                </form>
            </div>
        </nav>
    @endif

    {{-- Isi halaman dimasukkan di sini --}}
    <main class="max-w-6xl mx-auto p-6">
        @yield('content')
    </main>

    {{-- JavaScript buatan sendiri --}}
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>