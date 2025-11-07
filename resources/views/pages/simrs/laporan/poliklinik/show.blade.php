<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pasien Poliklinik</title>
    {{-- Menggunakan CSS dari template utama Anda --}}
    <link rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-table {
            width: 50%;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .filter-table td {
            padding: 2px 5px;
            border: none;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center !important;
        }

        .no-wrap {
            white-space: nowrap;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                margin: 0.5cm;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }

            .data-table th {
                background-color: #f2f2f2 !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center no-print mb-3">
            <h3 class="mb-0">Laporan Pasien Poliklinik</h3>
            <div>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fal fa-print"></i> Print
                </button>
                <button onclick="window.close()" class="btn btn-secondary">
                    <i class="fal fa-times"></i> Close
                </button>
            </div>
        </div>

        <div class="report-header d-none d-print-block">
            <h3>LAPORAN PASIEN POLIKLINIK</h3>
        </div>

        <table class="filter-table">
            <tbody>
                <tr>
                    <td style="width: 150px;"><strong>PERIODE</strong></td>
                    <td>: {{ \Carbon\Carbon::createFromFormat('d-m-Y', $filter['stgl1'])->format('d-m-Y') }} s/d
                        {{ \Carbon\Carbon::createFromFormat('d-m-Y', $filter['stgl2'])->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><strong>POLIKLINIK</strong></td>
                    <td>: {{ $poliklinik->name ?? 'Semua Poli' }}</td>
                </tr>
                <tr>
                    <td><strong>DOKTER</strong></td>
                    <td>: {{ $dokter->fullname ?? 'Semua Dokter' }}</td>
                </tr>
                <tr>
                    <td><strong>RUJUKAN</strong></td>
                    <td>: </td>
                </tr>
                <tr>
                    <td><strong>PERUJUK</strong></td>
                    <td>: </td>
                </tr>
                <tr>
                    <td><strong>PENJAMIN</strong></td>
                    <td>: {{ $penjamin->name ?? 'Semua Penjamin' }}</td>
                </tr>
                <tr>
                    <td><strong>STATUS REGISTRASI</strong></td>
                    <td>: </td>
                </tr>
                <tr>
                    <td><strong>ICD 10</strong></td>
                    <td>: </td>
                </tr>
            </tbody>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Tanggal Registrasi</th>
                    <th rowspan="2">No. Reg</th>
                    <th colspan="2">No. RM</th>
                    <th rowspan="2">Nama Pasien</th>
                    <th rowspan="2">JK</th>
                    <th rowspan="2">Umur / Tahun</th>
                    <th rowspan="2">Alamat</th>
                    <th rowspan="2">No. Telp</th>
                    <th rowspan="2">Poli</th>
                    <th rowspan="2">Dokter</th>
                    <th rowspan="2">Penjamin</th>
                    <th rowspan="2">Perujuk</th>
                    <th rowspan="2">Diagnosa Awal</th>
                    <th rowspan="2">Diagnosa Akhir</th>
                    <th rowspan="2">Petugas</th>
                    <th rowspan="2">Status Pasien</th>
                </tr>
                <tr>
                    <th>Baru</th>
                    <th>Lama</th>
                </tr>
            </thead>
            {{-- resources/views/pages/simrs/laporan/poliklinik/show.blade.php --}}
            <tbody>
                @forelse ($results as $item)
                    @php
                        // Menghitung umur
                        $birthDate = new DateTime($item->patient->date_of_birth ?? 'now');
                        $today = new DateTime('today');
                        $age = $birthDate->diff($today);

                        // Menentukan status pasien baru atau lama
                        // Cek apakah relasi patient dan registrations berhasil dimuat
                        $isNewPatient = true; // Anggap baru secara default
                        if ($item->patient && $item->patient->relationLoaded('registrations')) {
                            // Jika jumlah registrasi pasien lebih dari 1, maka dia pasien lama
                            if ($item->patient->registrations->count() > 1) {
                                $isNewPatient = false;
                            }
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center no-wrap">
                            {{ \Carbon\Carbon::parse($item->registration_date)->format('d Nov Y H:i') }}</td>
                        <td class="text-center">{{ $item->registration_number ?? '-' }}</td>

                        {{-- Kolom No. RM Baru / Lama --}}
                        <td class="text-center">
                            @if ($isNewPatient)
                                {{ $item->patient->medical_record_number ?? '-' }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if (!$isNewPatient)
                                {{ $item->patient->medical_record_number ?? '-' }}
                            @endif
                        </td>

                        <td>{{ $item->patient->name ?? '-' }}</td>
                        <td class="text-center">{{ substr($item->patient->gender ?? '-', 0, 1) }}</td>
                        <td class="no-wrap">{{ $age->y }} Th {{ $age->m }} Bln</td>
                        <td>{{ $item->patient->address ?? '-' }}</td>
                        <td>{{ $item->patient->mobile_phone_number ?? '-' }}</td>
                        <td>{{ $item->departement->name ?? '-' }}</td>
                        <td>{{ $item->doctor->employee->fullname ?? ($item->doctor->fullname ?? '-') }}</td>
                        <td>{{ $item->penjamin->nama_perusahaan ?? '-' }}</td>
                        <td>{{ isset($item->rujukan) ? ucwords(strtolower($item->rujukan)) : '' }}</td>
                        <td>{{ $item->diagnosa_awal ?? '' }}</td>
                        <td>{{ $item->diagnosa_akhir ?? '' }}</td>
                        <td>{{ $item->user->name ?? '' }}</td>
                        <td class="text-center">{{ $item->status ?? 'Aktif' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="18" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
