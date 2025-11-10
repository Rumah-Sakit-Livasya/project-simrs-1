<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Pasien Rawat Inap</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    {{-- Path ke CSS print Anda --}}
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/print.css') }}" />
    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
</head>

<body>
    <!-- B: Functions -->
    <div id="functions">
        <ul>
            <li><a href="#" onClick="window.print();">Print</a></li>
            <li><a href="#" onClick="window.close();">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews">
        <h2 class="bdr">
            LAPORAN PASIEN RAWAT INAP
            {{-- Menggunakan Carbon::parse untuk memastikan format tanggal benar --}}
            <span>PERIODE TGL. REGISTRASI : {{ \Carbon\Carbon::parse($params['periode_awal'])->format('d-m-Y') }} s/d
                {{ \Carbon\Carbon::parse($params['periode_akhir'])->format('d-m-Y') }}</span>
            <span>Kelas : {{ $params['kelas'] }}</span>
            <span>Penjamin : {{ $params['penjamin'] }}</span>
            <span>Dokter : {{ $params['dokter'] }}</span>
        </h2>

        <table width="100%" class="bdr2 pad">
            <thead>
                {{-- ===== BAGIAN HEADER YANG DIPERBAIKI ===== --}}
                <tr>
                    <th rowspan="2">No</th>
                    {{-- Diubah dari colspan=2 menjadi satu kolom dengan rowspan=2 --}}
                    <th rowspan="2">No. RM</th>
                    <th rowspan="2">No. Reg</th>
                    <th rowspan="2">Nama Pasien</th>
                    <th rowspan="2">JK</th>
                    <th rowspan="2">Usia</th>
                    <th rowspan="2">Alamat</th>
                    <th rowspan="2">Penjamin</th>
                    <th rowspan="2">No. SEP</th>
                    <th rowspan="2">Kelas / Ruang</th>
                    <th rowspan="2">Tgl. Masuk</th>
                    <th rowspan="2">Tgl Keluar</th>
                    <th rowspan="2">Lama Rawat</th>
                    <th rowspan="2">Dokter Yg Merawat</th>
                    <th colspan="2">Diagnosa</th>
                    <th rowspan="2">Alasan Keluar</th>
                    <th rowspan="2">Status Pasien</th>
                </tr>
                <tr>
                    {{-- Kolom "No. RM" yang berlebih di sini dihapus --}}
                    <th>Awal</th>
                    <th>Akhir (ICD-10)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $item)
                    <tr>
                        {{-- ===== BAGIAN ISI TABEL YANG DIPERBAIKI ===== --}}
                        <td align="center">{{ $loop->iteration }}</td>
                        {{-- Menampilkan No RM dari relasi patient --}}
                        <td align="center">{{ $item->patient->medical_record_number ?? '' }}</td>
                        <td align="center">{{ $item->registration_number ?? '' }}</td>
                        <td>{{ $item->patient->name ?? 'N/A' }}</td>
                        <td align="center">{{ $item->patient->gender ?? '' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->patient->date_of_birth)->age ?? '' }} Th
                        </td>
                        <td>{{ $item->patient->address ?? '' }}</td>
                        <td>{{ $item->penjamin->nama_perusahaan ?? 'N/A' }}</td>
                        <td>{{ $item->no_sep ?? '' }}</td>
                        {{-- Pastikan relasi kelasRawat dan room sudah benar di Model Registration --}}
                        <td>{{ $item->kelasRawat->kelas ?? '' }}<br>{{ $item->room->no_ruang ?? '' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->registration_date)->format('d-m-Y') }}</td>
                        <td align="center">
                            {{ $item->registration_close_date ? \Carbon\Carbon::parse($item->registration_close_date)->format('d-m-Y') : '-' }}
                        </td>
                        <td align="center">
                            {{ $item->registration_close_date ? \Carbon\Carbon::parse($item->registration_date)->diffInDays($item->registration_close_date) . ' Hari' : '-' }}
                        </td>
                        <td>{{ $item->doctor->employee->fullname ?? 'N/A' }}</td>
                        <td>{{ $item->diagnosa_awal ?? '' }}</td>
                        {{-- Diagnosa akhir biasanya dari tabel/relasi lain --}}
                        <td></td>
                        {{-- Pastikan relasi tutupKunjungan sudah ada di Model Registration --}}
                        <td>{{ $item->tutupKunjungan->alasan_keluar ?? '' }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan disesuaikan dengan jumlah kolom header yang baru (18) --}}
                        <td colspan="18" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- E: Print View -->
</body>

</html>
