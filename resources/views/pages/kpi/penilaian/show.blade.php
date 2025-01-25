<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KPI - Penilaian Pegawai</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        h3 {
            font-size: 10pt !important;
            margin: 10px 0px 10px 0px;
        }

        span {
            font-size: 11pt !important;
        }

        .text-center {
            text-align: center;
        }

        .biru-muda {
            background: rgb(219, 243, 249);
        }

        .kuning-muda {
            background: rgb(255, 255, 179);
        }

        .text-hijau {
            color: rgb(0, 104, 0)
        }

        .text-merah {
            color: rgb(168, 0, 0)
        }

        table#identitas,
        table#penilaian {
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
            border: 1px solid black;
            /* Border bawah untuk setiap baris */
        }

        /* Border untuk kolom tabel */
        table#identitas td:nth-child(3),
        table#identitas th {
            border-right: 1px solid black;
        }

        table#penilaian td {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
        }

        table#penilaian {
            border-top: 0px !important;
        }

        table#detail-absensi {
            font-size: 10pt;
        }

        table#penilaian th {
            border-right: 1px solid black;
            border-top: none !important;
            background: rgb(138, 196, 248);
            color: #ffffff;
            text-align: center;
        }

        table#identitas td {
            padding: 2px 8px 2px 8px;
        }

        /* Menghapus border kanan untuk kolom terakhir */
        table#identitas tr td:last-child,
        table#identitas tr th:last-child {
            border-right: none;
        }

        table#detail_nilai {
            margin-left: 8px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        table#detail_nilai,
        table#detail_nilai tr,
        table#detail_nilai td {
            border: none;
            /* Border untuk tabel */
            border-collapse: collapse;
            padding-left: 0px;
        }
        .p-0 {
            padding: 0px !important;
        }
    </style>
</head>

