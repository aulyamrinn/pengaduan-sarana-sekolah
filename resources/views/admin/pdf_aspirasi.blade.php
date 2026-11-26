<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Aspirasi Siswa</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #4b5563;
        }
        .meta {
            margin-bottom: 12px;
            font-size: 10px;
        }
        .meta strong {
            color: #111827;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .summary td {
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            background-color: #f9fafb;
            width: 25%;
            text-align: center;
        }
        .summary .label {
            font-size: 9px;
            color: #6b7280;
            display: block;
        }
        .summary .value {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        table.data th,
        table.data td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        table.data th {
            background-color: #e5e7eb;
            font-weight: bold;
            font-size: 10px;
        }
        table.data tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-menunggu {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-proses {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Aspirasi Siswa</h1>
        <p>Sistem Pengaduan Sarana Sekolah</p>
        <p>Dicetak pada: {{ now('Asia/Jakarta')->format('d-m-Y H:i') }} WIB</p>
    </div>

    <div class="meta">
        <strong>Filter Aktif:</strong>
        {{ count($filterInfo) > 0 ? implode(' | ', $filterInfo) : 'Semua Data' }}
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="label">Total Laporan</span>
                <span class="value">{{ $laporan->count() }}</span>
            </td>
            <td>
                <span class="label">Menunggu Tindakan</span>
                <span class="value">{{ $laporan->filter(fn($i) => ($i->aspirasi?->status ?? 'Menunggu') == 'Menunggu')->count() }}</span>
            </td>
            <td>
                <span class="label">Sedang Diproses</span>
                <span class="value">{{ $laporan->filter(fn($i) => $i->aspirasi?->status == 'Proses')->count() }}</span>
            </td>
            <td>
                <span class="label">Selesai</span>
                <span class="value">{{ $laporan->filter(fn($i) => $i->aspirasi?->status == 'Selesai')->count() }}</span>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 8%;">Hari</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 11%;">NIS / Pelapor</th>
                <th style="width: 14%;">Lokasi</th>
                <th style="width: 13%;">Kategori</th>
                <th style="width: 18%;">Deskripsi</th>
                <th style="width: 9%;">Status</th>
                <th style="width: 13%;">Feedback Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
                @php
                    $status = $item->aspirasi?->status ?? 'Menunggu';
                    $badgeClass = match ($status) {
                        'Proses'  => 'badge-proses',
                        'Selesai' => 'badge-selesai',
                        default   => 'badge-menunggu',
                    };
                    $days = [
                        'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa',
                        'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu',
                    ];
                    $hari = $item->created_at ? ($days[$item->created_at->format('D')] ?? $item->created_at->format('l')) : '-';
                    $tanggal = $item->created_at ? $item->created_at->format('d-m-Y') : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $hari }}</td>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $item->nis }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>{{ $item->kategori?->ket_kategori ?? '-' }}</td>
                    <td>{{ $item->ket }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>
                    <td>{{ $item->aspirasi?->feedback ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data laporan yang sesuai dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
