@extends('pages.simrs.erm.index')
@section('erm')
    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')

                <hr style="border-color: #868686; margin-bottom: 50px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian"></div>
                    <h2 class="font-weight-bold mb-3">RINGKASAN PASIEN RAWAT JALAN</h2>
                </header>

                <form action="javascript:void(0)" id="resume-medis-rajal-form">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                    <table class="table table-borderless">
                        <tbody>
                            {{-- Nama Pasien --}}
                            <tr>
                                <td style="width: 20%;"><label>Nama Pasien</label></td>
                                <td style="width: 3%;"><label>:</label></td>
                                <td style="width: 50%;">
                                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                        value="{{ $registration->patient->name }}" readonly>
                                </td>
                                <td style="width: 20%;">
                                    @php
                                        $alasanMasuk = $pengkajian?->alasan_masuk_rs ?? '';
                                    @endphp
                                    @foreach (['kunjungan_awal' => 'Kunjungan Awal', 'kontrol_lanjutan' => 'Kontrol Lanjutan', 'observasi' => 'Observasi', 'post_operasi' => 'Post Operasi'] as $value => $label)
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="custom-control-input" value="{{ $value }}"
                                                name="alasan_masuk_rs" id="{{ $value }}"
                                                {{ $alasanMasuk == $value ? 'checked' : '' }}>
                                            <label class="custom-control-label text-primary"
                                                for="{{ $value }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>

                            {{-- No. Rekam Medis --}}
                            <tr>
                                <td><label>No. Rekam Medis</label></td>
                                <td><label>:</label></td>
                                <td>
                                    <input type="text" class="form-control" id="medical_record_number"
                                        name="medical_record_number"
                                        value="{{ $registration->patient->medical_record_number }}" readonly>
                                </td>
                            </tr>

                            {{-- Tanggal Lahir --}}
                            <tr>
                                <td><label>Tanggal Lahir</label></td>
                                <td><label>:</label></td>
                                <td>
                                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir"
                                        placeholder="dd/mm/yyyy"
                                        value="{{ $pengkajian?->tgl_lahir ? \Carbon\Carbon::parse($pengkajian->tgl_lahir)->format('d/m/Y') : \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d/m/Y') }}"
                                        readonly>
                                </td>
                            </tr>

                            {{-- Jenis Kelamin --}}
                            <tr>
                                <td><label>Jenis Kelamin</label></td>
                                <td><label>:</label></td>
                                <td>
                                    <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                        value="{{ $registration->patient->gender }}" readonly>
                                </td>
                            </tr>

                            {{-- Tanggal Masuk RS --}}
                            <tr>
                                <td><label>Tanggal Masuk RS</label></td>
                                <td><label>:</label></td>
                                <td>
                                    <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk"
                                        value="{{ $pengkajian?->tgl_masuk ? \Carbon\Carbon::parse($pengkajian->tgl_masuk)->format('Y-m-d') : \Carbon\Carbon::parse($registration->registration_date)->format('Y-m-d') }}">
                                </td>
                            </tr>

                            {{-- Berat Lahir --}}
                            <tr>
                                <td><label>Berat Lahir</label></td>
                                <td><label>:</label></td>
                                <td>
                                    <input type="text" class="form-control numeric text-left" id="berat_lahir"
                                        name="berat_lahir" value="{{ $pengkajian?->berat_lahir ?? '' }}">
                                </td>
                            </tr>

                            {{-- Cara Keluar RS --}}
                            <tr>
                                <td><label>Cara Keluar RS</label></td>
                                <td><label>:</label></td>
                                <td colspan="2">
                                    @php
                                        $caraKeluar = $pengkajian?->cara_keluar ?? '';
                                    @endphp
                                    @foreach (['sembuh' => 'Sembuh', 'meninggal' => 'Meninggal', 'rawat' => 'Rawat', 'rujuk' => 'Rujuk', 'aps' => 'APS', 'kontrol' => 'Kontrol'] as $value => $label)
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="custom-control-input"
                                                    value="{{ $value }}" name="cara_keluar"
                                                    id="{{ $value }}" {{ $caraKeluar == $value ? 'checked' : '' }}>
                                                <label class="custom-control-label text-primary"
                                                    for="{{ $value }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>

                            {{-- Anamnesa --}}
                            <tr>
                                <td colspan="4">
                                    <div class="card mt-3">
                                        <div class="card-header bg-info text-white">Anamnesa</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="anamnesa" name="anamnesa" rows="11">
