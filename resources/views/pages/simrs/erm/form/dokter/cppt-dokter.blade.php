@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <i class="mdi mdi-clipboard-text-outline mr-2"></i>CPPT DOKTER
                                </h4>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light btn-sm" id="btnAdd"
                                        data-toggle="collapse" data-parent="#accordion_soap" data-target="#add_soap"
                                        aria-expanded="true">
                                        <i class="mdi mdi-plus-circle"></i> Tambah CPPT
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" data-toggle="collapse"
                                        data-parent="#accordion_soap" data-target="#view-fitler-soap" aria-expanded="false">
                                        <i class="mdi mdi-filter"></i> Filter
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="accordion_soap" class="accordion">
                                    <form action="javascript:void(0)" class="w-100" data-tipe-cppt="dokter"
                                        data-tipe-cppt="rawat-jalan" id="cppt-dokter-rajal-form" autocomplete="off">
                                        @csrf
                                        @method('POST')

                                        <div id="add_soap" class="panel-content collapse in" aria-expanded="true">
                                            <!-- Mulai Form Input CPPT -->
                                            <input type="hidden" name="registration_id" id="regId"
                                                value="{{ $registration->id }}" />
                                            <input type="hidden" name="registration_number" id="regNum"
                                                value="{{ $registration->registration_number }}" />
                                            <input type="hidden" name="registration_type" id="regType"
                                                value="{{ $registration->registration_type }}" />
                                            <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                                value="{{ $registration->patient->medical_record_number }}" />

                                            <!-- Informasi Dokter -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="card-title mb-0">
                                                                <i class="mdi mdi-doctor mr-2"></i>Informasi Dokter
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="pid_dokter" class="form-label fw-bold">
                                                                            <i class="mdi mdi-account-star mr-1"></i>Dokter
                                                                        </label>
                                                                        <select
                                                                            class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                                            name="doctor_id" id="cppt_doctor_id">
                                                                            <option value=""></option>
                                                                            @foreach ($jadwal_dokter as $jadwal)
                                                                                <option value="{{ $jadwal->doctor_id }}"
                                                                                    @if ($registration->doctor_id == $jadwal->doctor_id) selected @endif>
                                                                                    {{ $jadwal->doctor->employee->fullname }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="konsulkan_ke"
                                                                            class="form-label fw-bold">
                                                                            <i
                                                                                class="mdi mdi-account-question mr-1"></i>Konsulkan
                                                                            Ke
                                                                        </label>
                                                                        <select
                                                                            class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                                            name="konsulkan_ke" id="konsulkan_ke">
                                                                            <option value=""></option>
                                                                            @foreach ($jadwal_dokter as $jadwal)
                                                                                <option value="{{ $jadwal->doctor_id }}">
                                                                                    {{ $jadwal->doctor->employee->fullname }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SOAP - Subjective and Objective -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="card-title mb-0">
                                                                <i class="mdi mdi-clipboard-text mr-2"></i>SOAP Assessment
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Subjective -->
                                                                <div class="col-md-6">
                                                                    <div class="card border-primary">
                                                                        <div class="card-header bg-primary">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-account-voice mr-2"></i>Subjective
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body p-0">
                                                                            <textarea class="form-control border-0 rounded-0" id="subjective" name="subjective" rows="10"
                                                                                placeholder="Keluhan Utama">
@if ($registration->registration_type === 'rawat-jalan' && $assesment)
@php
    $anamnesis = $assesment->anamnesis ?? '';
    if (is_array($anamnesis)) {
        $anamnesisArr = $anamnesis;
    } else {
        $anamnesisArr = json_decode($anamnesis, true) ?? [];
    }
@endphp
Alergi obat : {{ $anamnesisArr['alergi']['status'] ?? '-' }}
Reaksi alergi obat : {{ $anamnesisArr['alergi']['keterangan'] ?? '-' }}
Keluhan Utama : {{ $anamnesisArr['keluhan_utama'] ?? '-' }}
Riwayat Penyakit Sekarang : {{ $anamnesisArr['riwayat_penyakit_sekarang'] ?? '-' }}
Riwayat Penyakit Dahulu : {{ $anamnesisArr['riwayat_penyakit_dahulu'] ?? '-' }}
Riwayat Penyakit Keluarga : {{ $anamnesisArr['riwayat_penyakit_keluarga'] ?? '-' }}
Alergi makan :
Reaksi alergi makan :
Alergi lainya :
Reaksi alergi lainya :
@else
Alergi obat : {{ $assesment?->awal_riwayat_alergi_obat === 1 ? 'Ya' : 'Tidak' }}
Reaksi alergi obat : {{ $assesment?->awal_riwayat_alergi_obat_lain ?? '' }}
Keluhan Utama : {{ $assesment?->awal_keluhan ?? '' }}
Riwayat Penyakit Sekarang : {{ $assesment?->awal_riwayat_penyakit_sekarang ?? '' }}
Riwayat Penyakit Dahulu : {{ $assesment?->awal_riwayat_penyakit_dahulu ?? '' }}
Riwayat Penyakit Keluarga : {{ $assesment?->awal_riwayat_penyakit_keluarga ?? '' }}
Alergi makan :
Reaksi alergi makan :
Alergi lainya :
Reaksi alergi lainya :
@endif
</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Objective -->
                                                                <div class="col-md-6">
                                                                    <div class="card border-success">
                                                                        <div class="card-header bg-success">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-stethoscope mr-2"></i>Objective
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body p-0">
                                                                            <textarea class="form-control border-0 rounded-0" id="objective" name="objective" rows="10">
@if ($registration->registration_type === 'rawat-jalan' && $assesment)
@php
    $tanda_vital_raw = $assesment->tanda_vital ?? '';
    if (is_array($tanda_vital_raw)) {
        $tanda_vital = $tanda_vital_raw;
    } else {
        $tanda_vital = json_decode($tanda_vital_raw, true) ?? [];
    }
@endphp
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
@else
{{ 'Nadi (PR): ' .
    ($assesment?->pr ?? '') .
    "\n" .
    'Respirasi (RR): ' .
    ($assesment?->rr ?? '') .
    "\n" .
    'Tensi (BP): ' .
    ($assesment?->bp ?? '') .
    "\n" .
    'Suhu (T): ' .
    ($assesment?->temperatur ?? '') .
    "\n" .
    'Tinggi Badan: ' .
    ($assesment?->body_height ?? '') .
    "\n" .
    'Berat Badan: ' .
    ($assesment?->body_weight ?? '') .
    "\n" .
    'BMI: ' .
    ($assesment?->bmi ?? '') .
    ($assesment?->kat_bmi ? ' (' . $assesment->kat_bmi . ')' : '') .
    "\n" .
    'SpO2: ' .
    ($assesment?->sp02 ?? '') .
    "\n" .
    'Lingkar Kepala: ' .
    ($assesment?->lingkar_kepala ?? '') .
    "\n" .
    'Pemeriksaan Fisik: ' .
    ($assesment?->awal_pemeriksaan_fisik ?? '') .
    "\n" .
    'Pemeriksaan Penunjang: ' .
    ($assesment?->awal_pemeriksaan_penunjang ?? '') }}
@endif
</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="card-header bg-light">
                                                            <h6 class="card-title text-white mb-0">
                                                                <i class="mdi mdi-brain mr-2"></i>Assessment & Planning
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Assessment -->
                                                                <div class="col-md-6">
                                                                    <div class="card border-danger">
                                                                        <div
                                                                            class="card-header bg-danger d-flex justify-content-between align-items-center">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-file-chart mr-2"></i>Assessment
                                                                            </h6>
                                                                            <span id="diag_perawat"
                                                                                class="badge badge-warning pointer">Diagnosa
                                                                                Keperawatan</span>
                                                                        </div>
                                                                        <div class="card-body p-0">
                                                                            <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="4"
                                                                                placeholder="Diagnosa Keperawatan">Diagnosa Kerja: {{ $assesment?->awal_diagnosa_kerja ?? '' }}
Diagnosa Banding: {{ $assesment?->awal_diagnosa_banding ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Planning -->
                                                                <div class="col-md-6">
                                                                    <div class="card border-warning">
                                                                        <div
                                                                            class="card-header bg-warning d-flex justify-content-between align-items-center">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-calendar-check mr-2"></i>Planning
                                                                            </h6>
                                                                            <span id="intervensi_perawat"
                                                                                class="badge badge-dark pointer">Intervensi</span>
                                                                        </div>
                                                                        <div class="card-body p-0">
                                                                            <textarea class="form-control border-0 rounded-0" id="planning" name="planning" rows="4"
                                                                                placeholder="Rencana Tindak Lanjut">Terapi / Tindakan : {{ $assesment?->awal_terapi_tindakan ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Evaluation and Instructions -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="card-title text-white mb-0">
                                                                <i class="mdi mdi-note-edit-outline mr-2"></i>Evaluation &
                                                                Instructions
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="card border-info">
                                                                        <div class="card-header bg-info">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-information-outline mr-2"></i>Instruksi
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body p-0">
                                                                            <textarea class="form-control border-0 rounded-0" id="instruksi" name="instruksi" rows="4"
                                                                                placeholder="Evaluasi"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card border-info">
                                                                        <div class="card-header bg-info">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-prescription mr-2"></i>Resep
                                                                                Manual
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body p-0">
                                                                            <textarea class="form-control border-0 rounded-0" id="resep_manual" name="resep_manual" rows="4"
                                                                                placeholder="Resep Manual"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Prescription Management -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="card-title mb-0">
                                                                <i class="mdi mdi-pill mr-2"></i>Resep Elektronik
                                                                <i id="loading-spinner-head"
                                                                    class="loading fas fa-spinner fa-spin ml-2"></i>
                                                                <span
                                                                    class="loading-message loading text-warning ml-1">Loading...</span>
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <style>
                                                                #loading-page {
                                                                    position: absolute;
                                                                    min-height: 100%;
                                                                    min-width: 100%;
                                                                    background: rgba(0, 0, 0, 0.75);
                                                                    border-radius: 0 0 4px 4px;
                                                                    z-index: 1000;
                                                                }
                                                            </style>
                                                            <div class="loading" id="loading-page"></div>

                                                            <!-- Drug Selection Section -->
                                                            <div class="row mb-3">
                                                                <div class="col-12">
                                                                    <div class="card border-secondary">
                                                                        <div class="card-header bg-secondary">
                                                                            <h6 class="card-title text-white mb-0">
                                                                                <i
                                                                                    class="mdi mdi-package-variant mr-2"></i>Pilih
                                                                                Obat
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @if (!isset($default_apotek))
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="form-label fw-bold">
                                                                                                <i
                                                                                                    class="mdi mdi-store mr-1"></i>Gudang
                                                                                            </label>
                                                                                            <select
                                                                                                class="select2 form-control @error('gudang_id') is-invalid @enderror"
                                                                                                name="gudang_id"
                                                                                                id="cppt_gudang_id">
                                                                                                <option value=""
                                                                                                    disabled selected
                                                                                                    hidden>
                                                                                                    Pilih Gudang
                                                                                                </option>
                                                                                                @foreach ($gudangs as $gudang)
                                                                                                    <option
                                                                                                        value="{{ $gudang->id }}">
                                                                                                        {{ $gudang->nama }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                    @else
                                                                                        <div class="col-12">
                                                                                            <input type="hidden"
                                                                                                name="gudang_id"
                                                                                                value="{{ $default_apotek->id }}">
                                                                                @endif
                                                                                <div class="form-group">
                                                                                    <label class="form-label fw-bold">
                                                                                        <i
                                                                                            class="mdi mdi-medical-bag mr-1"></i>Nama
                                                                                        Obat
                                                                                    </label>
                                                                                    <select class="select2 form-control"
                                                                                        name="barang_id"
                                                                                        id="cppt_barang_id">
                                                                                        <option value="" disabled
                                                                                            selected hidden>Pilih Obat
                                                                                        </option>
                                                                                        @if (isset($default_apotek))
                                                                                            @foreach ($barangs as $barang)
                                                                                                @php
                                                                                                    $items = $barang->stored_items->where(
                                                                                                        'gudang_id',
                                                                                                        $default_apotek->id,
                                                                                                    );
                                                                                                    $qty = $items->sum(
                                                                                                        'qty',
                                                                                                    );
                                                                                                    $barang->qty = $qty;
                                                                                                @endphp
                                                                                                @if ($qty > 0)
                                                                                                    <option
                                                                                                        value="{{ $barang->id }}"
                                                                                                        class="obat"
                                                                                                        data-qty="{{ $qty }}"
                                                                                                        data-item="{{ json_encode($barang) }}">
                                                                                                        {{ $barang->nama }}
                                                                                                        (Stock:
                                                                                                        {{ $qty }})
                                                                                                    </option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Prescription Table -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card border-success">
                                                    <div class="card-header bg-success">
                                                        <h6 class="card-title text-white mb-0">
                                                            <i class="mdi mdi-table mr-2"></i>Daftar Resep Elektronik
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover mb-0">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th class="text-center" style="width: 3%;">
                                                                            <i class="mdi mdi-settings"></i>
                                                                        </th>
                                                                        <th style="width: 25%;">
                                                                            <i class="mdi mdi-medical-bag mr-1"></i>Nama
                                                                            Obat
                                                                        </th>
                                                                        <th class="text-center" style="width: 8%;">
                                                                            <i
                                                                                class="mdi mdi-package-variant-closed mr-1"></i>UOM
                                                                        </th>
                                                                        <th class="text-center" style="width: 7%;">
                                                                            <i class="mdi mdi-counter mr-1"></i>Stok
                                                                        </th>
                                                                        <th class="text-center" style="width: 8%;">
                                                                            <i class="mdi mdi-numeric mr-1"></i>Qty
                                                                        </th>
                                                                        <th class="text-right" style="width: 10%;">
                                                                            <i class="mdi mdi-cash mr-1"></i>Harga
                                                                        </th>
                                                                        <th style="width: 15%;">
                                                                            <i class="mdi mdi-script-text mr-1"></i>Signa
                                                                        </th>
                                                                        <th style="width: 15%;">
                                                                            <i
                                                                                class="mdi mdi-information-outline mr-1"></i>Instruksi
                                                                        </th>
                                                                        <th class="text-right" style="width: 12%;">
                                                                            <i class="mdi mdi-calculator mr-1"></i>Subtotal
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="table_re">
                                                                    <!-- Prescription items will be populated here by JavaScript -->
                                                                </tbody>
                                                                <tfoot class="table-dark">
                                                                    <tr>
                                                                        <td colspan="8"
                                                                            class="text-right font-weight-bold">
                                                                            <strong>Grand Total:</strong>
                                                                        </td>
                                                                        <td class="text-right font-weight-bold">
                                                                            <span id="grand_total"
                                                                                class="numeric text-success">0</span>
                                                                            <input type="hidden" name="total_harga_obat"
                                                                                id="total_harga_obat" value="0"
                                                                                readonly="">
                                                                            <input type="hidden" name="total_bpjs"
                                                                                id="total_bpjs" value="0"
                                                                                readonly="">
                                                                            <input type="hidden" name="is_bpjs"
                                                                                id="is_bpjs" value="f"
                                                                                readonly="">
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Signature and Submission -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="card-title mb-0">
                                                            <i class="mdi mdi-signature mr-2"></i>Tanda Tangan & Simpan
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @include(
                                                            'pages.simrs.erm.partials.signature-field',
                                                            [
                                                                'judul' => 'Dokter,',
                                                                'pic' => auth()->user()->employee->fullname,
                                                                'role' => 'dokter',
                                                                'prefix' => 'cppt_dokter', // Berikan prefix unik
                                                                'signature_model' => $pengkajian?->signature, // Kirim model data tanda tangan yang relevan
                                                            ]
                                                        )

                                                        <!-- Tombol Submit -->
                                                        <div class="row mt-4">
                                                            <div class="col-12 text-right">
                                                                <button type="submit" class="btn btn-success btn-lg"
                                                                    id="submit-cppt-dokter">
                                                                    <i class="mdi mdi-content-save mr-2"></i>Simpan CPPT
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- Filter Section -->
                <div id="view-fitler-soap" class="panel-content collapse" aria-expanded="false">
                    <div class="card-body no-padding">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="s_tgl_1" class="col-sm-4 control-label">Tgl.
                                        CPPT</label>
                                    <div class="input-daterange input-group col-sm-8" id="demo-date-range">
                                        <input name="sdate" type="text" class="datepicker form-control"
                                            id="sdate" readonly />
                                        <span class="input-group-addon">s/d</span>
                                        <input name="edate" type="text" class="datepicker form-control"
                                            id="edate" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dept" class="col-sm-4 control-label">Status
                                        Rawat</label>
                                    <div class="col-sm-8">
                                        <select class="form-control sel2" id="dept" name="dept">
                                            <option value=""></option>
                                            <option value="ri">Rawat Inap</option>
                                            <option value="rj">Rawat Jalan</option>
                                            <option value="igd">IGD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="role" class="col-sm-4 control-label">Tipe
                                        CPPT</label>
                                    <div class="col-sm-8">
                                        <select class="form-control sel2" id="role" name="role">
                                            <option value=""></option>
                                            <option value="dokter">Dokter</option>
                                            <option value="perawat">Perawat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Filter Section -->
            </div>
        </div>
        </form>

        {{-- <div class="col-md-6">
            <div class="card-body">
                <div class="table-responsive no-margin">
                    <table id="cppt-dokter" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:25%;">Tanggal</th>
                                <th style="width: 70%;">Catatan</th>
                                <th style="width: 6%;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="list_soap_dokter">
                            <tr>
                                <td class="text-center">
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                    </table>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <!-- Additional rows here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <!-- Pagination will be handled by DataTables -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div><!--end .table-responsive -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-body">
                <div class="table-responsive no-margin">
                    <table id="cppt-perawat" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:25%;">Tanggal</th>
                                <th style="width: 70%;">Catatan</th>
                                <th style="width: 6%;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="list_soap_perawat">
                            <tr>
                                <td class="text-center">
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                    </table>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <!-- Additional rows here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <!-- Pagination will be handled by DataTables -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div><!--end .table-responsive -->
            </div>
        </div> --}}

        <div class="col-md-12">
            <hr style="border-color: #868686; margin-bottom: 30px;">
            {{-- Container utama untuk semua data CPPT --}}
            <div id="cppt-container" class="cppt-container">
                <!-- Konten (Header tanggal dan kolom) akan di-generate oleh JavaScript -->
            </div>
        </div>

        </div>
        </div>
        </div>
    @endif
    @if (!empty($showSwal) && $showSwal)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Isi asesmen atau pengkajian terlebih dahulu!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = @json(
                                $registration->registration_type == 'rawat-inap'
                                    ? '?menu=pengkajian_dokter&registration=' . $registration->registration_number
                                    : '?menu=asesmen_awal_dokter&registration=' . $registration->registration_number);
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endsection
@section('plugin-erm')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="{{ asset('js/simrs/erm/form/dokter/cppt.js') }}?time={{ now() }}"></script>
    <script src="{{ asset('js/simrs/erm/form/dokter/cppt-dokter-form.js') }}?time={{ now() }}"></script>
    <script>
        /**
         * Custom matcher for the Select2 drug dropdown to allow searching
         * by drug name or active substance (zat aktif).
         * @param {import("select2").SearchOptions} params
         * @param {import("select2").OptGroupData | import("select2").OptionData} data
         * @returns {import("select2").OptGroupData | import("select2").OptionData | null}
         */
        function obatMatcher(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            const zatCheck = $("#zat_aktif");
            console.log(zatCheck.is(':checked'));

            const term = params.term.toLowerCase();
            const text = data.text.toLowerCase();
            const $el = $(data.element);
            const zat = $el.data('zat')?.toString().toLowerCase();

            if (zatCheck.is(':checked')) {
                if (zat && zat.includes(term)) {
                    return data;
                }
            } else {
                if (text.includes(term)) {
                    return data;
                }
            }

            return null;
        }

        $(document).ready(function() {
            function submitFormCPPT(actionType) {
                const form = $('#cppt-dokter-rajal-form');
                const registrationNumber = "{{ $registration->registration_number }}";

                const url =
                    "{{ route('cppt.store', ['type' => 'rawat-jalan', 'registration_number' => '__registration_number__']) }}"
                    .replace('__registration_number__', registrationNumber);

                // Now you can use `url` in your form submission or AJAX request

                let formData = form.serialize(); // Ambil data dari form

                // Tambahkan tipe aksi (draft atau final) ke data form
                formData += '&action_type=' + actionType;

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        if (actionType === 'draft') {
                            showSuccessAlert('Data berhasil disimpan sebagai draft!');
                        } else {
                            showSuccessAlert('Data berhasil disimpan sebagai final!');
                        }
                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(response) {
                        // Tangani error
                        var errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            showErrorAlert(value[0]);
                        });
                    }
                });
            }

            // Saat tombol Save Final diklik
            $('#bsSOAP').on('click', function() {
                submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
            });


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

            $("#cppt_barang_id").select2({
                matcher: obatMatcher,
                placeholder: 'Pilih Obat'
            })

            $('#cppt_gudang_id').select2({
                placeholder: 'Pilih Gudang',
            });

            $('#cppt_doctor_id').select2({
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
    @include('pages.simrs.erm.partials.action-js.cppt')
@endsection
