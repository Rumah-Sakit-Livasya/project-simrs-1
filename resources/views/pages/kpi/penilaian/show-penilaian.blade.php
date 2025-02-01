@extends('inc.layout')
@section('title', 'KPI - Tambah Penilaian')
@section('extended-css')
    <style>
        #canvas {
            border: 1px solid black;
            display: block;
            margin: 0 auto;
            cursor: crosshair;
        }

        .modal-dialog {
            max-width: 700px;
        }

        .modal-body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .text-hijau {
            color: rgb(0, 129, 0)
        }

        .text-merah {
            color: rgb(215, 0, 0)
        }

        .biru-muda {
            background: rgb(219, 243, 249);
        }

        .kuning-muda {
            background: rgb(255, 255, 179);
        }

        table#identitas {
            border: 1px solid black;
            /* Border untuk tabel */
            border-collapse: collapse;
            /* Menghindari double borders */
            width: 100%;
            /* Mengisi seluruh lebar */
        }

        /* Border untuk baris tabel */
        table#identitas tr,
        table#penilaian tr {
            border-bottom: 1px solid black;
            /* Border bawah untuk setiap baris */
        }

        /* Border untuk kolom tabel */
        table#identitas td:nth-child(3),
        table#identitas th {
            border-right: 1px solid black;
        }

        table#penilaian td {
            border-right: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
        }

        table#penilaian {
            border-top: 0px !important;
        }

        table#penilaian th {
            border-right: 1px solid black;
            background: rgb(138, 196, 248);
            color: #ffffff;
            text-align: center;
        }

        table#identitas td {
            padding: 2px 8px 2px 8px;
        }

        table#detail_nilai {
            padding: 10px 0px;
            border: none;
            margin: 10px 0px;
        }

        table#detail_nilai tr {
            border: none;
        }

        table#detail_nilai td {
            padding: 0px;
            border: none;
            line-height: 1.5;
        }

        .form-wrapper {
            overflow-x: hidden;
        }

        table th {
            font-size: 10pt;
        }

        table td td {
            vertical-align: top;
        }

        table th h3,
        table td h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 5px 0px;
        }

        @media (max-width: 768px) {
            .form-wrapper {
                overflow-x: scroll;
            }

            .form-wrapper .bungkus {
                width: 150%;
            }
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2 class="font-weight-bold">
                            {{ $group_penilaian->nama_group }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pt-2">
                            <form action="" method="POST" id="penilaian-form" data-form="{{ $group_penilaian->id }}">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-md-12 form-wrapper">
                                        <div class="bungkus">
                                            <table width="100%" id="identitas"
                                                style="border: 1px solid black;border-bottom: none !important; margin-top: 0px;">
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h3 class="my-2 font-weight-bold">
                                                            PEGAWAI YANG
                                                            DINILAI</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" style="font-size: 10pt !important;">Nama</td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        <span id="nama_pegawai"
                                                            style="font-size: 10pt !important;">{{ $penilaian_pegawai[0]->employee->fullname ?? '-' }}</span>
                                                    </td>
                                                    <td width="20%" style="font-size: 10pt !important;">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        <span id="unit_pegawai"
                                                            style="font-size: 10pt !important;">{{ $penilaian_pegawai[0]->employee->organization->name ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" style="font-size: 10pt !important;">Jabatan</td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        <span id="jabatan_pegawai"
                                                            style="font-size: 10pt !important;">{{ $penilaian_pegawai[0]->employee->jobPosition->name ?? '-' }}</span>
                                                    </td>
                                                    <td width="20%" style="font-size: 10pt !important;">
                                                        NIP
                                                    </td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%">
                                                        <span id="nip_pegawai"
                                                            style="font-size: 10pt !important;">{{ $penilaian_pegawai[0]->employee->employee_code ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h3 class="my-2 font-weight-bold">
                                                            PEJABAT YANG MENILAI</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" style="font-size: 10pt !important;">Nama</td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $penilai['nama'] }}
                                                    </td>
                                                    <td width="20%" style="font-size: 10pt !important;">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $penilai['unit'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" style="font-size: 10pt !important;">Jabatan</td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $penilai['jabatan'] }}
                                                    </td>
                                                    <td width="20%" style="font-size: 10pt !important;">
                                                        NIP
                                                    </td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $penilai['nip'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h3 class="my-2 font-weight-bold">
                                                            ATASAN PEJABAT PENILAI</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" style="font-size: 10pt !important;">Nama</td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $pejabat_penilai['nama'] }}
                                                    </td>
                                                    <td width="20%" style="font-size: 10pt !important;">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $pejabat_penilai['unit'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" style="font-size: 10pt !important;">Jabatan</td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $pejabat_penilai['jabatan'] }}
                                                    </td>
                                                    <td width="20%" style="font-size: 10pt !important;">
                                                        NIP
                                                    </td>
                                                    <td width="1%" style="font-size: 10pt !important;">:</td>
                                                    <td width="29%" style="font-size: 10pt !important;">
                                                        {{ $pejabat_penilai['nip'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="kuning-muda">
                                                        <table width="100%" style="font-weight: bold;"
                                                            id="detail-absensi">
                                                            <tr class="border-0">
                                                                <td class="p-0 kuning-muda">Jumlah Hari</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="border-0 p-0 kuning-muda"
                                                                    style="border-right: none">
                                                                    <span id="jml_hari">
                                                                        {{ $attendances['total_hari'] ?? '0 Hari' }}</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Hadir</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_hadir">
                                                                        {{ $attendances['total_hadir'] ?? '0 Hari' }}</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Alfa</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_alfa">
                                                                        {{ $attendances['total_alfa'] ?? '0 Hari' }}</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Cuti</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_cuti">
                                                                        {{ $attendances['total_cuti'] ?? '0 Hari' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="border-0">
                                                                <td class="p-0 kuning-muda">Sakit</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="border-0 p-0 kuning-muda"
                                                                    style="border-right: none">
                                                                    <span id="jml_sakit">
                                                                        {{ $attendances['total_sakit'] ?? '0 Hari' }}</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Izin</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_izin">
                                                                        {{ $attendances['total_izin'] ?? '0 Hari' }}</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Telat</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_telat">
                                                                        {{ $attendances['total_telat'] ?? '0 Hari' }}</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Libur</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_libur">
                                                                        {{ $attendances['total_libur'] ?? '0 Hari' }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%" id="penilaian" style="border: 1px solid black;">
                                                <tr>
                                                    <th colspan="2" rowspan="3" width="50%"
                                                        id="bobot_penilaian">
                                                        <h3>ASPEK PENILAIAN PENILAIAN</h3>
                                                    </th>
                                                    <th width="10%">BOBOT</th>
                                                    <th width="15%" colspan="5">PENILAIAN</th>
                                                    <th width="15%">NILAI</th>
                                                    <th width="10%">TOTAL NILAI</th>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2">a</th>
                                                    <th width="3.5%">1</th>
                                                    <th width="3.5%">2</th>
                                                    <th width="3.5%">3</th>
                                                    <th width="3.5%">4</th>
                                                    <th width="3.5%">5</th>
                                                    <th rowspan="2">c=(b/( 40) ) x a </th>
                                                    <th rowspan="2">d=c x 100</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5">b</th>
                                                </tr>
                                                @php
                                                    $number = 0;
                                                    $index_penilaian = 0;
                                                    $total = [];
                                                    $total_aspek_per_row = 0;
                                                    $bobot = [];
                                                @endphp
                                                @foreach ($group_penilaian->aspek_penilaians as $index => $aspek)
                                                    <tr>
                                                        @php
                                                            $aspek_name = $aspek->nama;

                                                            // Menjadikan semua huruf kecil
                                                            $aspek_name = strtolower($aspek_name);

                                                            // Mengganti spasi dengan underscore
                                                            $aspek_name = str_replace(' ', '_', $aspek_name);

                                                            // Menambahkan karakter "[]"
                                                            $aspek_name_not_array = $aspek_name;
                                                            $aspek_name .= '[]';
                                                        @endphp
                                                        <td class="biru-muda text-center"
                                                            style="width: 2% !important;font-size: 11pt; font-weight: bold;">
                                                            <h3>NO</h3>
                                                        </td>
                                                        <td class="biru-muda" style="font-size: 11pt; font-weight: bold;">
                                                            <h3>{{ strtoupper($aspek->nama) }}</h3>
                                                        </td>
                                                        <td class="kuning-muda text-center"
                                                            style="font-size: 11pt; font-weight: bold;">
                                                            <h3 id="bobot_{{ $aspek_name_not_array }}">
                                                                {{ $aspek->bobot }}
                                                                @php
                                                                    $bobot[] = intval($aspek->bobot);
                                                                @endphp
                                                            </h3>
                                                        </td>
                                                        <td class="kuning-muda text-center" colspan="5">
                                                            <h3 id="total_nilai_{{ $aspek_name_not_array }}"
                                                                class="total_nilai">
                                                                {{ $total_nilai_all[$index]['nilai'] }}</h3>
                                                        </td>
                                                        <td class="kuning-muda text-center">
                                                            <h3 id="total_aspek_{{ $aspek_name_not_array }}"
                                                                class="total_aspek">
                                                                {{ $total_nilai_all[$index]['nilai_kalkulasi'] }}</h3>
                                                        </td>
                                                        <td class="kuning-muda text-center">
                                                            <h3 id="total_akhir_{{ $aspek_name_not_array }}"
                                                                class="total_akhir">
                                                                {{ $total_nilai_all[$index]['total_nilai'] }}</h3>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $number = 1;
                                                    @endphp
                                                    @foreach ($aspek->indikator_penilaians as $index => $indikator)
                                                        <tr>
                                                            <td class="text-center" style="font-size: 10pt !important;">
                                                                {{ $number++ }}</td>
                                                            <td style="font-size: 10pt !important">{{ $indikator->nama }}
                                                            </td>
                                                            @if ($loop->last)
                                                                <td style="border-bottom: 1px solid black !important;">
                                                                </td>
                                                            @else
                                                                <td style="border-bottom: 1px solid white !important;">
                                                                </td>
                                                            @endif
                                                            <td colspan="5" class="text-center">
                                                                <span class="{{ $aspek_name_not_array }}"
                                                                    style="font-size: 10pt !important;">{{ $penilaian_pegawai[$index_penilaian]->nilai }}</span>
                                                                @php
                                                                    if ($index == 0) {
                                                                        if (
                                                                            $total_aspek_per_row != 0 ||
                                                                            $total_aspek_per_row != null
                                                                        ) {
                                                                            $total[] = $total_aspek_per_row;
                                                                        }
                                                                        $total_aspek_per_row = 0;
                                                                    }
                                                                    $total_aspek_per_row +=
                                                                        $penilaian_pegawai[$index_penilaian]->nilai;
                                                                @endphp
                                                            </td>
                                                            @if ($loop->last)
                                                                <td style="border-bottom: 1px solid black !important;">
                                                                </td>
                                                                <td style="border-bottom: 1px solid black !important;">
                                                                </td>
                                                            @else
                                                                <td style="border-bottom: 1px solid white !important;">
                                                                </td>
                                                                <td style="border-bottom: 1px solid white !important;">
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $index_penilaian++;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                                <tr>
                                                    <td colspan="9">
                                                        <h3>TOTAL NILAI</h3>
                                                    </td>
                                                    <td class="kuning-muda">
                                                        <h3 id="total_semuanya"
                                                            style="font-weight: bold; font-size: 12pt !important;"
                                                            class="text-center">
                                                            {{ $total_akhir }}
                                                        </h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="10">
                                                        <h3 class="font-weight-bold text-left ml-2"> NILAI
                                                            PRESTASI KERJA
                                                        </h3>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table id="detail_nilai" style="font-size: 10pt !important">
                                                            <tr>
                                                                <td>Sangat Baik / Istimewa</td>
                                                                <td>:</td>
                                                                <td>> 95</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Baik</td>
                                                                <td>:</td>
                                                                <td>86 s/d 95</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cukup</td>
                                                                <td>:</td>
                                                                <td>66 s/d 85</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Kurang</td>
                                                                <td>:</td>
                                                                <td>51 s/d 65</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sangat Kurang</td>
                                                                <td>:</td>
                                                                <td>
                                                                    < 50</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td colspan="8">
                                                        <h3 id="keterangan_penilaian"
                                                            style="font-size: 10pt !important; text-align: center">
                                                            @if ($total_akhir > 95)
                                                                Karyawan tersebut memiliki kinerja yang <span
                                                                    class="text-hijau"
                                                                    style="font-weight: bold; font-size: 10pt;"> SANGAT
                                                                    BAIK </span>
                                                            @elseif ($total_akhir > 85 && $total_akhir < 96)
                                                                Karyawan tersebut memiliki kinerja yang <span
                                                                    class="text-hijau"
                                                                    style="font-weight: bold; font-size: 10pt;"> BAIK
                                                                </span>
                                                            @elseif ($total_akhir > 65 && $total_akhir < 86)
                                                                Karyawan tersebut memiliki kinerja yang <span
                                                                    class="text-hijau"
                                                                    style="font-weight: bold; font-size: 10pt;"> CUKUP
                                                                </span>
                                                            @elseif ($total_akhir > 50 && $total_akhir < 66)
                                                                Karyawan tersebut memiliki kinerja yang <span
                                                                    class="text-merah"
                                                                    style="font-weight: bold; font-size: 10pt;"> KURANG
                                                                </span>
                                                            @elseif ($total_akhir <= 50)
                                                                Karyawan tersebut memiliki kinerja yang <span
                                                                    class="text-merah"
                                                                    style="font-weight: bold; font-size: 10pt;"> SANGAT
                                                                    KURANG </span>
                                                            @endif
                                                        </h3>
                                                    </td>
                                                </tr>
                                                <tr style="border: 0px; border-top: 1px solid black !important;">
                                                    <td colspan="10" style="border-right: 0px;">
                                                        <h3 class="font-weight-bold my-1 text-center"
                                                            style="font-size: 12pt">PENILAIAN AKHIR</h3>
                                                    </td>
                                                </tr>
                                                <tr style="border: 0px; border-top: 1px solid black !important;">
                                                    <td colspan="10" style="border-right: 0px;">
                                                        <p class="my-1">Bedasarkan hasil penilaian
                                                            keseluruhan disimpulkan bahwa:</p>
                                                    </td>
                                                </tr>
                                                <tr style="border: 0px; border-top: 1px solid black !important;">
                                                    <td colspan="10"
                                                        style="border-right: 0px; padding-top: 0px; padding-bottom: 0px;">
                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                            <tr style="border: 0px; height: 50px">
                                                                <td style="width: 50%; padding: 5px 0px;">
                                                                    <h3 class="font-weight-bold text-center mb-0">
                                                                        YA</h3>
                                                                </td>
                                                                <td
                                                                    style="width: 50%; padding: 5px 0px; border-right: 0px;">
                                                                    <h3 class="font-weight-bold text-center mb-0">
                                                                        TIDAK</h3>
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="border: 0px; border-top: 1px solid black !important;">
                                                    <td colspan="10"
                                                        style="border-right: 0px; padding-top: 0px; padding-bottom: 0px;">
                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                            <tr style="border: 0px; height: 50px">
                                                                <td style="width: 50%; padding: 5px 8px 5px 0px;">
                                                                    <h3 id="ya"
                                                                        style="font-size: 10pt !important; text-align: center; margin-top: 10px !important">
                                                                        {{ $catatan->keterangan_ya }}</h3>
                                                                </td>
                                                                <td
                                                                    style="width: 50%; padding: 5px 0px 5px 8px; border-right: 0px;">
                                                                    <h3 id="ya"
                                                                        style="font-size: 10pt !important; text-align: center; margin-top: 10px !important;">
                                                                        {{ $catatan->keterangan_tidak }}</h3>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%"
                                                style="text-align: center; border: 1px solid black; margin-top: 30px">
                                                <tr>
                                                    <td class="kuning-muda" colspan="2"
                                                        style="text-align: left; padding: 8px; border-bottom: 1px solid black;">
                                                        <b>Majalengka, {{ now()->format('d F Y') }}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="padding-top: 15px; padding-bottom: 15px;">
                                                        <b>Karyawan yang Dinilai
                                                        </b><br><br>

                                                        <div>
                                                            @if (auth()->user()->id == $penilaian_pegawai[0]->employee->user->id)
                                                                <a class="btn btn-primary btn-sm text-white ttd"
                                                                    id="btn_ttd_pegawai"
                                                                    data-id= "{{ $penilaian_pegawai[0]->employee_id ?? null }}"
                                                                    data-tipe="pegawai">Tanda
                                                                    tangan</a>
                                                            @endif
                                                            <br>
                                                        </div>
                                                        <img id="ttd_pegawai" src="" alt="Signature Image"
                                                            style="display:none; max-width:60%; margin-top: -3px;"><br>

                                                        <span
                                                            id="ttd_nama_pegawai">{{ $penilaian_pegawai[0]->employee->fullname }}</span>
                                                    </td>
                                                    <td width="50%" style="border-left: 1px solid black">
                                                        <b>Yang Menilai
                                                        </b><br><br>

                                                        <div>
                                                            @if (auth()->user()->id == $penilaian_pegawai[0]->employee_penilai->user->id)
                                                                <a class="btn btn-primary btn-sm text-white ttd"
                                                                    id="btn_ttd_penilai"
                                                                    data-id="{{ $penilaian_pegawai[0]->penilai }}"
                                                                    data-tipe = "penilai">Tanda
                                                                    tangan</a>
                                                            @endif
                                                            <br>
                                                        </div>
                                                        <img id="ttd_penilai" src="" alt="Signature Image"
                                                            style="display:none; max-width:60%; margin-top: -3px;"><br>

                                                        <span
                                                            id="ttd_nama_penilai">{{ $penilaian_pegawai[0]->employee_penilai->fullname }}</span>
                                                    </td>
                                                </tr>
                                                <tr style="border: 0px; border-top: 1px solid black;">
                                                    <td width="50%" style="padding-top: 15px; padding-bottom: 15px;">
                                                        <b>
                                                            Mengetahui, <br>
                                                            {{ $penilaian_pegawai[0]->employee_pejabat_penilai->jobPosition->name . ' ' . $penilaian_pegawai[0]->employee_pejabat_penilai->organization->name }}
                                                        </b><br><br>
                                                        <div id="tombol-{{ $group_penilaian->pejabat_penilai }}">
                                                            @if (auth()->user()->id == $penilaian_pegawai[0]->employee_pejabat_penilai->user->id)
                                                                <a class="btn btn-primary btn-sm text-white ttd"
                                                                    id="btn_ttd_pejabat_penilai"
                                                                    data-id="{{ $penilaian_pegawai[0]->pejabat_penilai }}"
                                                                    data-tipe = "pejabat_penilai">Tanda
                                                                    tangan</a>
                                                            @endif
                                                            <br>
                                                        </div>

                                                        <img id="ttd_pejabat_penilai" src=""
                                                            alt="Signature Image"
                                                            style="display:none; max-width:60%; margin-top: -3px;"><br>
                                                        <span
                                                            id="ttd_nama_pejabat_penilai">{{ $penilaian_pegawai[0]->employee_pejabat_penilai->fullname }}</span>
                                                    </td>
                                                    <td width="50%" style="border-left: 1px solid black">
                                                        <b>menyetujui,</b>
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="50%">
                                                                    <b>HRD</b> <br><br>
                                                                    <div id="tombol-104">
                                                                        @if (auth()->user()->employee_id == $hrd->id)
                                                                            <a class="btn btn-primary btn-sm text-white ttd"
                                                                                id="btn_ttd_hrd" data-tipe = "hrd"
                                                                                data-id="{{ $hrd->id }}">Tanda
                                                                                tangan</a>
                                                                        @endif
                                                                        <br>
                                                                    </div>

                                                                    <img id="ttd_hrd" src=""
                                                                        alt="Signature Image"
                                                                        style="display:none; max-width:60%; margin-top: -3px;"><br>
                                                                    <span id="ttd_nama_hrd">{{ $hrd->fullname }}</span>
                                                                </td>
                                                                <td width="50%">
                                                                    <b>Direktur</b> <br><br>
                                                                    <div id="tombol-228">
                                                                        @if (auth()->user()->employee_id == $direktur->id)
                                                                            <a class="btn btn-primary btn-sm text-white ttd"
                                                                                id="btn_ttd_direktur"
                                                                                data-id = "{{ $direktur->id }}"
                                                                                data-tipe = "direktur">Tanda
                                                                                tangan</a>
                                                                        @endif
                                                                        <br>
                                                                    </div>

                                                                    <img id="ttd_direktur" src=""
                                                                        alt="Signature Image"
                                                                        style="display:none; max-width:60%; margin-top: -3px;"><br>
                                                                    <span
                                                                        id="ttd_nama_direktur">{{ $direktur->fullname }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.kpi.penilaian.partials.ttd')
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('.ttd').click(function(e) {
                e.preventDefault();
                let idTtd = null;
                let tipeTtd = $(this).attr('data-tipe');

                if (tipeTtd == 'pejabat_penilai') {
                    idTtd = $(this).attr('data-id');
                    $('#btn_save_ttd').attr('data-target', 'ttd_pejabat_penilai');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                    $('#btn_save_ttd').attr('data-id-rekap', "{{ $catatan->id }}");
                } else if (tipeTtd == 'penilai') {
                    idTtd = $(this).attr('data-id');
                    $('#btn_save_ttd').attr('data-target', 'ttd_penilai');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                    $('#btn_save_ttd').attr('data-id-rekap', "{{ $catatan->id }}");
                } else if (tipeTtd == 'pegawai') {
                    idTtd = $(this).attr('data-id');
                    $('#btn_save_ttd').attr('data-target', 'ttd_pegawai');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                    $('#btn_save_ttd').attr('data-id-rekap', "{{ $catatan->id }}");
                } else if (tipeTtd == 'hrd') {
                    idTtd = $(this).attr('data-id');
                    $('#btn_save_ttd').attr('data-target', 'ttd_hrd');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                    $('#btn_save_ttd').attr('data-id-rekap', "{{ $catatan->id }}");
                } else if (tipeTtd == 'direktur') {
                    idTtd = $(this).attr('data-id');
                    $('#btn_save_ttd').attr('data-target', 'ttd_direktur');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                    $('#btn_save_ttd').attr('data-id-rekap', "{{ $catatan->id }}");
                }

                if (!idTtd) {
                    alert(tipeTtd + ' belum dipilih!');
                } else {
                    $('#signatureModal').modal('show');
                }

            });
        });
    </script>
    <script>
        let idSignature = null;
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let painting = false;
        let history = [];
        const offsetX = 0;
        const offsetY = 5;

        function startPosition(e) {
            painting = true;
            draw(e);
        }

        function endPosition() {
            painting = false;
            ctx.beginPath();
            history.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
        }

        function draw(e) {
            if (!painting) return;

            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left - offsetX;
            const y = e.clientY - rect.top - offsetY;

            ctx.lineWidth = 5;
            ctx.lineCap = 'round';
            ctx.strokeStyle = 'black';

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            history = [];
        }

        function undo() {
            if (history.length > 0) {
                ctx.putImageData(history.pop(), 0, 0);
            }
        }

        function saveSignature() {
            const btn = $('#btn_save_ttd'); // Ambil tombol
            btn.prop('disabled', true).text('Saving...'); // Nonaktifkan tombol & ubah teks

            const dataURL = canvas.toDataURL('image/png');
            let idSignature = btn.attr('data-id');
            let tipe = btn.attr('data-target');
            let idRekap = btn.attr('data-id-rekap');
            let idPegawai = "{{ $penilaian_pegawai[0]->employee->id ?? '-' }}";
            let idForm = "{{ $group_penilaian->id }}";
            let periode = "{{ $penilaian_pegawai[0]->periode }}";
            let tahun = "{{ $penilaian_pegawai[0]->tahun }}";
            let pejabat_penilai = "{{ $penilaian_pegawai[0]->pejabat_penilai }}";
            let direktur = "{{ $direktur->id }}";
            console.log(idPegawai);
            $.ajax({
                url: '/api/dashboard/kpi/save-signature/' + idSignature,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    signature_image: dataURL,
                    idRekap: idRekap,
                    tipe: tipe,
                    idPegawai: idPegawai,
                    idForm: idForm,
                    periode: periode,
                    tahun: tahun,
                    pejabat_penilai: pejabat_penilai,
                    direktur: direktur,
                },
                success: function(response) {
                    // Update tampilan tanda tangan
                    $('#btn_' + tipe).parent().hide();
                    $('#' + tipe).attr('src', response.path).show();
                    $('#signatureModal').modal('hide'); // Tutup modal
                    clearCanvas();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save'); // Aktifkan kembali tombol
                }
            });
        }


        canvas.addEventListener('mousedown', startPosition);
        canvas.addEventListener('mouseup', endPosition);
        canvas.addEventListener('mousemove', draw);
    </script>
@endsection
