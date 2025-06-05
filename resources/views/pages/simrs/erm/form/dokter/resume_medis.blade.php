@extends('pages.simrs.erm.index')
@section('erm')
    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.poliklinik.partials.detail-pasien')

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
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" value="kunjungan_awal"
                                            name="alasan_masuk_rs" id="kunjungan-awal"
                                            {{ $pengkajian?->alasan_masuk_rs == 'kunjungan_awal' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-primary" for="kunjungan-awal">Kunjungan
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
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" value="kontrol_lanjutan"
                                            name="alasan_masuk_rs" id="kontrol-lanjutan"
                                            {{ $pengkajian?->alasan_masuk_rs == 'kontrol_lanjutan' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-primary" for="kontrol-lanjutan">Kontrol
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
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" value="observasi"
                                            name="alasan_masuk_rs" id="observasi"
                                            {{ $pengkajian?->alasan_masuk_rs == 'observasi' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-primary" for="observasi">Observasi</label>
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
                                    <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                        value="{{ $registration->patient->gender }}" readonly>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" value="post_operasi"
                                            name="alasan_masuk_rs" id="post-operasi"
                                            {{ $pengkajian?->alasan_masuk_rs == 'post_operasi' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-primary" for="post-operasi">Post
                                            Operasi</label>
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
                                    <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk"
                                        value="{{ \Carbon\Carbon::parse($registration->registration_date)->format('d-m-Y') }}">
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
                                    <div class="form-check form-check-inline mr-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="sembuh"
                                                name="cara_keluar" id="sembuh"
                                                {{ $pengkajian?->cara_keluar == 'sembuh' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary" for="sembuh">Sembuh</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline mr-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="meninggal"
                                                name="cara_keluar" id="meninggal"
                                                {{ $pengkajian?->cara_keluar == 'meninggal' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary"
                                                for="meninggal">Meninggal</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline mr-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="rawat"
                                                name="cara_keluar" id="rawat"
                                                {{ $pengkajian?->cara_keluar == 'rawat' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary" for="rawat">Rawat</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline mr-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="rujuk"
                                                name="cara_keluar" id="rujuk"
                                                {{ $pengkajian?->cara_keluar == 'rujuk' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary" for="rujuk">Rujuk</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline mr-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="aps"
                                                name="cara_keluar" id="aps"
                                                {{ $pengkajian?->cara_keluar == 'aps' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary" for="aps">APS</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline mr-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="kontrol"
                                                name="cara_keluar" id="kontrol"
                                                {{ $pengkajian?->cara_keluar == 'kontrol' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary"
                                                for="kontrol">Kontrol</label>
                                        </div>
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
                                                <td style="width: 35%">

                                                </td>
                                                <td style="width: 65%">
                                                    @include('pages.simrs.erm.partials.signature-field', [
                                                        'judul' => 'Dokter yang memeriksa,',
                                                        'pic' => $registration->doctor->employee->fullname,
                                                        'role' => 'perawat',
                                                    ])
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
                                        <div class="card-actionbar-row d-flex justify-content-between align-items-center">
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
    @endif
@endsection
@section('plugin-erm')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.erm.partials.action-js.resume-medis-rajal')
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            $('#doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });

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
@endsection