@if (empty($pengkajian))
@if ($registration->registration_type === 'rawat-jalan' && $assesment)
@php
    // [PERBAIKAN] Pastikan $assesment->tanda_vital ada sebelum diakses
    $tanda_vital_raw = $assesment->tanda_vital ?? '';
    if (is_array($tanda_vital_raw)) {
        $tanda_vital = $tanda_vital_raw;
    } else {
        $tanda_vital = json_decode($tanda_vital_raw, true) ?? [];
    }
@endphp
Keluhan Utama: {{ $keluhan_utama }}
Nadi (PR): {{ $tanda_vital['pr'] ?? '' }}
Respirasi (RR): {{ $tanda_vital['rr'] ?? '' }}
Tensi (BP): {{ $tanda_vital['bp'] ?? '' }}
Suhu (T): {{ $tanda_vital['temperatur'] ?? '' }}
Tinggi Badan: {{ $tanda_vital['height_badan'] ?? '' }}
Berat Badan: {{ $tanda_vital['weight_badan'] ?? '' }}
BMI: {{ $tanda_vital['bmi'] ?? '' }}{{ isset($tanda_vital['kat_bmi']) ? ' (' . $tanda_vital['kat_bmi'] . ')' : '' }}
SpO2: {{ $tanda_vital['spo2'] ?? '' }}
Lingkar Kepala:
Pemeriksaan Fisik: {{ $assesment->pemeriksaan_fisik ?? '' }}
Pemeriksaan Penunjang: {{ $assesment->pemeriksaan_penunjang ?? '' }}
@endif
@else
{{ $pengkajian->anamnesa ?? '' }}
@endif
</textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            {{-- Diagnosa Utama / Tambahan --}}
                            <tr>
                                <td colspan="4">
                                    <h5 class="bg-primary text-white p-2 rounded">KODE ICD-X</h5>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="diagnosa_utama" class="form-label">DIAGNOSA UTAMA
                                                            *</label>
                                                        <textarea class="form-control" id="diagnosa_utama" name="diagnosa_utama" rows="4" required>
@if (empty($pengkajian) && !empty($diagnosa_utama))
{{ $diagnosa_utama }}
@else
{{ $pengkajian?->diagnosa_utama ?? '' }}
@endif
</textarea>
                                                    </div>
                                                </td>
                                                <td style="width: 30%">
                                                    <div class="form-group">
                                                        <label for="cari_icd" class="form-label">Cari ICD 10</label>
                                                        {{-- UBAH BAGIAN INI --}}
                                                        <select class="form-control" id="cari_icd"
                                                            name="cari_icd"></select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="diagnosa_tambahan" class="form-label">DIAGNOSA
                                                            TAMBAHAN</label>
                                                        <textarea class="form-control" id="diagnosa_tambahan" name="diagnosa_tambahan" rows="4" required>
@if (empty($pengkajian) && !empty($diagnosa_tambahan))
{{ $diagnosa_tambahan }}
@else
{{ $pengkajian?->diagnosa_tambahan ?? '' }}
@endif
</textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="cari_icd_tambahan" class="form-label">Cari ICD
                                                            10</label>
                                                        {{-- UBAH BAGIAN INI --}}
                                                        <select class="form-control" id="cari_icd_tambahan"
                                                            name="cari_icd_tambahan"></select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            {{-- Tindakan Utama / Tambahan --}}
                            <tr>
                                <td colspan="4">
                                    <h5 class="bg-primary text-white p-2 rounded">KODE ICD 9 CM</h5>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="tindakan_utama" class="form-label">TINDAKAN
                                                            UTAMA</label>
                                                        <textarea class="form-control" id="tindakan_utama" name="tindakan_utama" rows="4">
