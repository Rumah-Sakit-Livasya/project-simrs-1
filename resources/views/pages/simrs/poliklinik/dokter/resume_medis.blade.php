@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    @include('pages.simrs.poliklinik.partials.css-sidebar-custom')
    <style>
        main {
            overflow-x: hidden;
        }

        input[type="time"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .badge {
            cursor: pointer;
        }

        .badge.badge-orange {
            background-color: #ff5722;
            color: #ffffff;
        }

        .badge.badge-red {
            background-color: #f44336;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .img-baker {
                width: 45%;
                margin-bottom: 1rem;
            }
        }


        @media (min-width: 992px) {
            .nav-function-hidden:not(.nav-function-top) .page-sidebar:hover {
                left: -16.25rem;
                -webkit-transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
                transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
            }

            .nav.nav-tabs.action-erm {
                position: fixed;
                background: #ffffff;
                width: 100%;
                padding-top: 10px;
                padding-bottom: 10px;
                padding-left: 15px;
                z-index: 1;
            }

            .tab-content {
                margin-top: 55px;
            }
        }

        .slide-on-mobile {
            width: 20rem;
        }

        .text-decoration-underline {
            text-decoration: underline;
        }

        .text-secondary {
            font-size: 12px;
        }

        @media only screen and (max-width: 992px) {
            .slide-on-mobile-left {
                border-right: 1px solid rgba(0, 0, 0, 0.09);
                left: 0;
            }

            .slide-on-mobile {
                width: 17rem;
            }
        }

        #toggle-pasien i {
            color: #3366b9;
        }

        #js-slide-left {
            border-right: 1px solid rgba(0, 0, 0, 0.3);
            background: white;
        }

        #js-slide-left.hide {
            display: none;
        }

        .gradient-text {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .spaced-text {
            letter-spacing: 0.4em;
            font-weight: bold;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .logo-dashboard-simrs {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- notice the utilities added to the wrapper below -->
        <div class="d-flex flex-grow-1 p-0 shadow-1 layout-composed">
            <!-- left slider panel : must have unique ID-->
            @include('pages.simrs.poliklinik.partials.filter-poli')

            <!-- middle content area -->
            <div class="d-flex flex-column flex-grow-1 bg-white">

                @include('pages.simrs.poliklinik.partials.menu-erm')

                {{-- content start --}}
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                        @include('pages.simrs.erm.partials.detail-pasien')

                        <hr style="border-color: #868686; margin-bottom: 50px;">
                        <header class="text-primary text-center font-weight-bold mb-4">
                            <div id="alert-pengkajian"></div>
                            <h2 class="font-weight-bold MB-3">RINGKASAN PASIEN RAWAT JALAN</h4>
                        </header>
                        <form action="javascript:void(0)" id="resume-medis-rajal-form">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td style="width: 20%;">
                                            <label>Nama Pasien</label>
                                        </td>
                                        <td style="width: 3%;">
                                            <label>:</label>
                                        </td>
                                        <td style="width: 50%;">
                                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                                value="{{ $registration->patient->name }}" readonly>
                                        </td>
                                        <td style="width: 20%;">
                                            <div class="form-check">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="kunjungan_awal" name="alasan_masuk_rs" value="kunjungan_awal">
                                                <label class="form-check-label ml-2" for="kunjungan_awal">Kunjungan
                                                    Awal</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>No. Rekam Medis</label>
                                        </td>
                                        <td>
                                            <label>:</label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="medical_record_number"
                                                name="medical_record_number"
                                                value="{{ $registration->patient->medical_record_number }}" readonly>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="kontrol_lanjutan" name="alasan_masuk_rs" value="kontrol_lanjutan">
                                                <label class="form-check-label ml-2" for="kontrol_lanjutan">Kontrol
                                                    Lanjutan</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Tanggal Lahir</label>
                                        </td>
                                        <td>
                                            <label>:</label>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir"
                                                    placeholder="dd/mm/yyyy"
                                                    value="{{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d/m/Y') }}"
                                                    readonly>

                                                {{-- <div class="input-group-append">
                                                <span class="input-group-text fs-xl">
                                                    <i class="fal fa-calendar-check"></i>
                                                </span>
                                            </div> --}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="observasi" name="alasan_masuk_rs" value="observasi">
                                                <label class="form-check-label ml-2" for="observasi">Observasi</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Jenis Kelamin</label>
                                        </td>
                                        <td>
                                            <label>:</label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="jenis_kelamin"
                                                name="jenis_kelamin" value="{{ $registration->patient->gender }}" readonly>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="post_operasi" name="alasan_masuk_rs" value="post_operasi">
                                                <label class="form-check-label ml-2" for="post_operasi">Post Operasi</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Tanggal Masuk RS</label>
                                        </td>
                                        <td>
                                            <label>:</label>
                                        </td>
                                        <td>
                                            <input type="datetime-local" class="form-control" id="tgl_masuk"
                                                name="tgl_masuk" placeholder="dd/mm/yyyy"
                                                value="{{ \Carbon\Carbon::parse(now()->setTimeZone('Asia/Jakarta'))->format('d/m/Y H:i') }}">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="form-group">
                                                    <label class="form-label">Berat Lahir</label>
                                                    <div class="input-group">
                                                        <input type="text"
                                                            class="form-control numeric text-left border-top-0 border-right-0 border-left-0 border-bottom"
                                                            id="berat_lahir" name="berat_lahir">
                                                        <span class="input-group-addon grey-text text-small">gram</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Cara Keluar RS</label>
                                        </td>
                                        <td>
                                            <label>:</label>
                                        </td>
                                        <td colspan="2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="sembuh" name="cara_keluar" value="sembuh">
                                                <label class="form-check-label" for="sembuh">Sembuh</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="meninggal" name="cara_keluar" value="meninggal">
                                                <label class="form-check-label" for="meninggal">Meninggal</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="rawat" name="cara_keluar" value="rawat">
                                                <label class="form-check-label" for="rawat">Rawat</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="rujuk" name="cara_keluar" value="rujuk">
                                                <label class="form-check-label" for="rujuk">Rujuk</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="aps" name="cara_keluar" value="aps">
                                                <label class="form-check-label" for="aps">APS</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input custom-checkbox" type="radio"
                                                    id="kontrol" name="cara_keluar" value="kontrol">
                                                <label class="form-check-label" for="kontrol">Kontrol</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div class="card mt-3">
                                                <div class="card-header bg-info text-white">
                                                    Anamnesa
                                                </div>
                                                <div class="card-body p-0">
                                                    <textarea class="form-control border-0 rounded-0" id="anamnesa" name="anamnesa" rows="4">{{ $Anamnesa ?? 'Masukkan anamnesa di sini...' }}
                                                </textarea>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <h5 class="bg-primary text-white p-2 rounded">KODE ICD-X</h5>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="diagnosa_utama" class="form-label">DIAGNOSA
                                                                    UTAMA
                                                                    *</label>
                                                                <textarea class="form-control" id="diagnosa_utama" name="diagnosa_utama" rows="4" required>Diagnosa Kerja    : P3A1POST SC</textarea>
                                                            </div>
                                                        </td>
                                                        <td style="width: 25%">
                                                            <div class="form-group">
                                                                <label for="cari_icd" class="form-label">Cari ICD
                                                                    10</label>
                                                                <input type="text" name="cari_icd" id="cari_icd"
                                                                    class="form-control ui-autocomplete-input"
                                                                    placeholder="Cari ICD 10" autocomplete="off">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="diagnosa_tambahan" class="form-label">DIAGNOSA
                                                                    TAMBAHAN</label>
                                                                <textarea class="form-control" id="diagnosa_tambahan" name="diagnosa_tambahan" rows="4"></textarea>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="cari_icd_tambahan" class="form-label">Cari ICD
                                                                    10</label>
                                                                <input type="text" name="cari_icd_tambahan"
                                                                    id="cari_icd_tambahan"
                                                                    class="form-control ui-autocomplete-input"
                                                                    placeholder="Cari ICD 10" autocomplete="off">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <h5 class="bg-primary text-white p-2 rounded">KODE ICD 9 CM</h5>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="tindakan_utama" class="form-label">TINDAKAN
                                                                    UTAMA</label>
                                                                <textarea class="form-control" id="tindakan_utama" name="tindakan_utama" rows="4">Terapi / Tindakan : K AFF HC GV P. LUKA</textarea>
                                                            </div>
                                                        </td>
                                                        <td style="width: 25%">
                                                            <div class="form-group">
                                                                <label for="cari_icd2" class="form-label">Cari ICD
                                                                    9</label>
                                                                <input type="text" name="cari_icd2" id="cari_icd2"
                                                                    class="form-control ui-autocomplete-input"
                                                                    placeholder="Cari ICD 9" autocomplete="off">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="tindakan_tambahan" class="form-label">TINDAKAN
                                                                    TAMBAHAN</label>
                                                                <textarea class="form-control" id="tindakan_tambahan" name="tindakan_tambahan" rows="4"></textarea>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="cari_icd2_tambahan" class="form-label">Cari
                                                                    ICD
                                                                    9</label>
                                                                <input type="text" name="cari_icd2_tambahan"
                                                                    id="cari_icd2_tambahan"
                                                                    class="form-control ui-autocomplete-input"
                                                                    placeholder="Cari ICD 9" autocomplete="off">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 65%">

                                                        </td>
                                                        <td style="width: 35%">
                                                            <div class="text-center">
                                                                DPJP/Dokter Yang Memeriksa
                                                            </div>
                                                            <div class="text-center">
                                                                <a class="btn btn-primary btn-ttd-resume-medis btn-sm text-white my-2"
                                                                    data-id="{{ auth()->user()->id }}">
                                                                    Tanda Tangan
                                                                </a>
                                                                <img id="signature-display" src=""
                                                                    alt="Signature Image"
                                                                    style="display:none; max-width:80%;"><br>
                                                                <span>{{ auth()->user()->employee->fullname }}</span>
                                                            </div>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <input type="hidden" name="is_ttd">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div class="card-actionbar">
                                                <div
                                                    class="card-actionbar-row d-flex justify-content-between align-items-center">
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                                        data-dismiss="modal" data-status="0">
                                                        <span class="mdi mdi-printer"></span> Cetak
                                                    </button>
                                                    <div style="width: 40%" class="d-flex justify-content-between"
                                                        id="rmj-button-wrapper">
                                                        <button type="button"
                                                            class="btn bsd-resume-medis-rajal btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                                            data-status="0">
                                                            <span class="mdi mdi-content-save"></span> Simpan (draft)
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center bsf-resume-medis-rajal"
                                                            data-status="1">
                                                            <span class="mdi mdi-content-save"></span> Simpan (final)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.poliklinik.partials.js-filter')
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            // $('#doctor_id').select2({
            //     placeholder: 'Pilih Dokter',
            // });

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });
        });
    </script>
    @include('pages.simrs.poliklinik.partials.action-js.resume-medis-rajal')
@endsection