<body>
    <h1 class="text-center" style="font-size: 16pt">{{$group_penilaian->nama_group}}</h1>
    <table width="100%" id="identitas" style="border: 1px solid black;border-bottom: none !important; margin-top: 0px;">
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
                <table width="100%" style="font-weight: bold;" id="detail-absensi">
                    <tr class="border-0">
                        <td class="p-0 kuning-muda">Jumlah Hari</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="border-0 p-0 kuning-muda" style="border-right: none">
                            <span id="jml_hari"> {{$attendances['total_hari'] ?? '0 Hari'}}</span>
                        </td>
                        <td class="p-0 kuning-muda">Hadir</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="p-0 kuning-muda">
                            <span id="jml_hadir"> {{$attendances['total_hadir'] ?? '0 Hari'}}</span>
                        </td>
                        <td class="p-0 kuning-muda">Alfa</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="p-0 kuning-muda">
                            <span id="jml_alfa"> {{$attendances['total_alfa'] ?? '0 Hari'}}</span>
                        </td>
                        <td class="p-0 kuning-muda">Cuti</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="p-0 kuning-muda">
                            <span id="jml_cuti"> {{$attendances['total_cuti'] ?? '0 Hari'}}</span>
                        </td>
                    </tr>
                    <tr class="border-0">
                        <td class="p-0 kuning-muda">Sakit</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="border-0 p-0 kuning-muda" style="border-right: none">
                            <span id="jml_sakit"> {{$attendances['total_sakit'] ?? '0 Hari'}}</span>
                        </td>
                        <td class="p-0 kuning-muda">Izin</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="p-0 kuning-muda">
                            <span id="jml_izin"> {{$attendances['total_izin'] ?? '0 Hari'}}</span>
                        </td>
                        <td class="p-0 kuning-muda">Telat</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="p-0 kuning-muda">
                            <span id="jml_telat"> {{$attendances['total_telat'] ?? '0 Hari'}}</span>
                        </td>
                        <td class="p-0 kuning-muda">Libur</td>
                        <td class="p-0 kuning-muda">:</td>
                        <td class="p-0 kuning-muda">
                            <span id="jml_libur"> {{$attendances['total_libur'] ?? '0 Hari'}}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" id="penilaian"
        style="border: 1px solid black; border-top: none !important; margin-top: 15px;">
        <tr>
            <th colspan="2" rowspan="3" width="50%" id="bobot_penilaian">
                <h3 style="font-size: 10pt !important;" class="font-weight-bold mb-0">ASPEK PENILAIAN
                    PENILAIAN
                </h3>
            </th>
            <th width="10%" style="font-size: 10pt !important;">BOBOT</th>
            <th width="15%" style="font-size: 10pt !important;" colspan="5">PENILAIAN</th>
            <th width="15%" style="font-size: 10pt !important;">NILAI</th>
            <th width="10%" style="font-size: 10pt !important;">TOTAL NILAI</th>
        </tr>
        <tr>
            <th rowspan="2" style="font-size: 10pt !important;">a</th>
            <th width="3.5%" style="font-size: 10pt !important;">1</th>
            <th width="3.5%" style="font-size: 10pt !important;">2</th>
            <th width="3.5%" style="font-size: 10pt !important;">3</th>
            <th width="3.5%" style="font-size: 10pt !important;">4</th>
            <th width="3.5%" style="font-size: 10pt !important;">5</th>
            <th rowspan="2" style="font-size: 10pt !important;">c=(b/( 40) ) x a </th>
            <th rowspan="2" style="font-size: 10pt !important;">d=c x 100</th>
        </tr>
        <tr>
            <th colspan="5" style="font-size: 10pt !important;">b</th>
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
                <td class="biru-muda text-center" style="width: 2% !important;font-size: 11pt; font-weight: bold;">
                    <h3>NO</h3>
                </td>
                <td class="biru-muda" style="font-size: 11pt; font-weight: bold;">
                    <h3>{{ strtoupper($aspek->nama) }}</h3>
                </td>
                <td class="kuning-muda text-center" style="font-size: 11pt; font-weight: bold;">
                    <h3 id="bobot_{{ $aspek_name_not_array }}">
                        {{ $aspek->bobot }}
                        @php
                            $bobot[] = intval($aspek->bobot);
                        @endphp
                    </h3>
                </td>
                <td class="kuning-muda text-center" colspan="5">
                    <h3 id="total_nilai_{{ $aspek_name_not_array }}" class="total_nilai">
                        {{ $total_nilai_all[$index]['nilai'] }}</h3>
                </td>
                <td class="kuning-muda text-center">
                    <h3 id="total_aspek_{{ $aspek_name_not_array }}" class="total_aspek">
                        {{ $total_nilai_all[$index]['nilai_kalkulasi'] }}</h3>
                </td>
                <td class="kuning-muda text-center">
                    <h3 id="total_akhir_{{ $aspek_name_not_array }}" class="total_akhir">
                        {{ $total_nilai_all[$index]['total_nilai'] }}</h3>
                </td>
            </tr>
            @php
                $number = 1;
            @endphp
            @foreach ($aspek->indikator_penilaians as $index => $indikator)
                <tr>
                    <td class="text-center" style="font-size: 10pt !important;">{{ $number++ }}</td>
                    <td style="font-size: 10pt !important">{{ $indikator->nama }}</td>
                    @if ($loop->last)
                        <td style="border-bottom: 1px solid black !important;"></td>
                    @else
                        <td style="border-bottom: 1px solid white !important;"></td>
                    @endif
                    <td colspan="5" class="text-center">
                        <span class="{{ $aspek_name_not_array }}"
                            style="font-size: 10pt !important;">{{ $penilaian_pegawai[$index_penilaian]->nilai }}</span>
                        @php
                            if ($index == 0) {
                                if ($total_aspek_per_row != 0 || $total_aspek_per_row != null) {
                                    $total[] = $total_aspek_per_row;
                                }
                                $total_aspek_per_row = 0;
                            }
                            $total_aspek_per_row += $penilaian_pegawai[$index_penilaian]->nilai;
                        @endphp
                    </td>
                    @if ($loop->last)
                        <td style="border-bottom: 1px solid black !important;"></td>
                        <td style="border-bottom: 1px solid black !important;"></td>
                    @else
                        <td style="border-bottom: 1px solid white !important;"></td>
                        <td style="border-bottom: 1px solid white !important;"></td>
                    @endif
                </tr>
                @php
                    $index_penilaian++;
                @endphp
            @endforeach
        @endforeach
        <tr>
            <td colspan="9"
                style="text-align: left !important; padding-left: 15px !important; color: black !important; border-right:none !important">
                <h3>TOTAL NILAI</h3>
            </td>
            <td class="kuning-muda">
                <h3 id="total_semuanya" style="font-weight: bold; font-size: 12pt !important;" class="text-center">
                    {{ $total_akhir }}
                </h3>
            </td>
        </tr>
        <tr>
            <th colspan="10">
                <h3 style="text-align: left !important; padding-left: 15px !important; font-size: 10pt !important;color: black !important;"
                    class="font-weight-bold mb-0"> NILAI
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
                <h3 id="keterangan_penilaian" style="font-size: 10pt !important; text-align: center">
                    @if ($total_akhir > 95)
                        Karyawan tersebut memiliki kinerja yang <span class="text-hijau"
                            style="font-weight: bold; font-size: 10pt;"> SANGAT BAIK </span>
                    @elseif ($total_akhir > 85 && $total_akhir < 96)
                        Karyawan tersebut memiliki kinerja yang <span class="text-hijau"
                            style="font-weight: bold; font-size: 10pt;"> BAIK </span>
                    @elseif ($total_akhir > 65 && $total_akhir < 86)
                        Karyawan tersebut memiliki kinerja yang <span class="text-hijau"
                            style="font-weight: bold; font-size: 10pt;"> CUKUP </span>
                    @elseif ($total_akhir > 50 && $total_akhir < 66)
                        Karyawan tersebut memiliki kinerja yang <span class="text-merah"
                            style="font-weight: bold; font-size: 10pt;"> KURANG </span>
                    @elseif ($total_akhir <= 50)
                        Karyawan tersebut memiliki kinerja yang <span class="text-merah"
                            style="font-weight: bold; font-size: 10pt;"> SANGAT KURANG </span>
                    @endif
                </h3>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="padding-left: 0px; height: 100px" valign="top">
                <h3 style="text-align: left !important; padding-left: 15px !important; font-size: 10pt !important;color: black !important;"
                    class="font-weight-bold mb-0"> Tanggapan dari Pegawai yang dinilai :
                </h3>
                <p id="catatan_tambahan" style="padding-left: 15px !important; font-size: 10pt !important">
                    {{ $catatan->komentar_pegawai ?? '-' }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="padding-left: 0px; height: 100px" valign="top">
                <h3 style="text-align: left !important; padding-left: 15px !important; font-size: 10pt !important;color: black !important;"
                    class="font-weight-bold mb-0"> Komentar
                    {{ $penilai['jabatan'] }}
                    {{ $penilai['unit'] }}:
                </h3>
                <p id="catatan_tambahan" style="padding-left: 15px !important; font-size: 10pt !important">
                    {{ $catatan->komentar_penilai ?? '-' }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="padding-left: 0px; height: 100px" valign="top">
                <h3 class="my-2 font-weight-bold"
                    style="text-align: left !important; padding-left: 15px !important; font-size: 10pt !important;color: black !important;">
                    Komentar
                    {{ $pejabat_penilai['jabatan'] }}
                    {{ $pejabat_penilai['jabatan'] != 'Direktur' ? $pejabat_penilai['unit'] : ' RS Livasya' }}:</h3>
                <p id="catatan_tambahan" style="padding-left: 15px !important; font-size: 10pt !important">
                    {{ $catatan->komentar_pejabat_penilai ?? '-' }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="padding: 0px;">
                <table width="100%" style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
                    <tr style="border: 0px">
                        <td width="33%" valign="top" style="border: 0px;">
                            <span style="font-size: 10pt; font-weight: bold;">
                                {{ $pejabat_penilai['jabatan'] }}
                                {{ $pejabat_penilai['jabatan'] != 'Direktur' ? $pejabat_penilai['unit'] : ' RS Livasya' }}
                            </span>
                            <center>
                                <img src="/storage/employee/ttd/signature_{{ $group_penilaian->pejabat_penilai }}.png"
                                    width="40%" style="display: block; text-align: center" alt="">
                            </center>
                            {{ $pejabat_penilai['nama'] }}

                        </td>
                        <td width="33%" valign="top" style="border: 0px;">
                            <span style="font-size: 10pt; font-weight: bold;">
                                {{ $penilai['jabatan'] }}
                                {{ $penilai['unit'] }}</span>
                            <center>
                                <img src="/storage/employee/ttd/signature_{{ $group_penilaian->penilai }}.png"
                                    width="40%" style="display: block; text-align: center" alt="">
                            </center>
                            {{ $penilai['nama'] }}

                        </td>
                        <td width="33%" valign="top" class="border-right-0" style="border: 0px;">
                            <span
                                style="font-size: 10pt; font-weight: bold;">{{ $penilaian_pegawai[0]->employee->organization->name ?? '-' }}</span>

                            <center>
                                <img src="/storage/employee/ttd/signature_{{ $penilaian_pegawai[0]->employee->id }}.png"
                                    width="40%" style="display: block; text-align: center" alt="">
                            </center>
                            {{ $penilaian_pegawai[0]->employee->fullname }}

                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</body>
<script src="/js/vendors.bundle.js"></script>
<script src="/js/app.bundle.js"></script>

</html>
