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
                                    <div class="col-md-12">
                                        <label for="tahun" class="font-weight-bold">Tahun</label>
                                        <select
                                            class="select2 form-control mb-3 w-100  @error('tahun') is-invalid @enderror"
                                            id="tahun" name="tahun">
                                            <option value=""></option>
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
                                                            class="select2 form-control w-100 employee-select @error('employee_id') is-invalid @enderror"
                                                            id="employee_id" name="employee_id">
                                                            <option value=""></option>
                                                            @foreach ($employees as $item)
                                                                <option value="{{ $item->id }}">{{ $item->id }} -
                                                                    {{ $item->fullname }}</option>
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
                                                        <select
                                                            class="select2 form-control w-100 employee-select @error('penilai') is-invalid @enderror"
                                                            id="penilai" name="penilai">
                                                            <option value=""></option>
                                                            @foreach ($employees as $item)
                                                                <option value="{{ $item->id }}">{{ $item->id }} -
                                                                    {{ $item->fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('penilai')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td width="20%">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="unit_penilai"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Jabatan</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="jabatan_penilai"></span>
                                                    </td>
                                                    <td width="20%">
                                                        NIP
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="nip_penilai"></span>
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
                                                        <select
                                                            class="select2 form-control w-100 employee-select @error('pejabat_penilai') is-invalid @enderror"
                                                            id="pejabat_penilai" name="pejabat_penilai">
                                                            <option value=""></option>
                                                            @foreach ($employees as $item)
                                                                <option value="{{ $item->id }}">{{ $item->id }} -
                                                                    {{ $item->fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('pejabat_penilai')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td width="20%">
                                                        Unit Kerja
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="unit_pejabat_penilai"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Jabatan</td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="jabatan_pejabat_penilai"></span>
                                                    </td>
                                                    <td width="20%">
                                                        NIP
                                                    </td>
                                                    <td width="1%">:</td>
                                                    <td width="29%">
                                                        <span id="nip_pejabat_penilai"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-white"
                                                        style="background-color: rgb(138, 196, 248); font-size: 11pt; font-weight: bold; padding: 8px;">
                                                        ABSENSI DAN KETERLAMBATAN DALAM 1 TAHUN
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
                                                            {{-- <tr>
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
                                                                        style="font-size: 12pt !important">Komentar Pejabat
                                                                        yang Menilai:</h3>
                                                                    <textarea class="form-control mb-3" id="komentar_penilai" name="komentar_penilai" rows="5"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr style="border: 0px">
                                                                <td colspan="10" class="border-right-0">
                                                                    <h3 class="my-2 font-weight-bold"
                                                                        style="font-size: 12pt !important">Komentar Atasan
                                                                        Pejabat yang Menilai :
                                                                    </h3>
                                                                    <textarea class="form-control mb-3" id="komentar_pejabat_penilai" name="komentar_pejabat_penilai" rows="5"></textarea>
                                                                </td>
                                                            </tr> --}}
                                                            <tr
                                                                style="border: 0px; border-top: 1px solid black !important;">
                                                                <td colspan="10" style="border-right: 0px;">
                                                                    <h3 class="font-weight-bold my-1 text-center"
                                                                        style="font-size: 12pt">PENILAIAN AKHIR</h3>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                style="border: 0px; border-top: 1px solid black !important;">
                                                                <td colspan="10" style="border-right: 0px;">
                                                                    <p class="my-1">Bedasarkan hasil penilaian
                                                                        keseluruhan disimpulkan bahwa:</p>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                style="border: 0px; border-top: 1px solid black !important;">
                                                                <td colspan="10"
                                                                    style="border-right: 0px; padding-top: 0px; padding-bottom: 0px;">
                                                                    <table width="100%" cellspacing="0"
                                                                        cellpadding="0">
                                                                        <tr style="border: 0px; height: 50px">
                                                                            <td style="width: 50%; padding: 5px 0px;">
                                                                                <h3
                                                                                    class="font-weight-bold text-center mb-0">
                                                                                    YA</h3>
                                                                            </td>
                                                                            <td
                                                                                style="width: 50%; padding: 5px 0px; border-right: 0px;">
                                                                                <h3
                                                                                    class="font-weight-bold text-center mb-0">
                                                                                    TIDAK</h3>
                                                                            </td>
                                                                        </tr>

                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                style="border: 0px; border-top: 1px solid black !important;">
                                                                <td colspan="10"
                                                                    style="border-right: 0px; padding-top: 0px; padding-bottom: 0px;">
                                                                    <table width="100%" cellspacing="0"
                                                                        cellpadding="0">
                                                                        <tr style="border: 0px; height: 50px">
                                                                            <td
                                                                                style="width: 50%; padding: 5px 8px 5px 0px;">
                                                                                <textarea class="form-control" id="keterangan_ya" name="keterangan_ya" rows="5"></textarea>
                                                                            </td>
                                                                            <td
                                                                                style="width: 50%; padding: 5px 0px 5px 8px; border-right: 0px;">
                                                                                <textarea class="form-control" id="keterangan_tidak" name="keterangan_tidak" rows="5"></textarea>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%" style="text-align: center; border: 1px solid black; margin-top: 30px">
                                                <tr>
                                                    <td class="kuning-muda" colspan="2" style="text-align: left; padding: 8px; border-bottom: 1px solid black;">
                                                        <b>Majalengka, {{now()->format('d F Y')}}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="padding-top: 15px; padding-bottom: 15px;">
                                                        <b>Karyawan yang Dinilai
                                                        </b><br><br>

                                                        <div>
                                                            <a class="btn btn-primary btn-sm text-white ttd"
                                                                id="btn_ttd_pegawai" data-tipe="pegawai">Tanda
                                                                tangan</a>
                                                            <br>
                                                        </div>
                                                        <img id="ttd_pegawai" src="" alt="Signature Image"
                                                            style="display:none; max-width:60%; margin-top: -3px;"><br>

                                                        <span id="ttd_nama_pegawai"></span>
                                                    </td>
                                                    <td width="50%" style="border-left: 1px solid black">
                                                        <b>Yang Menilai
                                                        </b><br><br>

                                                        <div>
                                                            <a class="btn btn-primary btn-sm text-white ttd"
                                                                id="btn_ttd_penilai" data-tipe = "penilai">Tanda
                                                                tangan</a>
                                                            <br>
                                                        </div>
                                                        <img id="ttd_penilai" src="" alt="Signature Image"
                                                            style="display:none; max-width:60%; margin-top: -3px;"><br>

                                                        <span id="ttd_nama_penilai"></span>
                                                    </td>
                                                </tr>
                                                <tr style="border: 0px; border-top: 1px solid black;">
                                                    <td width="50%" style="padding-top: 15px; padding-bottom: 15px;">
                                                        <b>
                                                            Mengetahui,
                                                        </b><br><br>
                                                        <div id="tombol-{{ $group_penilaian->pejabat_penilai }}">
                                                            <a class="btn btn-primary btn-sm text-white ttd"
                                                                id="btn_ttd_pejabat_penilai"
                                                                data-tipe = "pejabat_penilai">Tanda
                                                                tangan</a>
                                                            <br>
                                                        </div>

                                                        <img id="ttd_pejabat_penilai" src=""
                                                            alt="Signature Image"
                                                            style="display:none; max-width:60%; margin-top: -3px;"><br>
                                                        <span id="ttd_nama_pejabat_penilai"></span>
                                                    </td>
                                                    <td width="50%" style="border-left: 1px solid black">
                                                        <b>menyetujui,</b>
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="50%">
                                                                    <div id="tombol-{{ $group_penilaian->pejabat_penilai }}">
                                                                        <a class="btn btn-primary btn-sm text-white ttd"
                                                                            id="btn_ttd_pejabat_penilai"
                                                                            data-tipe = "pejabat_penilai">Tanda
                                                                            tangan</a>
                                                                        <br>
                                                                    </div>
            
                                                                    <img id="ttd_pejabat_penilai" src=""
                                                                        alt="Signature Image"
                                                                        style="display:none; max-width:60%; margin-top: -3px;"><br>
                                                                    <span id="ttd_nama_pejabat_penilai">HRD</span>
                                                                </td>
                                                                <td width="50%">
                                                                    <div id="tombol-{{ $group_penilaian->pejabat_penilai }}">
                                                                        <a class="btn btn-primary btn-sm text-white ttd"
                                                                            id="btn_ttd_pejabat_penilai"
                                                                            data-tipe = "pejabat_penilai">Tanda
                                                                            tangan</a>
                                                                        <br>
                                                                    </div>
            
                                                                    <img id="ttd_pejabat_penilai" src=""
                                                                        alt="Signature Image"
                                                                        style="display:none; max-width:60%; margin-top: -3px;"><br>
                                                                    <span id="ttd_nama_pejabat_penilai">Direktur</span>
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

            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                });
            });

            async function fetchAttendanceReport(employeeId, tahun) {

                try {
                    const response = await fetch(
                        `/api/dashboard/attendances/report/employee/penilaian/${employeeId}/${tahun}`, {
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

                if (!tahun) {
                    alert("mohon pilih tahun terlebih dahulu!");
                    $('#employee_id').val('');
                } else {
                    $.ajax({
                        type: "GET",
                        url: "/api/dashboard/kpi/employee/" + $(this).val() + "/get",
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('span#ttd_nama_pegawai').text(response.nama);
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

                    fetchAttendanceReport(employeeId, tahun);
                }

            });

            $('.employee-select').on('change', function(e) {
                e.preventDefault();

                let employeeId = $(this).val();
                let targetId = $(this).attr('id'); // Ambil ID elemen yang memicu event
                let tahun = $('#tahun').val(); // Jika dibutuhkan dalam fetchAttendanceReport

                if (employeeId) {
                    $.ajax({
                        type: "GET",
                        url: "/api/dashboard/kpi/employee/" + employeeId + "/get",
                        success: function(response) {
                            if (targetId === 'penilai') {
                                // Sesuaikan element tujuan penilai
                                $('span#ttd_nama_penilai').text(response.nama);
                                $('span#unit_penilai').text(response.unit);
                                $('span#jabatan_penilai').text(response.jabatan);
                                $('span#nip_penilai').text(response.nip || '-');
                            } else if (targetId === 'pejabat_penilai') {
                                // Sesuaikan element tujuan pejabat penilai
                                $('span#ttd_nama_pejabat_penilai').text(response.nama);
                                $('span#unit_pejabat_penilai').text(response.unit);
                                $('span#jabatan_pejabat_penilai').text(response.jabatan);
                                $('span#nip_pejabat_penilai').text(response.nip || '-');
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert(xhr.responseJSON.error);
                        }
                    });
                }
            });

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

                let isValid = true; // Flag untuk validasi

                // Loop melalui semua input di dalam form kecuali textarea
                $(this).find('input, select').not('textarea').each(function() {
                    if ($(this).val().trim() === '') {
                        isValid = false; // Jika ada input yang kosong, set flag ke false
                        $(this).addClass(
                            'is-invalid'); // Tambahkan class untuk error styling (opsional)
                        alert('Field ' + $(this).attr('name') + ' harus diisi!');
                    } else {
                        $(this).removeClass('is-invalid'); // Hapus class error jika sudah terisi
                    }
                });

                if (!isValid) {
                    e.preventDefault(); // Hentikan submit jika ada input kosong
                } else {
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
                                        '/penilaian/daftar-form';
                                }, 1000);
                            },
                            error: function(xhr) {
                                // $('#create-attendance-form').modal('hide');
                                showErrorAlertNoRefresh(xhr.responseJSON.error);
                                console.log(xhr);
                            }
                        });
                    }
                }
            });

            $('.ttd').click(function(e) {
                e.preventDefault();
                let idTtd = null;
                let tipeTtd = $(this).attr('data-tipe');

                if (tipeTtd == 'pejabat_penilai') {
                    idTtd = $('#pejabat_penilai').val();
                    $('#btn_save_ttd').attr('data-target', 'ttd_pejabat_penilai');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                } else if (tipeTtd == 'penilai') {
                    idTtd = $('#penilai').val();
                    $('#btn_save_ttd').attr('data-target', 'ttd_penilai');
                    $('#btn_save_ttd').attr('data-id', idTtd);
                } else {
                    idTtd = $('#employee_id').val();
                    $('#btn_save_ttd').attr('data-target', 'ttd_pegawai');
                    $('#btn_save_ttd').attr('data-id', idTtd);
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
            const dataURL = canvas.toDataURL('image/png');
            idSignature = $('#btn_save_ttd').attr('data-id');
            let tipe = $('#btn_save_ttd').attr('data-target');
            $.ajax({
                url: '/api/dashboard/kpi/save-signature/' + idSignature,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    signature_image: dataURL
                },
                success: function(response) {
                    // Update the signature display
                    $('#btn_' + tipe).parent().hide();
                    $('#' + tipe).attr('src', response.path).show();
                    $('#signatureModal').modal('hide'); // Hide the modal
                    clearCanvas();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        canvas.addEventListener('mousedown', startPosition);
        canvas.addEventListener('mouseup', endPosition);
        canvas.addEventListener('mousemove', draw);
    </script>
@endsection
