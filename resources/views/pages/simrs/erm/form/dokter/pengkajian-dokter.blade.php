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
                    <h2 class="font-weight-bold">PENGKAJIAN DOKTER</h4>
                </header>
                <form action="javascript:void(0)" id="pengkajian-dokter-rajal-form">
                    @csrf
                    @method('POST')
                    <header class="text-warning mb-4">
                        <h4 class="font-weight-bold">TANDA TANDA VITAL</h4>
                    </header>
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label class="text-primary" for="pr">Nadi (PR)</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="pr" type="text" name="pr" class="form-control"
                                            value="{{ $pengkajian?->pr ?? ($triage?->pr ?? $data?->pr) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="rr" class="text-primary">Respirasi (RR)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="rr" name="rr" type="text"
                                        value="{{ $pengkajian?->rr ?? ($triage?->rr ?? $data?->rr) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="body_height">Tinggi Badan (cm)</label>
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi-pd" id="body_height" name="body_height"
                                        type="text"
                                        value="{{ $pengkajian?->body_height ?? ($triage?->body_height ?? $data?->body_height) }}">">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="body_weight">Berat Badan (kg)</label>
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi-pd" id="body_weight" name="body_weight"
                                        type="text"
                                        value="{{ $pengkajian?->body_weight ?? ($triage?->body_weight ?? $data?->body_weight) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="bp">Tensi (BP)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="bp" name="bp" type="text"
                                        value="{{ $pengkajian?->bp ?? ($triage?->bp ?? $data?->bp) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="temperatur">Suhu (T)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="temperatur" name="temperatur" type="text"
                                        value="{{ $pengkajian?->temperatur ?? ($triage?->temperatur ?? $data?->temperatur) }}">

                                    <div class="input-group-append">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="bmi">Index Massa Tubuh</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="bmi" name="bmi" readonly
                                        type="text" value="{{ $pengkajian?->bmi ?? ($triage?->bmi ?? $data?->bmi) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg/m²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="kat_bmi">Katerogi IMT</label>
                                <input class="form-control" id="kat_bmi" name="kat_bmi" readonly type="text"
                                    value="{{ $pengkajian?->kat_bmi ?? ($triage?->kat_bmi ?? $data?->kat_bmi) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="sp02">SP 02</label>
                            <div class="input-group">
                                <input class="form-control" id="sp02" name="sp02" type="text"
                                    value="{{ $pengkajian?->sp02 ?? ($triage?->sp02 ?? $data?->sp02) }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="diagnosa_keperawatan">Diagnosa Keperawatan</label>
                                <select name="diagnosa_keperawatan" id="diagnosa_keperawatan"
                                    class="form-control select2">
                                    @php
                                        $selectedDiagnosa =
                                            $pengkajian?->diagnosa_keperawatan ?? ($data?->diagnosa_keperawatan ?? '');
                                    @endphp
                                    <option value="-" {{ $selectedDiagnosa === '-' ? 'selected' : '' }}>-</option>
                                    <option value="Gangguan rasa nyaman"
                                        {{ $selectedDiagnosa === 'Gangguan rasa nyaman' ? 'selected' : '' }}>Gangguan rasa
                                        nyaman</option>
                                    <option value="Nyeri" {{ $selectedDiagnosa === 'Nyeri' ? 'selected' : '' }}>Nyeri
                                    </option>
                                    <option value="Pola Nafas tidak efektif"
                                        {{ $selectedDiagnosa === 'Pola Nafas tidak efektif' ? 'selected' : '' }}>Pola Nafas
                                        tidak efektif</option>
                                    <option value="Bersihan jalan nafas tidak efektif"
                                        {{ $selectedDiagnosa === 'Bersihan jalan nafas tidak efektif' ? 'selected' : '' }}>
                                        Bersihan jalan nafas tidak efektif</option>
                                    <option value="Nyeri Akut" {{ $selectedDiagnosa === 'Nyeri Akut' ? 'selected' : '' }}>
                                        Nyeri Akut</option>
                                    <option value="Nyeri Kronis"
                                        {{ $selectedDiagnosa === 'Nyeri Kronis' ? 'selected' : '' }}>Nyeri Kronis</option>
                                    <option value="Resiko Infeksi"
                                        {{ $selectedDiagnosa === 'Resiko Infeksi' ? 'selected' : '' }}>Resiko Infeksi
                                    </option>
                                    <option value="Harga diri Rendah"
                                        {{ $selectedDiagnosa === 'Harga diri Rendah' ? 'selected' : '' }}>Harga diri Rendah
                                    </option>
                                    <option value="Resiko Perilaku Kekerasan"
                                        {{ $selectedDiagnosa === 'Resiko Perilaku Kekerasan' ? 'selected' : '' }}>Resiko
                                        Perilaku Kekerasan</option>
                                    <option value="Halusinasi" {{ $selectedDiagnosa === 'Halusinasi' ? 'selected' : '' }}>
                                        Halusinasi</option>
                                    <option value="Isolasi Sosial"
                                        {{ $selectedDiagnosa === 'Isolasi Sosial' ? 'selected' : '' }}>Isolasi Sosial
                                    </option>
                                    <option value="Resiko Bunuh Diri"
                                        {{ $selectedDiagnosa === 'Resiko Bunuh Diri' ? 'selected' : '' }}>Resiko Bunuh Diri
                                    </option>
                                    <option value="Waham" {{ $selectedDiagnosa === 'Waham' ? 'selected' : '' }}>Waham
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="rencana_tindak_lanjut">Rencana Tindak Lanjut</label>
                                <select name="rencana_tindak_lanjut" id="rencana_tindak_lanjut"
                                    class="form-control select2">
                                    @php
                                        $selectedRencana =
                                            $pengkajian->rencana_tindak_lanjut ?? ($data?->rencana_tindak_lanjut ?? '');
                                    @endphp
                                    <option value="-" {{ $selectedRencana === '-' ? 'selected' : '' }}>-</option>
                                    <option value="Kolaborasi Dokter"
                                        {{ $selectedRencana === 'Kolaborasi Dokter' ? 'selected' : '' }}>Kolaborasi Dokter
                                    </option>
                                    <option value="Perawatan Luka"
                                        {{ $selectedRencana === 'Perawatan Luka' ? 'selected' : '' }}>Perawatan Luka
                                    </option>
                                    <option value="Memberikan Edukasi"
                                        {{ $selectedRencana === 'Memberikan Edukasi' ? 'selected' : '' }}>Memberikan
                                        Edukasi</option>
                                    <option value="Mengukur tanda - tanda vital"
                                        {{ $selectedRencana === 'Mengukur tanda - tanda vital' ? 'selected' : '' }}>
                                        Mengukur tanda - tanda vital</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="bg-primary text-white">ASESMENT AWAL MEDIS RAWAT
                                            JALAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%">Asesment dilakukan melalui</td>
                                        <td>
                                            <div class="form-check form-check-inline mr-2">
                                                <input type="checkbox" id="autoanamnesa"
                                                    name="asesmen_dilakukan_melalui[]" value="autoanamnesa"
                                                    class="form-check-input"
                                                    {{ in_array('autoanamnesa', json_decode($pengkajian?->asesmen_dilakukan_melalui ?? '[]')) ? 'checked' : '' }}>
                                                <label for="autoanamnesa" class="form-check-label">Autoanamnesa</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-2">
                                                <input type="checkbox" id="alloanamnesa"
                                                    name="asesmen_dilakukan_melalui[]" value="alloanamnesa"
                                                    class="form-check-input"
                                                    {{ in_array('autoanamnesa', json_decode($pengkajian?->asesmen_dilakukan_melalui ?? '[]')) ? 'checked' : '' }}>
                                                <label for="alloanamnesa" class="form-check-label">Alloanamnesa</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal / Jam</td>
                                        <td colspan="2">
                                            <input type="date" id="awal_tgl_rajal" name="awal_tgl_rajal"
                                                class="form-control d-inline" style="width: 40%;"
                                                value="{{ $pengkajian ? $pengkajian->awal_tgl_rajal : now()->format('Y-m-d') }}">

                                            /
                                            <input type="time" id="awal_jam_rajal" name="awal_jam_rajal"
                                                class="form-control d-inline" style="width: 40%;"
                                                value="{{ $pengkajian?->awal_jam_rajal }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Keluhan</td>
                                        <td colspan="2">
                                            <textarea id="awal_keluhan" name="awal_keluhan" rows="4" class="form-control" style="width: 80%;">{{ $pengkajian?->awal_keluhan }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Riwayat Penyakit Sekarang</td>
                                        <td colspan="2">
                                            <textarea id="awal_riwayat_penyakit_sekarang" name="awal_riwayat_penyakit_sekarang" rows="4"
                                                class="form-control" style="width: 80%;">{{ $pengkajian?->awal_riwayat_penyakit_sekarang }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Riwayat Penyakit Dahulu</td>
                                        <td colspan="2">
                                            <textarea id="awal_riwayat_penyakit_dahulu" name="awal_riwayat_penyakit_dahulu" rows="4" class="form-control"
                                                style="width: 80%;">{{ $pengkajian?->awal_riwayat_penyakit_dahulu }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Riwayat Penyakit Keluarga</td>
                                        <td colspan="2">
                                            <textarea id="awal_riwayat_penyakit_keluarga" name="awal_riwayat_penyakit_keluarga" rows="4"
                                                class="form-control" style="width: 80%;">{{ $pengkajian?->awal_riwayat_penyakit_keluarga }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Riwayat Alergi Obat</td>
                                        <td colspan="2">
                                            <div class="form-check">
                                                <input type="radio" id="tidak_ada" name="awal_riwayat_alergi_obat"
                                                    value=0 class="form-check-input"
                                                    {{ $pengkajian?->awal_riwayat_alergi_obat == 0 ? 'checked' : '' }}>
                                                <label for="tidak_ada" class="form-check-label">Tidak Ada</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="ada" name="awal_riwayat_alergi_obat"
                                                    value=1 class="form-check-input"
                                                    {{ $pengkajian?->awal_riwayat_alergi_obat == 1 ? 'checked' : '' }}>
                                                <label for="ada" class="form-check-label">Ada,
                                                    Sebutkan</label>
                                                <input type="text" id="alergiInput"
                                                    name="awal_riwayat_alergi_obat_lain"
                                                    {{ $pengkajian?->awal_riwayat_alergi_obat_lain }}
                                                    class="form-control d-inline" style="width: 60%;">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Pemeriksaan Fisik</td>
                                        <td colspan="2">
                                            <textarea id="awal_pemeriksaan_fisik" name="awal_pemeriksaan_fisik" rows="4" class="form-control"
                                                style="width: 80%;">{{ $pengkajian?->awal_pemeriksaan_fisik }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Pemeriksaan Penunjang</td>
                                        <td colspan="2">
                                            <textarea id="awal_pemeriksaan_penunjang" name="awal_pemeriksaan_penunjang" rows="4" class="form-control"
                                                style="width: 80%;">{{ $pengkajian?->awal_pemeriksaan_penunjang }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Diagnosa Kerja</td>
                                        <td colspan="2">
                                            <textarea id="awal_diagnosa_kerja" name="awal_diagnosa_kerja" rows="4" class="form-control"
                                                style="width: 80%;">{{ $pengkajian?->awal_diagnosa_kerja }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Diagnosa Banding</td>
                                        <td colspan="2">
                                            <textarea id="awal_diagnosa_banding" name="awal_diagnosa_banding" rows="4" class="form-control"
                                                style="width: 80%;">{{ $pengkajian?->awal_diagnosa_banding }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Terapi/Tindakan</td>
                                        <td colspan="2">
                                            <textarea id="awal_terapi_tindakan" name="awal_terapi_tindakan" rows="4" class="form-control"
                                                style="width: 80%;">{{ $pengkajian?->awal_terapi_tindakan }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Edukasi</td>
                                        <td colspan="2">
                                            <div class="form-check">
                                                <input type="checkbox" id="edukasi_proses_penyakit" name="awal_edukasi[]"
                                                    value="proses_penyakit" class="form-check-input"
                                                    {{ in_array('proses_penyakit', json_decode($pengkajian?->awal_edukasi) ?? []) ? 'checked' : '' }}>
                                                <label for="edukasi_proses_penyakit" class="form-check-label">Proses
                                                    Penyakit</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" id="edukasi_terapi" name="awal_edukasi[]"
                                                    value="terapi" class="form-check-input"
                                                    {{ in_array('edukasi_terapi', json_decode($pengkajian?->awal_edukasi) ?? []) ? 'checked' : '' }}>
                                                <label for="edukasi_terapi" class="form-check-label">Terapi</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" id="edukasi_tindakan_medis" name="awal_edukasi[]"
                                                    value="tindakan_medis" class="form-check-input"
                                                    {{ in_array('tindakan_medis', json_decode($pengkajian?->awal_edukasi) ?? []) ? 'checked' : '' }}>
                                                <label for="edukasi_tindakan_medis" class="form-check-label">Tindakan
                                                    Medis</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Evaluasi Penyakit</td>
                                        <td colspan="2">
                                            <div class="form-check">
                                                <input type="checkbox" id="evaluasi_akut" name="awal_evaluasi_penyakit[]"
                                                    value="akut" class="form-check-input"
                                                    {{ in_array('akut', json_decode($pengkajian?->awal_evaluasi_penyakit) ?? []) ? 'checked' : '' }}>
                                                <label for="evaluasi_akut" class="form-check-label">Akut</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" id="evaluasi_kronis"
                                                    name="awal_evaluasi_penyakit[]" value="kronis"
                                                    class="form-check-input"
                                                    {{ in_array('kronis', json_decode($pengkajian?->awal_evaluasi_penyakit) ?? []) ? 'checked' : '' }}>
                                                <label for="evaluasi_kronis" class="form-check-label">Kronis</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Rencana Tindak Lanjut</td>
                                        <td colspan="2">
                                            <div class="form-check">
                                                <input type="checkbox" id="rencana_rawat_jalan"
                                                    name="awal_rencana_tindak_lanjut[]" value="rajal"
                                                    class="form-check-input"
                                                    {{ in_array('rajal', json_decode($pengkajian?->awal_rencana_tindak_lanjut) ?? []) ? 'checked' : '' }}>
                                                <label for="rencana_rawat_jalan" class="form-check-label">Rawat
                                                    Jalan</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" id="rencana_rawat_inap"
                                                    name="awal_rencana_tindak_lanjut[]" value="ranap"
                                                    class="form-check-input"
                                                    {{ in_array('ranap', json_decode($pengkajian?->awal_rencana_tindak_lanjut) ?? []) ? 'checked' : '' }}>
                                                <label for="rencana_rawat_inap" class="form-check-label">Rawat
                                                    Inap</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" id="rencana_rujuk"
                                                    name="awal_rencana_tindak_lanjut[]" value="rujuk"
                                                    class="form-check-input"
                                                    {{ in_array('rujuk', json_decode($pengkajian?->awal_rencana_tindak_lanjut) ?? []) ? 'checked' : '' }}>
                                                <label for="rencana_rujuk" class="form-check-label">Rujuk</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" id="rencana_konsul"
                                                    name="awal_rencana_tindak_lanjut[]" value="konsul"
                                                    class="form-check-input"
                                                    {{ in_array('konsul', json_decode($pengkajian?->awal_rencana_tindak_lanjut) ?? []) ? 'checked' : '' }}>
                                                <label for="rencana_konsul" class="form-check-label">Konsul</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 px-3">
                            @include('pages.simrs.erm.partials.signature-field', [
                                'judul' => 'Dokter, ',
                                'pic' => $registration->doctor->employee->fullname,
                                'role' => 'Dokter',
                            ])
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer mr-2"></span> Print
                                    </button>
                                    <div style="width: 40%" class="d-flex justify-content-between">
                                        <button type="button"
                                            class="btn btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-pengkajian-dokter-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-pengkajian-dokter-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.erm.partials.action-js.pengkajian-dokter')
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
@endsection