@if (empty($pengkajian?->tindakan_utama) && !empty($terapi_tindakan))
{{ $terapi_tindakan }}
@if (!empty($tindakanMedis) && $tindakanMedis instanceof \Illuminate\Support\Collection && $tindakanMedis->isNotEmpty())
@foreach ($tindakanMedis as $tindakan)
- {{ $tindakan }}
@endforeach
@endif
@else
{{ $pengkajian?->tindakan_utama ?? '' }}
@endif
</textarea>
                                                    </div>
                                                </td>
                                                <td style="width: 25%">
                                                    <div class="form-group">
                                                        <label for="cari_icd2" class="form-label">Cari ICD 9</label>
                                                        {{-- UBAH BAGIAN INI --}}
                                                        <select class="form-control" id="cari_icd2"
                                                            name="cari_icd2"></select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="tindakan_tambahan" class="form-label">TINDAKAN
                                                            TAMBAHAN</label>
                                                        <textarea class="form-control" id="tindakan_tambahan" name="tindakan_tambahan" rows="4">{{ $pengkajian?->tindakan_tambahan ?? '' }}</textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="cari_icd2_tambahan" class="form-label">Cari ICD
                                                            9</label>
                                                        {{-- UBAH BAGIAN INI --}}
                                                        <select class="form-control" id="cari_icd2_tambahan"
                                                            name="cari_icd2_tambahan"></select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            {{-- Tanda Tangan Dokter --}}
                            <tr>
                                <td colspan="4">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td style="width: 35%"></td>
                                                <td style="width: 65%">
                                                    @include('pages.simrs.erm.partials.signature-field', [
                                                        'judul' => 'Dokter,',
                                                        'pic' => auth()->user()->employee->fullname,
                                                        'role' => 'dokter',
                                                        'prefix' => 'resume_medis_dokter',
                                                        'signature_model' => $pengkajian?->signature,
                                                    ])
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            {{-- Button --}}
                            <tr>
                                <td colspan="4">
                                    <div class="card-actionbar">
                                        <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                            <button type="button"
                                                class="btn btn-warning bsd-resume-medis-rajal waves-effect waves-light save-form d-flex align-items-center"
                                                data-status="0">
                                                <span class="mdi mdi-content-save mr-1"></span> Simpan (draft)
                                            </button>
                                            <button type="button"
                                                class="btn btn-primary bsf-resume-medis-rajal waves-effect waves-light save-form d-flex align-items-center"
                                                data-status="1">
                                                <span class="mdi mdi-content-save mr-1"></span> Simpan (final)
                                            </button>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.erm.partials.action-js.resume-medis-rajal')
    <script>
        $(document).ready(function() {
            @if ($assessmentNotFilled)
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengkajian Belum Diisi',
                    text: 'Anda harus mengisi pengkajian awal terlebih dahulu sebelum membuat resume medis.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false, // Mencegah user menutup alert dengan klik di luar
                    allowEscapeKey: false // Mencegah user menutup alert dengan tombol Esc
                }).then((result) => {
                    // Setelah user klik "OK", redirect ke halaman sebelumnya
                    if (result.isConfirmed) {
                        window.location.href =
                            '{{ url()->current() }}?registration={{ $registration->registration_number }}&menu=pengkajian_dokter_igd';
                    }
                });
            @endif

            $('body').addClass('layout-composed');

            // Fungsi helper untuk inisialisasi Select2 dengan AJAX
            function initializeIcdSelect2(selectElementId, targetTextareaId, apiUrl, placeholderText) {
                $(selectElementId).select2({
                    placeholder: placeholderText,
                    minimumInputLength: 3, // Mulai mencari setelah 3 karakter
                    ajax: {
                        url: apiUrl,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term
                            }; // Kirim query pencarian
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            }; // Tampilkan hasil dari API
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    // Event handler saat item dipilih
                    var data = e.params.data;
                    var currentText = $(targetTextareaId).val();
                    var newText = data.text;

                    // Tambahkan teks ke textarea
                    $(targetTextareaId).val(currentText ? currentText + '\n' + newText : newText);

                    // Kosongkan Select2
                    $(this).val(null).trigger('change');
                });
            }

            // Inisialisasi untuk ICD-10 (Diagnosa) menggunakan API Lokal
            const icd10Url = "{{ route('api.local.icd10.search') }}";
            initializeIcdSelect2('#cari_icd', '#diagnosa_utama', icd10Url, 'Cari Diagnosa ICD 10...');
            initializeIcdSelect2('#cari_icd_tambahan', '#diagnosa_tambahan', icd10Url, 'Cari Diagnosa ICD 10...');

            // Inisialisasi untuk ICD-9 (Tindakan) menggunakan API Lokal
            const icd9Url = "{{ route('api.local.icd9.search') }}";
            initializeIcdSelect2('#cari_icd2', '#tindakan_utama', icd9Url, 'Cari Tindakan ICD 9...');
            initializeIcdSelect2('#cari_icd2_tambahan', '#tindakan_tambahan', icd9Url, 'Cari Tindakan ICD 9...');
        });
    </script>
@endsection
