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
                        <h2>
                            Tambah Penilaian Pegawai
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pt-2">
                            <form action="" method="POST" id="penilaian-form" data-form="{{ $group_penilaian->id }}">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="periode" class="font-weight-bold">Periode</label>
                                        <select
                                            class="select2 form-control mb-3 w-100  @error('periode') is-invalid @enderror"
                                            id="periode" name="periode">
                                            <option value="Januari - Maret">Januari - Maret</option>
                                            <option value="April - Juni">April - Juni</option>
                                            <option value="Juli - September">Juli - September</option>
                                            <option value="Oktober - Desember">Oktober - Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tahun" class="font-weight-bold">Tahun</label>
                                        <select
                                            class="select2 form-control mb-3 w-100  @error('tahun') is-invalid @enderror"
                                            id="tahun" name="tahun">
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                            <option value="2028">2028</option>
                                            <option value="2029">2029</option>
                                            <option value="2030">2030</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-wrapper">
                                        <div class="bungkus">
                                            <table width="100%" id="identitas"
                                                style="border: 1px solid black; margin-top: 20px;">
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h3 class="my-2 font-weight-bold"
                                                            style="font-size: 12pt !important">
                                                            PEGAWAI YANG
                                                            DINILAI</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Nama</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <select
                                                            class="select2 form-control w-100  @error('employee_id') is-invalid @enderror"
                                                            id="employee_id" name="employee_id">
                                                            <option value=""></option>
                                                            @foreach ($employees as $item)
                                                                <option value="{{ $item->id }}">{{ $item->id }} -
                                                                    {{ $item->fullname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('employee_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td width="20%">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="unit_pegawai"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Jabatan</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="jabatan_pegawai"></span>
                                                    </td>
                                                    <td width="20%">
                                                        NIP
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="nip_pegawai"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h3 class="my-2 font-weight-bold"
                                                            style="font-size: 12pt !important">
                                                            PEJABAT YANG MENILAI</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Nama</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $penilai['nama'] }}
                                                    </td>
                                                    <td width="20%">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $penilai['unit'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Jabatan</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $penilai['jabatan'] }}
                                                    </td>
                                                    <td width="20%">
                                                        NIP
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $penilai['nip'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h3 class="my-2 font-weight-bold"
                                                            style="font-size: 12pt !important">
                                                            ATASAN PEJABAT PENILAI</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Nama</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $pejabat_penilai['nama'] }}
                                                    </td>
                                                    <td width="20%">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $pejabat_penilai['unit'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Jabatan</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $pejabat_penilai['jabatan'] }}
                                                    </td>
                                                    <td width="20%">
                                                        NIP
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        {{ $pejabat_penilai['nip'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-white"
                                                        style="background-color: rgb(138, 196, 248); font-size: 11pt; font-weight: bold; padding: 8px;">
                                                        ABSENSI DAN KETERLAMBATAN DALAM 3 BULAN
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="kuning-muda">
                                                        <table width="100%" style="font-weight: bold;">
                                                            <tr class="border-0">
                                                                <td class="p-0 kuning-muda">Jumlah Hari</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="border-0 p-0 kuning-muda">
                                                                    <span id="jml_hari"> 0 Hari</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Hadir</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_hadir"> 0 Hari</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Alfa</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_alfa"> 0 Hari</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Cuti</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_cuti"> 0 Hari</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="border-0">
                                                                <td class="p-0 kuning-muda">Sakit</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="border-0 p-0 kuning-muda">
                                                                    <span id="jml_sakit"> 0 Hari</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Izin</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_izin"> 0 Hari</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Telat</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_telat"> 0 Hari</span>
                                                                </td>
                                                                <td class="p-0 kuning-muda">Libur</td>
                                                                <td class="p-0 kuning-muda">:</td>
                                                                <td class="p-0 kuning-muda">
                                                                    <span id="jml_libur"> 0 Hari</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" style="padding: 0px;">
                                                        <table width="100%" id="penilaian">
                                                            <tr>
                                                                <th colspan="2" rowspan="3" width="45%">
                                                                    <h3 style="font-size: 12pt"
                                                                        class="font-weight-bold mb-0">BOBOT PENILAIAN:</h3>
                                                                </th>
                                                                <th width="10%">BOBOT</th>
                                                                <th width="20%" colspan="5">PENILAIAN</th>
                                                                <th width="10%">NILAI</th>
                                                                <th width="10%" class="border-right-0">TOTAL NILAI</th>
                                                            </tr>
                                                            <tr>
                                                                <th rowspan="2">a</th>
                                                                <th width="3.5%">1</th>
                                                                <th width="3.5%">2</th>
                                                                <th width="3.5%">3</th>
                                                                <th width="3.5%">4</th>
                                                                <th width="3.5%">5</th>
                                                                <th rowspan="2">
                                                                    {{ $rumus_penilaian == 'rata-rata' ? '=(b / (5 * n) ) x a' : 'c=(b/( 40) ) x a' }}
                                                                </th>
                                                                <th rowspan="2" class="border-right-0">d=c x 100</th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="5">b</th>
                                                            </tr>
                                                            @php
                                                                $number = 0;
                                                            @endphp
                                                            @foreach ($group_penilaian->aspek_penilaians as $index => $aspek)
                                                                <tr>
                                                                    <td class="biru-muda text-center"
                                                                        style="font-size: 11pt; font-weight: bold; width: 1%;">
                                                                        NO</td>
                                                                    <td class="biru-muda"
                                                                        style="font-size: 11pt; font-weight: bold;">
                                                                        {{ strtoupper($aspek->nama) }}</td>

                                                                    @php
                                                                        $aspek_name = $aspek->nama;
                                                                        $aspek_name = strtolower($aspek_name);
                                                                        $aspek_name = str_replace(
                                                                            ' ',
                                                                            '_',
                                                                            $aspek_name,
                                                                        );
                                                                        $aspek_name_not_array = $aspek_name;
                                                                        $aspek_name .= '[]';
                                                                    @endphp

                                                                    <td class="kuning-muda text-center"
                                                                        style="font-size: 11pt; font-weight: bold;">
                                                                        <span
                                                                            id="bobot_{{ $aspek_name_not_array }}">{{ $aspek->bobot }}</span><span>%</span>
                                                                    </td>
                                                                    <td class="kuning-muda text-center" colspan="5">
                                                                        <input type="text"
                                                                            id="total_nilai_{{ $aspek_name_not_array }}"
                                                                            name="total_nilai_{{ $aspek_name_not_array }}"
                                                                            style="background: transparent; text-align:center; font-size: 11pt; height: 30px;"
                                                                            class="form-control font-weight-bold form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                                            placeholder="Total">
                                                                    </td>
                                                                    <td class="kuning-muda text-center">
                                                                        <input type="text"
                                                                            id="total_aspek_{{ $aspek_name_not_array }}"
                                                                            name="total_aspek_{{ $aspek_name_not_array }}"
                                                                            style="background: transparent; text-align:center; font-size: 11pt; height: 30px;"
                                                                            class="form-control font-weight-bold form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                                            placeholder="Nilai">
                                                                    </td>
                                                                    <td class="kuning-muda text-center border-right-0">
                                                                        <input type="text"
                                                                            id="total_akhir_{{ $aspek_name_not_array }}"
                                                                            name="total_akhir_{{ $aspek_name_not_array }}"
                                                                            style="background: transparent; text-align:center; font-size: 11pt; height: 30px;"
                                                                            class="form-control total_semuanya font-weight-bold form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                                            placeholder="Total Nilai">
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $number = 1;
                                                                @endphp
                                                                @foreach ($aspek->indikator_penilaians as $index => $indikator)
                                                                    <tr>
                                                                        <td class="text-center">{{ $number++ }}</td>
                                                                        <td>{{ $indikator->nama }}</td>
                                                                        @if ($index == 0)
                                                                            <td
                                                                                rowspan="{{ $aspek->indikator_penilaians->count() }}">

                                                                            </td>
                                                                        @endif
                                                                        <td colspan="5">
                                                                            <input type="text"
                                                                                id="example-input-material"
                                                                                style="background: transparent; text-align:center; font-size: 11pt; height: 30px;"
                                                                                name="nilai_{{ $aspek_name }}"
                                                                                class="form-control form-control-lg {{ $aspek_name_not_array }} nilai-isi rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                                                placeholder=".....">
                                                                        </td>
                                                                        @if ($index == 0)
                                                                            <td
                                                                                rowspan="{{ $aspek->indikator_penilaians->count() }}">
                                                                            </td>
                                                                        @endif
                                                                        @if ($index == 0)
                                                                            <td class="border-right-0"
                                                                                rowspan="{{ $aspek->indikator_penilaians->count() }}">
                                                                            </td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="9" style="border-right: 0px;">
                                                                    <h3 class="font-weight-bold my-1"
                                                                        style="font-size: 12pt">JUMLAH NILAI</h3>
                                                                </td>
                                                                <td colspan="1"
                                                                    class="kuning-muda text-center border-right-0">
                                                                    <h3 class="font-weight-bold my-1"
                                                                        style="font-size: 12pt" id="total_semuanya_fix">
                                                                    </h3>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table id="detail_nilai"
                                                                        style="font-size: 10pt !important">
                                                                        <tr>
                                                                            <td class="border-right-0">Sangat Baik /
                                                                                Istimewa</td>
                                                                            <td class="text-center border-right-0"
                                                                                style="width: 10%">:
                                                                            </td>
                                                                            <td class="border-right-0">> 95</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="border-right-0">Baik</td>
                                                                            <td class="text-center border-right-0">:</td>
                                                                            <td class="border-right-0">86 s/d 95</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="border-right-0">Cukup</td>
                                                                            <td class="text-center border-right-0">:</td>
                                                                            <td class="border-right-0">66 s/d 85</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="border-right-0">Kurang</td>
                                                                            <td class="text-center border-right-0">:</td>
                                                                            <td class="border-right-0">51 s/d 65</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="border-right-0">Sangat Kurang</td>
                                                                            <td class="text-center border-right-0">:</td>
                                                                            <td class="border-right-0">
                                                                                < 50</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td colspan="8" class="border-right-0">
                                                                    <h3 id="keterangan_penilaian" class="font-weight-bold"
                                                                        style="font-size: 11pt !important; text-align: center">
                                                                    </h3>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="10" class="border-right-0">
                                                                    <h3 class="my-2 font-weight-bold border-right-0"
                                                                        style="font-size: 12pt !important">Tanggapan dari
                                                                        Pegawai yang dinilai:</h3>
                                                                    <textarea class="form-control mb-3" id="komentar_pegawai" name="komentar_pegawai" rows="5"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="10" class="border-right-0">
                                                                    <h3 class="my-2 font-weight-bold"
                                                                        style="font-size: 12pt !important">Komentar
                                                                        {{ $penilai['jabatan'] }}
                                                                        {{ $penilai['unit'] }}:</h3>
                                                                    <textarea class="form-control mb-3" id="komentar_penilai" name="komentar_penilai" rows="5"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr style="border: 0px">
                                                                <td colspan="10" class="border-right-0">
                                                                    <h3 class="my-2 font-weight-bold"
                                                                        style="font-size: 12pt !important">Komentar
                                                                        {{ $pejabat_penilai['jabatan'] }}
                                                                        {{ $pejabat_penilai['jabatan'] != 'Direktur' ? $pejabat_penilai['unit'] : ' RS Livasya' }}:
                                                                    </h3>
                                                                    <textarea class="form-control mb-3" id="komentar_pejabat_penilai" name="komentar_pejabat_penilai" rows="5"></textarea>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" style="padding: 0px;">
                                                        <table width="100%"
                                                            style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
                                                            <tr style="border: 0px">
                                                                <td width="33%">
                                                                    <b>
                                                                        {{ $pejabat_penilai['jabatan'] }}
                                                                        {{ $pejabat_penilai['jabatan'] != 'Direktur' ? $pejabat_penilai['unit'] : ' RS Livasya' }}:
                                                                    </b><br><br>
                                                                    <div
                                                                        id="tombol-{{ $group_penilaian->pejabat_penilai }}">
                                                                        <a class="btn btn-primary btn-sm text-white ttd"
                                                                            onclick="openSignaturePad({{ $group_penilaian->pejabat_penilai }})"
                                                                            id="ttd_pegawai">Tanda
                                                                            tangan</a>
                                                                        <br>
                                                                    </div>

                                                                    <img id="signature-display-{{ $group_penilaian->pejabat_penilai }}"
                                                                        src="" alt="Signature Image"
                                                                        style="display:none; max-width:60%;"><br>

                                                                    {{ $pejabat_penilai['nama'] }}

                                                                </td>
                                                                <td width="33%">
                                                                    <b>
                                                                        {{ $penilai['jabatan'] }}
                                                                        {{ $penilai['unit'] }}</b><br><br>

                                                                    <div id="tombol-{{ $group_penilaian->penilai }}">
                                                                        <a class="btn btn-primary btn-sm text-white"
                                                                            onclick="openSignaturePad({{ $group_penilaian->penilai }})">Tanda
                                                                            tangan</a>
                                                                        <br>
                                                                    </div>
                                                                    <img id="signature-display-{{ $group_penilaian->penilai }}"
                                                                        src="" alt="Signature Image"
                                                                        style="display:none; max-width:60%;"><br>
                                                                    {{ $penilai['nama'] }}

                                                                </td>
                                                                <td width="33%" class="border-right-0" valign="top">
                                                                    <span id="jabatan_pegawai_ttd"
                                                                        style="font-weight: bold;"></span> <span
                                                                        id="unit_pegawai_ttd"
                                                                        style="font-weight: bold;"></span><br><br>

                                                                    <div id="tombol-pegawai">
                                                                        <a class="btn btn-primary btn-sm text-white"
                                                                            data-id="{{ $group_penilaian->penilai }}">Pilih Pegawai Dahulu</a>
                                                                        <br>
                                                                    </div>
                                                                    <img id="signature-display" src=""
                                                                        alt="Signature Image"
                                                                        style="display:none; max-width:60%;"><br>
                                                                    <span id="nama_pegawai_ttd"></span>

                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-info btn-block">
                                            <div class="ikon-tambah">
                                                <span class="fal fa-upload mr-1"></span>
                                                Tambah
                                            </div>
                                            <div class="span spinner-text d-none">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </div>
                                        </button>
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
            let check = false;
            let total_semuanya = null;
            let jml_aspek = [];
            const rumus_penilaian = @json($rumus_penilaian);
            // Dapatkan bulan saat ini
            let currentMonth = new Date().getMonth(); // Januari adalah 0, Desember adalah 11

            // Tentukan periode berdasarkan bulan saat ini
            let periode;
            if (currentMonth >= 0 && currentMonth <= 2) {
                periode = "Januari - Maret";
            } else if (currentMonth >= 3 && currentMonth <= 5) {
                periode = "April - Juni";
            } else if (currentMonth >= 6 && currentMonth <= 8) {
                periode = "Juli - September";
            } else if (currentMonth >= 9 && currentMonth <= 11) {
                periode = "Oktober - Desember";
            }

            // Dapatkan tahun saat ini
            var currentYear = new Date().getFullYear();

            // Pilih option yang sesuai dengan tahun
            $('#tahun').val(currentYear);

            // Pilih option yang sesuai dengan periode
            $('#periode').val(periode);

            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                });
            });

            async function fetchAttendanceReport(employeeId, periode, tahun) {
                // Mapping bulan ke angka
                const months = {
                    'Januari': 1,
                    'Februari': 2,
                    'Maret': 3,
                    'April': 4,
                    'Mei': 5,
                    'Juni': 6,
                    'Juli': 7,
                    'Agustus': 8,
                    'September': 9,
                    'Oktober': 10,
                    'November': 11,
                    'Desember': 12
                };

                let parts = periode.split(' - ');

                let startMonthName = parts[0];
                let endMonthName = parts[1];

                let periodeFix = months[startMonthName] + " - " + months[endMonthName];
                try {
                    const response = await fetch(
                        `/api/dashboard/attendances/report/employee/${employeeId}/${periodeFix}/${tahun}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error);
                    }

                    const data = await response.json();
                    if (data) {
                        $('#jml_hari').text(data.total_hari + ' hari');
                        $('#jml_hadir').text(data.total_hadir + ' hari');
                        $('#jml_izin').text(data.total_izin + ' hari');
                        $('#jml_sakit').text(data.total_sakit + ' hari');
                        $('#jml_telat').text(data.total_telat + ' menit');
                        $('#jml_cuti').text(data.total_cuti + ' hari');
                        $('#jml_alfa').text(data.total_absent + ' hari');
                        $('#jml_libur').text(data.total_libur + ' hari');
                    }
                } catch (error) {
                    showErrorAlert(error.message);
                }
            }


            $('#employee_id').on('change', function(e) {
                e.preventDefault();

                let employeeId = $(this).val();
                idSignature = employeeId;
                let periodeOnChange = $('#periode').val();
                let tahun = $('#tahun').val();

                $.ajax({
                    type: "GET",
                    url: "/api/dashboard/kpi/employee/" + $(this).val() + "/get",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('span#nama_pegawai_ttd').text(response.nama);
                        $('span#unit_pegawai').text(response.unit);
                        $('span#unit_pegawai_ttd').text(response.unit);
                        $('span#jabatan_pegawai').text(response.jabatan);
                        $('span#jabatan_pegawai_ttd').text(response.jabatan);

                        $('span#nip_pegawai').text(response.nip || '-');

                        $('#tombol-pegawai a').attr('onclick', 'openSignaturePad(null)');
                        $('#tombol-pegawai a').text('Tanda Tangan');

                    },
                    error: function(xhr) {
                        // $('#create-attendance-form').modal('hide');
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });

                fetchAttendanceReport(employeeId, periodeOnChange, tahun);
            })

            function updateTotal(nama) {
                const namaDariInput = nama;
                let fixName = nama.replace("nilai_", "");
                const fixNameNoArray = fixName.replace(/\[\]$/, "");


                let total = 0;
                let input = "input[name='" + namaDariInput + "']";

                $(input).each(function() {
                    var value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                if (rumus_penilaian == 'rata-rata') {
                    // Set the total in the 'total-nilai' input
                    let inputs = $("input[name='" + namaDariInput + "']");
                    let jumlahInput = inputs.length;

                    $('#total_nilai_' + fixNameNoArray).val(total);
                    const bobot = parseFloat($('#bobot_' + fixNameNoArray).text().trim());
                    const total_aspek = (total / (jumlahInput * 5)) * bobot / 100;
                    $('#total_aspek_' + fixNameNoArray).val(total_aspek.toFixed(2));
                    const total_akhir = (total_aspek * 100);
                    $('#total_akhir_' + fixNameNoArray).val(total_akhir.toFixed(2)).trigger('change');
                } else {
                    // Set the total in the 'total-nilai' input
                    $('#total_nilai_' + fixNameNoArray).val(total);
                    const bobot = $('#bobot_' + fixNameNoArray).text().trim();
                    const total_aspek = (total / 40) * bobot / 100;
                    $('#total_aspek_' + fixNameNoArray).val(total_aspek);
                    const total_akhir = (total_aspek * 100);
                    $('#total_akhir_' + fixNameNoArray).val(total_akhir).trigger('change');;
                }
            }

            // Attach event handler to update total when any 'sikap-kerja' input changes
            $('.nilai-isi').on('keyup change', function() {
                if ($(this).val() > 5) {
                    $(this).val('');
                }
                updateTotal($(this).attr('name'));
            });

            // Fungsi untuk menghitung total
            function hitungTotal() {
                var total_semuanya = 0;
                $('.total_semuanya').each(function() {
                    // Parse nilai input ke dalam bentuk angka
                    var nilai = parseFloat($(this).val()) || 0;
                    total_semuanya += nilai;
                });
                // Menampilkan hasil total
                $('#total_semuanya_fix').html(total_semuanya);
                let text = `Karyawan tersebut memiliki kinerja yang`;
                if (total_semuanya > 95) {
                    $('#keterangan_penilaian').html(`${text} <span class="text-hijau"
                        style="font-weight: bold; font-size: 10pt;"> SANGAT BAIK </span>`);

                } else if (total_semuanya > 85 && total_semuanya < 96) {
                    $('#keterangan_penilaian').html(`${text} <span class="text-hijau"
                        style="font-weight: bold; font-size: 10pt;"> BAIK </span>`);
                } else if (total_semuanya > 65 && total_semuanya < 86) {
                    $('#keterangan_penilaian').html(`${text} <span class="text-hijau"
                        style="font-weight: bold; font-size: 10pt;"> CUKUP </span>`);
                } else if (total_semuanya > 50 && total_semuanya < 66) {
                    $('#keterangan_penilaian').html(`${text} <span class="text-merah"
                        style="font-weight: bold; font-size: 10pt;"> KURANG </span>`);
                } else if (total_semuanya <= 50) {
                    $('#keterangan_penilaian').html(`${text} <span class="text-merah"
                        style="font-weight: bold; font-size: 10pt;"> SANGAT KURANG </span>`);
                }
            }

            $('.total_semuanya').on('change', function() {
                hitungTotal();

            });

            $('#penilaian-form').on('submit', function(e) {
                e.preventDefault();
                const id_form = $(this).attr('data-form');
                const id_pegawai = $('#employee_id').val();
                let formData = new FormData(this);

                if (id_pegawai == null || id_pegawai == "") {
                    showErrorAlertNoRefresh("Pegawai Wajib dipilih!")
                } else {
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/kpi/' + id_form + "/" + id_pegawai +
                            "/store",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#penilaian-form').find('.ikon-tambah').hide();
                            $('#penilaian-form').find('.spinner-text').removeClass(
                                'd-none');
                        },
                        success: function(response) {
                            $('#penilaian-form').find('.ikon-edit').show();
                            $('#penilaian-form').find('.spinner-text').addClass(
                                'd-none');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                window.location.href =
                                    '/kpi/master-data/group-penilaian/rekap/bulanan';
                            }, 1000);
                        },
                        error: function(xhr) {
                            // $('#create-attendance-form').modal('hide');
                            showErrorAlertNoRefresh(xhr.responseJSON.error);
                        }
                    });
                }
            });

            function openSignaturePad() {
                idSignature = $(this).attr('data-id');
                $('#signatureModal').modal('show'); // Example using Bootstrap modal
            }

            // $('#ttd_pegawai').click(function(e) {
            //     e.preventDefault();
            //     var url = "{{ route('ttd') }}";
            //     // Ukuran dan posisi popup baru
            //     var width = screen.width;
            //     var height = screen.height;
            //     var left = 0;
            //     var top = 0;
            //     window.open(url, 'popupWindow',
            //         `width=${width},height=${height},top=${top},left=${left}`);
            // })

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
            const dataURL = canvas.toDataURL('image/png');
            $.ajax({
                url: '/api/dashboard/kpi/save-signature/' + idSignature,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    signature_image: dataURL
                },
                success: function(response) {
                    // Update the signature display
                    $('#tombol-' + idSignature).hide();
                    $('#signature-display-' + idSignature).attr('src', response.path).show();
                    $('#signatureModal').modal('hide'); // Hide the modal
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function openSignaturePad(id) {
            if (id == null) {
                $('#tombol-pegawai').attr('id', 'tombol-' + idSignature);
                $('#signature-display').attr('id', 'signature-display-' + idSignature);
            } else {
                idSignature = id;
            }


            $('#signatureModal').modal('show');
        }

        canvas.addEventListener('mousedown', startPosition);
        canvas.addEventListener('mouseup', endPosition);
        canvas.addEventListener('mousemove', draw);
    </script>
@endsection
