@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="triage-form" method="POST">
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">TRIAGE</h2>
                    </header>
                    <div class="row my-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_masuk" class="control-label text-primary">Hari/Tanggal</label>
                                <input type="date" name="tgl_masuk" class="form-control"
                                    value="{{ $pengkajian?->tgl_masuk ?? now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jam_masuk" class="control-label text-primary">Pukul Pasien
                                    Datang</label>
                                <input type="time" name="jam_masuk" class="form-control"
                                    value="{{ $pengkajian?->jam_masuk ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jam_dilayani" class="control-label text-primary">Pukul Pasien
                                    Periksa</label>
                                <input type="time" name="jam_dilayani" class="form-control"
                                    value="{{ $pengkajian?->jam_dilayani ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="pr">Nadi (PR)</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="pr" type="text" name="pr" class="form-control"
                                            value="{{ $pengkajian?->pr }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="rr" class="text-primary">Respirasi (RR)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="rr" name="rr" type="text"
                                        value="{{ $pengkajian?->rr }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="bp" class="text-primary">Tensi (BP)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="bp" name="bp" type="text"
                                        value="{{ $pengkajian?->bp }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="temperatur" class="text-primary">Suhu (T)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="temperatur" name="temperatur" type="text"
                                        value="{{ $pengkajian?->temperatur }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">C°</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="height" class="text-primary">Tinggi Badan</label>
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi" id="body_height" name="body_height"
                                        type="text" value="{{ $pengkajian?->body_height }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="weight" class="text-primary">Berat Badan</label>
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                        type="text" value="{{ $pengkajian?->body_weight }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="bmi" class="text-primary">Index Massa Tubuh</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="bmi" name="bmi"
                                        readonly="readonly" type="text" value="{{ $pengkajian?->bmi }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg/m²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="kat_bmi" class="text-primary">Kategori IMT</label>
                                <div class="input-group">
                                    <input class="form-control" id="kat_bmi" name="kat_bmi" readonly="readonly"
                                        type="text" value="{{ $pengkajian?->kat_bmi }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="sp02" class="text-primary">SP 02</label>
                                <div class="input-group">
                                    <input class="form-control" id="sp02" name="sp02" type="text"
                                        value="{{ $pengkajian?->sp02 }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="lingkar_perut" class="text-primary">Lingkar
                                    Perut</label>
                                <div class="input-group">
                                    <input class="form-control" id="lingkar_perut" name="lingkar_perut" type="text"
                                        value="{{ $pengkajian?->lingkar_perut }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h4 class="text-primary">Anamnesa</h4>
                            <div class="form-group d-flex">
                                <div class="form-check mr-4">
                                    <input class="form-check-input" type="checkbox" name="auto_anamnesa"
                                        id="auto_anamnesa" value="1">
                                    <label class="form-check-label" for="auto_anamnesa">Auto Anamnesa</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allo_anamnesa"
                                        id="allo_anamnesa" value="1">
                                    <label class="form-check-label" for="allo_anamnesa">Allo Anamnesa</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">KATEGORI</th>
                                    <th colspan="2" class="bg-danger text-white">MERAH</th>
                                    <th colspan="2" class="bg-warning">KUNING</th>
                                    <th colspan="2" class="bg-success text-white">HIJAU</th>
                                </tr>
                                <tr class="text-center">
                                    <th><span class="font-weight-bold">Kategori
                                            1</span><br>Resusitasi<br>Respon
                                        time: <span class="font-weight-bold">SEGERA</span></th>
                                    <th><span class="font-weight-bold">Kategori 2</span><br>Emergency/Gawat
                                        Darurat<br>Respon time: <span class="font-weight-bold">10 Menit</span>
                                    </th>
                                    <th><span class="font-weight-bold">Kategori
                                            3</span><br>Urgent/Darurat<br>Respon time: <span class="font-weight-bold">30
                                            Menit</span>
                                    </th>
                                    <th><span class="font-weight-bold">Kategori 4</span><br>Semi
                                        Darurat<br>Respon time: <span class="font-weight-bold">60 Menit</span>
                                    </th>
                                    <th><span class="font-weight-bold">Tidak Darurat</span><br>Respon time:
                                        <span class="font-weight-bold">120 Menit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Airway</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="airway_merah[]"
                                                value="Sumbatan Total" id="sumbatan-total">
                                            <label class="form-check-label" for="sumbatan-total">Sumbatan Total</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="airway_merah[]"
                                                value="Sumbatan Sebagian" id="sumbatan-sebagian">
                                            <label class="form-check-label" for="sumbatan-sebagian">Sumbatan
                                                Sebagian</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="airway_merah[]"
                                                value="Paten" id="paten">
                                            <label class="form-check-label" for="paten">Paten</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="airway_kuning[]"
                                                value="Paten" id="paten-2">
                                            <label class="form-check-label" for="paten-2">Paten</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="airway_kuning[]"
                                                value="Paten" id="paten-3">
                                            <label class="form-check-label" for="paten-3">Paten</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="airway_hijau[]"
                                                value="Tidak Ada Kelainan" id="tidak-ada-kelainan">
                                            <label class="form-check-label" for="tidak-ada-kelainan">Tidak Ada
                                                Kelainan</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Breathing</td>
                                    <td>
                                        <span class="font-weight-bold">Distres Pernapasan Berat</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_merah[]"
                                                value="Sumbatan Total" id="sumbatan-total-2">
                                            <label class="form-check-label" for="sumbatan-total-2">Sumbatan Total</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_merah[]"
                                                value="Sumbatan Sebagian" id="sumbatan-sebagian-2">
                                            <label class="form-check-label" for="sumbatan-sebagian-2">Sumbatan
                                                Sebagian</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Distres Pernapasan Sedang</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_merah[]"
                                                value="RR > 30 x/menit" id="field-1">
                                            <label class="form-check-label" for="field-1">RR > 30 x/menit</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_merah[]"
                                                value="Penggunaan Otot Bantuan Nafas" id="field-2">
                                            <label class="form-check-label" for="field-2">Penggunaan Otot Bantuan
                                                Nafas</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Distres Pernapasan Ringan</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_kuning[]"
                                                value="RR > 30 x/menit" id="field-3">
                                            <label class="form-check-label" for="field-3">RR > 30 x/menit</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Tidak Ada Distres pernafasan</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_kuning[]"
                                                value="RR Normal" id="field-4">
                                            <label class="form-check-label" for="field-4">RR Normal</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="breathing_hijau[]"
                                                value="Tidak Ada Distres Pernapasan" id="field-5">
                                            <label class="form-check-label" for="field-5">Respon Time: 120 Menit</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Circulation</td>
                                    <td>
                                        <span class="font-weight-bold">Gangguan hemodinamik berat</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_merah[]"
                                                value="Nadi Tidak Teraba" id="field-6">
                                            <label class="form-check-label" for="field-6">Nadi Tidak Teraba</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_merah[]"
                                                value="Perdarahan yang tidak terkontrol/aktif" id="field-7">
                                            <label class="form-check-label" for="field-7">Perdarahan yang tidak
                                                terkontrol/aktif</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Gangguan hemodinamik sedang</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_merah[]"
                                                value="Nadi Tidak Teraba/Halus" id="field-8">
                                            <label class="form-check-label" for="field-8">Nadi Tidak Teraba/Halus</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_merah[]"
                                                value="Perdarahan kapiler > 2 Detik" id="field-9">
                                            <label class="form-check-label" for="field-9">Perdarahan kapiler > 2
                                                Detik</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Gangguan hemodinamik ringan</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_kuning[]"
                                                value="Nadi Teraba lemah-kuat" id="field-10">
                                            <label class="form-check-label" for="field-10">Nadi Teraba lemah-kuat</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_kuning[]"
                                                value="Perdarahan kapiler < 2 Detik" id="field-11">
                                            <label class="form-check-label" for="field-11">Perdarahan kapiler < 2
                                                    Detik</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Tidak Ada Gangguan Hemodinamik</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_kuning[]"
                                                value="Nadi Teraba" id="field-12">
                                            <label class="form-check-label" for="field-12">Nadi Teraba</label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">Tidak Ada Gangguan Hemodinamik</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="circulation_hijau[]"
                                                value="Nadi Teraba Kuat" id="field-13">
                                            <label class="form-check-label" for="field-13">Nadi Teraba Kuat</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Disability</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disability[]"
                                                value="GCS 0" id="field-14">
                                            <label class="form-check-label" for="field-14">GCS 0</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disability[]"
                                                value="GCS < 9" id="field-15">
                                            <label class="form-check-label" for="field-15">GCS < 9</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disability[]"
                                                value="GCS 9 - 12" id="field-16">
                                            <label class="form-check-label" for="field-16">GCS 9 - 12</label>
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disability[]"
                                                value="GCS 12 - 15" id="field-17">
                                            <label class="form-check-label" for="field-17">GCS 12 - 15</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kesimpulan</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="kesimpulan[]"
                                                value="Hitam" id="field-18">
                                            <label class="form-check-label" for="field-18">Hitam (DOA)</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="kesimpulan[]"
                                                value="Merah" id="field-19">
                                            <label class="form-check-label" for="field-19">Merah</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="kesimpulan[]"
                                                value="Kuning" id="field-20">
                                            <label class="form-check-label" for="field-20">Kuning</label>
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="kesimpulan[]"
                                                value="Hijau" id="field-21">
                                            <label class="form-check-label" for="field-21">Hijau</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DAA dengan warna Hitam</td>
                                    <td colspan="6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="daa_hitam"
                                                value="1" id="field-22">
                                            <label class="form-check-label" for="field-22">DAA dengan warna Hitam</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Contoh pemanggilan yang sudah diperbaiki --}}
                    @include('pages.simrs.erm.partials.signature-field', [
                        'judul' => 'Perawat,',
                        'pic' => auth()->user()->employee->fullname,
                        'role' => 'perawat',
                        'prefix' => 'triage', // Berikan prefix unik
                        'signature_model' => $pengkajian?->signature, // Kirim model data tanda tangan yang relevan
                    ])

                    <div class="row mt-5 justify-content-center">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer mr-2"></span> Print
                                    </button>
                                    <div style="width: 40%" class="d-flex justify-content-end">
                                        {{-- <button type="button"
                                            class="btn mr-2 btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button> --}}
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-triage">
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
    <script>
        $(document).ready(function() {
            $(function() {
                $('.select2').select2({
                    dropdownCssClass: "move-up"
                });
                $(".select2").on("select2:open", function() {
                    // Mengambil elemen kotak pencarian
                    var searchField = $(".select2-search__field");

                    // Mengubah urutan elemen untuk memindahkannya ke atas
                    searchField.insertBefore(searchField.prev());
                });
            });

            $('#triage-form').on('submit', function(e) {
                e.preventDefault();

                // Ambil semua data dari form
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('igd.triage.store') }}", // Route Laravel untuk menyimpan data
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#sf-triage').prop('disabled', true).text(
                            'Saving...');
                    },
                    success: function(response) {
                        showSuccessAlert('Data berhasil disimpan!');
                        // Optional: redirect atau refresh
                    },
                    error: function(xhr, status, error) {
                        showErrorAlertNoRefresh(xhr.responseText);
                    },
                    complete: function() {
                        $('#sf-triage').prop('disabled', false).text(
                            'Simpan (final)');
                    }
                });
            });

            // GET DATA REGISTRATION BY ID
            var registrationId = {{ $registration->id }};
            console.log(registrationId);

            if (registrationId) {
                $.ajax({
                    url: `/simrs/igd/triage/${registrationId}`,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#loading-indicator').show(); // Tampilkan loader
                    },
                    success: function(response) {
                        console.log(response.data); // 👉 Lihat data di console
                        if (response.data) {
                            let data = response.data;

                            // Mengisi input text
                            $('input[name="tgl_masuk"]').val(data.tgl_masuk);
                            $('input[name="jam_masuk"]').val(data.jam_masuk);
                            $('input[name="jam_dilayani"]').val(data.jam_dilayani);
                            $('input[name="pr"]').val(data.pr);
                            $('input[name="bp"]').val(data.bp);
                            $('input[name="body_height"]').val(data.body_height);
                            $('input[name="bmi"]').val(data.bmi);
                            $('input[name="lingkar_dada"]').val(data.lingkar_dada);
                            $('input[name="sp02"]').val(data.sp02);
                            $('input[name="rr"]').val(data.rr);
                            $('input[name="temperatur"]').val(data.temperatur);
                            $('input[name="body_weight"]').val(data.body_weight);
                            $('input[name="kat_bmi"]').val(data.kat_bmi);
                            $('input[name="lingkar_perut"]').val(data.lingkar_perut);

                            // Checkbox auto anamnesa & allo anamnesa
                            $('#auto_anamnesa').prop('checked', data.auto_anamnesa == 1);
                            $('#allo_anamnesa').prop('checked', data.allo_anamnesa == 1);

                            // Checkbox dari JSON array
                            const jsonFields = [
                                'airway_merah',
                                'airway_kuning',
                                'airway_hijau',
                                'breathing_merah',
                                'breathing_kuning',
                                'breathing_hijau',
                                'circulation_merah',
                                'circulation_kuning',
                                'circulation_hijau',
                                'disability',
                                'kesimpulan'
                            ];

                            jsonFields.forEach(field => {
                                if (typeof data[field] === 'string') {
                                    // 👉 Jika masih berbentuk string, di-decode manual
                                    data[field] = JSON.parse(data[field]);
                                }
                                if (Array.isArray(data[field])) {
                                    data[field].forEach(value => {
                                        $(`input[name="${field}[]"][value="${value}"]`)
                                            .prop('checked', true);
                                    });
                                }
                            });

                            // Checkbox untuk daa_hitam
                            if (data.daa_hitam == 1) {
                                $('input[name="daa_hitam"]').prop('checked', true);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        $('#loading-indicator').hide(); // Sembunyikan loader setelah selesai
                    }
                });
            }
        });
    </script>
    @include('pages.simrs.poliklinik.partials.action-js.pengkajian-perawat')
@endsection
