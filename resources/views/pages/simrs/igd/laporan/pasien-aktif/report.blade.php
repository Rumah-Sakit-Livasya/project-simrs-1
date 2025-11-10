<!DOCTYPE HTML>
<html>

<head>
    <title>Print Laporan Pasien Aktif Rawat Inap</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    {{-- Path ke CSS & JS disesuaikan dengan template sistem Anda --}}
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/print.css') }}" />
    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
    {{-- File js/standard_lib.js jika memang dibutuhkan --}}
    {{-- <script src="{{ asset('js/standard_lib.js') }}" type="text/javascript"></script> --}}
</head>

<body>
    <!-- B: Functions -->
    <div id="functions">
        <ul>
            <li><a href="#" onClick="window.print();">Print</a></li>
            {{-- Tombol XLS bisa diimplementasikan nanti dengan route terpisah --}}
            {{-- <li><a href="#">xls</a></li> --}}
            <li><a href="#" onClick="window.close();">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews">
        <h2 class="bdr">
            Laporan Pasien Rawat Inap Aktif
            {{-- Menggunakan Carbon untuk memastikan format tanggal benar --}}
            <span>Periode Tgl Registrasi : {{ \Carbon\Carbon::parse($params['periode_awal'])->format('d-m-Y') }} s/d
                {{ \Carbon\Carbon::parse($params['periode_akhir'])->format('d-m-Y') }}</span>
            <span>Kelas : {{ $params['kelas'] }}</span>
            <span>Dokter : {{ $params['dokter'] }}</span>
        </h2>

        <table width="100%" class="bdr4 pad">
            <thead>
                <tr>
                    <th width="1%" rowspan="2">No.</th>
                    {{-- Diubah menjadi satu kolom --}}
                    <th width="5%" rowspan="2">No. RM</th>
                    <th width="5%" rowspan="2">No. Reg</th>
                    <th width="5%" rowspan="2">Tgl. Reg.</th>
                    <th width="10%" rowspan="2">Nama Pasien</th>
                    <th width="1%" rowspan="2">JK</th>
                    <th width="5%" rowspan="2">Usia</th>
                    <th rowspan="2">Alamat</th>
                    <th width="10%" rowspan="2">Dokter</th>
                    <th width="10%" rowspan="2">Penjamin</th>
                    <th colspan="2">Diagnosa</th>
                    <th width="3%" rowspan="2">Kelas</th>
                    <th width="10%" rowspan="2">Ruangan</th>
                    <th width="1%" rowspan="2">Bed</th>
                    <th width="1%" rowspan="2">Lama Rawat</th>
                </tr>
                <tr>
                    {{-- Kolom Baru/Lama sudah dihapus --}}
                    <th>Awal</th>
                    <th>Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $item)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align="center">{{ $item->patient->medical_record_number ?? '' }}</td>
                        <td align="center">{{ $item->registration_number ?? '' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->registration_date)->format('d-m-Y') }}</td>
                        <td>{{ $item->patient->name ?? 'N/A' }}</td>
                        <td align="center">{{ $item->patient->gender ?? '' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->patient->date_of_birth)->age ?? '' }} Th
                        </td>
                        <td>{{ $item->patient->address ?? '' }}</td>
                        <td>{{ $item->doctor->employee->fullname ?? 'N/A' }}</td>
                        <td>{{ $item->penjamin->nama_perusahaan ?? 'N/A' }}</td>
                        <td>{{ $item->diagnosa_awal ?? '' }}</td>
                        <td>{{-- Kolom untuk diagnosa akhir --}}</td>
                        <td align="center">{{ $item->kelasRawat->kelas ?? '' }}</td>
                        <td>{{ $item->room->ruangan ?? '' }}</td>
                        <td align="center">{{ $item->patient->bed->nama_tt ?? '' }}</td>
                        <td align="center">
                            {{-- Hitung selisih hari dari tgl masuk s/d hari ini --}}
                            {{ \Carbon\Carbon::parse($item->registration_date)->diffInDays(now()) + 1 }} hr
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan disesuaikan dengan jumlah kolom header yang baru (16) --}}
                        <td colspan="16" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- E: Print View -->
</body>

</html>
