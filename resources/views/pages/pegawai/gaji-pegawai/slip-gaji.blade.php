    @extends('inc.layout')
    @section('title', 'Pegawai')
    @section('extended-css')
        <style>
            body {
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            }

            @media print {
                @page {
                    size: 210mm 297mm;
                    /* Ukuran halaman A4 */
                    margin: 0;
                    page-break-after: always;
                    transform: scale(0.97);
                    /* Skala cetakan 9.7 */
                    transform-origin: center;
                }

                .slip-gaji {
                    height: 50%;
                    max-height: 148.5mm;
                    overflow: hidden;
                    padding: 5px;
                    margin-top: 8px !important;
                }

                h1 {
                    margin-top: 5px;
                    margin-bottom: 5px;
                }
            }

            .slip-gaji {
                position: relative;
                /* height: 50%; */
                /* max-height: 148.5mm; */
                overflow: hidden;
                padding: 5px;
                border: 1px solid rgba(0, 0, 0, 0.884);
                margin-top: 8px !important;
            }

            img.background {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                margin: auto;
                width: 500px;
                height: 500px;
                opacity: 0.27;
                z-index: -1;
            }

            .judul-wrapper {
                border: 1px solid rgba(0, 0, 0, 0.884);
            }

            .judul-wrapper h1.judul-pay-slip,
            .judul-wrapper small {
                font-size: 15pt;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                text-align: center;
                margin-top: 5px;
                margin-bottom: 5px;
            }

            .content-wrapper {
                padding: 10px;
                margin-top: 5px;
                margin-bottom: 5px;
                border: 1px solid rgba(0, 0, 0, 0.884);
                font-size: 10pt;
            }

            .content-wrapper .profile {
                font-weight: 600;
                margin: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th,
            table td {
                padding: 1px;
            }

            .payslip-wrapper {
                display: flex;
                /* Menggunakan flexbox */
            }

            .tabel-penerimaan,
            .tabel-potongan {
                flex: 1;
                /* Menggunakan flex-grow agar lebar tabel sama */
                height: 100%;
                /* Menggunakan tinggi 100% untuk membuat tinggi sama */
            }

            td span.child {
                margin-left: 16px;
            }

            .align-left {
                float: left;
            }

            .align-right {
                float: right;
            }

            .text-green {
                color: rgb(24, 173, 24);
            }

            .text-red {
                color: rgb(201, 38, 38);
            }
        </style>
    @endsection
    @section('content')
        <main id="js-page-content" role="main" class="page-content">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row mb-5">
                        <div class="col-xl-12">
                            <div id="panel-1" class="panel">
                                <div class="panel-container show">
                                    <div class="tambah-pegawai-baru mt-5 mb-3">
                                        <form action="{{ route('payroll.slip-gaji.show.print') }}" method="get"
                                            class="mx-5">
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <div id="step-1">
                                                <div class="form-group mb-3">
                                                    <label for="periode">Periode</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('periode') is-invalid @enderror"
                                                        name="periode" id="periode">
                                                        @php
                                                            $currentYear = date('Y');
                                                            $nextYear = $currentYear + 1;
                                                            $months = [
                                                                'Januari',
                                                                'Februari',
                                                                'Maret',
                                                                'April',
                                                                'Mei',
                                                                'Juni',
                                                                'Juli',
                                                                'Agustus',
                                                                'September',
                                                                'Oktober',
                                                                'November',
                                                                'Desember',
                                                            ];
                                                            $lastSearchPeriod = $request->periode ?? ''; // Mendapatkan periode terakhir yang dicari

                                                            foreach ($months as $index => $month) {
                                                                $nextIndex = ($index + 1) % 12; // Menyesuaikan indeks bulan berikutnya
                                                                $nextMonth = $months[$nextIndex];
                                                                $year = $index < 11 ? $currentYear : $nextYear; // Menentukan tahun

                                                                $period = "{$month} {$currentYear} - {$nextMonth} {$year}";
                                                                $selected =
                                                                    $period == $lastSearchPeriod ? 'selected' : ''; // Menandai opsi yang sesuai

                                                                echo "<option value=\"{$period}\" {$selected}>{$period}</option>";
                                                            }
                                                        @endphp
                                                    </select>
                                                    @error('periode')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!-- Hanya menampilkan data payroll ketika periode telah diisi -->
                                                <div class="btn-next mt-3 text-right">
                                                    <button type="submit"
                                                        class="btn-next-step btn btn-primary btn-sm ml-2">
                                                        <div class="ikon-tambah">
                                                            <span class="fal fa-search mr-1"></span>Cari
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
                            @if ($payroll !== [])
                                <div id="panel-2" class="panel">
                                    <div class="panel-container show m-5">
                                        <div class="slip-gaji">
                                            <img src="{{ asset('/img/logo-payslip.jpg') }}" alt="background-logo"
                                                class="background" style="z-index: 999999">
                                            <div class="judul-wrapper">
                                                <h1 class="judul-pay-slip text-black">
                                                    SLIP GAJI KARYAWAN <br>
                                                    PERIODE ({{ $payroll->periode }})
                                                </h1>
                                            </div>
                                            <div class="content-wrapper" style="padding-top: 3px; padding-bottom: 3px">
                                                <table class="profile">
                                                    <tbody>
                                                        <tr>
                                                            <td width="17%">
                                                                Nama Karyawan
                                                            </td>
                                                            <td width="5%">:</td>
                                                            <td width="38%">
                                                                {{ $payroll->employee->fullname }}
                                                            </td>
                                                            <td width="20.6%">
                                                                NIK
                                                            </td>
                                                            <td width="3.4%">:</td>
                                                            <td width="26%">
                                                                {{ $payroll->employee->identity_number }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="17%">
                                                                Bagian
                                                            </td>
                                                            <td width="5%">:</td>
                                                            <td width="38%">
                                                                {{ $payroll->employee->organization->name ?? '*belum setting' }}
                                                            </td>
                                                            <td width="20.6%%">
                                                                Status
                                                            </td>
                                                            <td width="3.4%">:</td>
                                                            <td width="26%">
                                                                {{ $payroll->employee->employment_status }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="17%">
                                                                Jabatan
                                                            </td>
                                                            <td width="5%">:</td>
                                                            <td width="38%">
                                                                {{ $payroll->employee->jobPosition->name ?? '*belum setting' }}
                                                            </td>
                                                            <td width="20.6%%">
                                                                Jumlah Masuk Kerja
                                                            </td>
                                                            <td width="3.4%">:</td>
                                                            <td width="26%">
                                                                {{ $payroll->hari_kerja }} Hari
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="payslip-wrapper content-wrapper" style="padding-top: 6px;">
                                                <div class="tabel-penerimaan">
                                                    <table style="width: 97%">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="3"><b><u>KOMPONEN PENERIMAAN</b></u></td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <b>a. Gaji Pokok</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->basic_salary) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <b>b. Tunjangan</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%"></td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <span class="child">- Tunjangan Makan &
                                                                        Transport</span>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->tunjangan_makan_dan_transport) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <span class="child">- Tunjangan Profesi</span>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->tunjangan_profesi) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <span class="child">- Tunjangan Masa Kerja</span>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%"
                                                                    style="border-bottom: 1px solid rgba(0, 0, 0, 0.884)">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->tunjangan_masa_kerja) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <b>c. Jumlah ( a + b )</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <b>
                                                                        <span class="align-left">Rp.</span>
                                                                        <span
                                                                            class="align-right">{{ rp2($payroll->basic_salary + $payroll->tunjangan_makan_dan_transport + $payroll->tunjangan_profesi + $payroll->tunjangan_masa_kerja) }}</span>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <b>d. Tunjangan Tidak Tetap</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%"></td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <span class="child">- Tunjangan Jabatan</span>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->tunjangan_jabatan) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <span class="child">- Guarantee Fee</span>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->guarantee_fee) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <span class="child">- Uang Duduk</span>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%"
                                                                    style="border-bottom: 1px solid rgba(0, 0, 0, 0.884)">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->uang_duduk) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-green">
                                                                <td width="60%">
                                                                    <b>e. Jumlah ( c + d )</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <b>
                                                                        <span class="align-left">Rp.</span>
                                                                        <span
                                                                            class="align-right">{{ rp2($payroll->tunjangan_jabatan + $payroll->guarantee_fee + $payroll->uang_duduk) }}</span>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tabel-potongan text-red">
                                                    <table style="width: 97%; float: right;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="3"><b><u>KOMPONEN POTONGAN</b></u></td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>f. Simpanan Pokok Koperasi</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->simpanan_pokok) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>g. BPJS Kesehatan</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">

                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->potongan_bpjs_kesehatan) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>h. BPJS TK (JHT)</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">

                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->potongan_bpjs_ketenagakerjaan) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>i. Pinjaman Koperasi</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">

                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->potongan_koperasi) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>j. Potongan Keterlambatan</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">

                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->potongan_keterlambatan) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>k. Potongan Izin</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">

                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->potongan_izin) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>l. Potongan Pajak</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">

                                                                    <span class="align-left">Rp.</span>
                                                                    <span
                                                                        class="align-right">{{ rp2($payroll->potongan_pajak) }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-red">
                                                                <td width="60%">
                                                                    <b>m. Jumlah (f s.d. m)</b>
                                                                </td>
                                                                <td width="5%">:</td>
                                                                <td width="35%">
                                                                    <b>
                                                                        <span class="align-left">Rp.</span>
                                                                        <span
                                                                            class="align-right">{{ rp2($payroll->total_deduction) }}</span>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="content-wrapper"
                                                style="padding-top: 5px; padding-bottom: 8px; font-weight: 600; margin-top: -9px;">
                                                <table>
                                                    <tr>
                                                        <td width="80.6%">
                                                            TOTAL GAJI DITERIMA
                                                        </td>
                                                        <td width="2.4%">:</td>
                                                        <td width="17%">
                                                            <span class="align-left">Rp.</span>
                                                            <span
                                                                class="align-right">{{ rp2($payroll->take_home_pay) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="80.6%">
                                                            PEMBULATAN
                                                        </td>
                                                        <td width="2.4%">:</td>
                                                        <td width="17%">
                                                            <span class="align-left">Rp.</span>
                                                            @php
                                                                $amount = $payroll->take_home_pay; // Ganti dengan nilai yang sesuai
                                                                $roundedAmount = floor($amount / 500) * 500;
                                                            @endphp
                                                            <span class="align-right">{{ rp2($roundedAmount) }}</span>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                            <div class="content-wrapper"
                                                style="display: flex; font-weight: 600; margin-top: -9px;">
                                                <div class="text-red"
                                                    style="border: 1px solid rgba(0, 0, 0, 0.884); width: 50%; padding: 5px 10px 5px 20px;">
                                                    *) Gaji Selama 1 bulan <br>
                                                    **)
                                                </div>
                                                <div class="ttd" style="width: 50%; text-align: center;">
                                                    <table>
                                                        <tr>
                                                            <td>Majalengka,
                                                                {{ \Carbon\Carbon::parse($payroll->updated_at)->translatedFormat('d F Y') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="/img/ttd-ibu.png" alt="TTD Ibu"
                                                                    width="60">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><u>apt. Lia Valini, S. Farm.</u></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wakil Direktur RS Livasya</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif
                            @if ($not)
                                <h5 class="text-muted text-center mt-5"><strong>tidak ada data</strong></h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('pages.pegawai.gaji-pegawai.partials.update-payroll')
    @endsection
    @section('plugin')
        <script src="/js/formplugins/select2/select2.bundle.js"></script>
        <script>
            $(document).ready(function() {
                $('#periode').select2({
                    placeholder: 'Pilih Periode',
                }).val('{{ $lastSearchPeriod }}').trigger('change');
            });
        </script>
    @endsection
