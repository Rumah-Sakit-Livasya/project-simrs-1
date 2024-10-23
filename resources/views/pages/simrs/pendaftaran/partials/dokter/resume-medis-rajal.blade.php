<div class="tab-pane fade mt-3 border-top" id="resume-medis-rajal" role="tabpanel">
    <div id="alert-resume-medis-rajal"></div>
    <header class="text-primary text-center font-weight-bold mt-4 mb-4">
        <h2>RINGKASAN PASIEN RAWAT JALAN</h4>
    </header>
    <div class="row">
        <div class="col-md-12 px-4 pb-2 pt-4">
            <form id="resume-medis-rajal-form">
                @csrf
                @method('POST')
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
                                    <input class="form-check-input custom-checkbox" type="radio" id="kunjungan_awal"
                                        name="alasan_masuk_rs" value="kunjungan_awal">
                                    <label class="form-check-label ml-2" for="kunjungan_awal">Kunjungan Awal</label>
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
                                    <input class="form-check-input custom-checkbox" type="radio" id="kontrol_lanjutan"
                                        name="alasan_masuk_rs" value="kontrol_lanjutan">
                                    <label class="form-check-label ml-2" for="kontrol_lanjutan">Kontrol Lanjutan</label>
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
                                    <input class="form-check-input custom-checkbox" type="radio" id="observasi"
                                        name="alasan_masuk_rs" value="observasi">
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
                                <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                    value="{{ $registration->patient->gender }}" readonly>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="radio" id="post_operasi"
                                        name="alasan_masuk_rs" value="post_operasi">
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
                                <input type="datetime-local" class="form-control" id="tgl_masuk" name="tgl_masuk"
                                    placeholder="dd/mm/yyyy"
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
                                    <input class="form-check-input custom-checkbox" type="radio" id="sembuh"
                                        name="cara_keluar" value="sembuh">
                                    <label class="form-check-label" for="sembuh">Sembuh</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-checkbox" type="radio" id="meninggal"
                                        name="cara_keluar" value="meninggal">
                                    <label class="form-check-label" for="meninggal">Meninggal</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-checkbox" type="radio" id="rawat"
                                        name="cara_keluar" value="rawat">
                                    <label class="form-check-label" for="rawat">Rawat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-checkbox" type="radio" id="rujuk"
                                        name="cara_keluar" value="rujuk">
                                    <label class="form-check-label" for="rujuk">Rujuk</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-checkbox" type="radio" id="aps"
                                        name="cara_keluar" value="aps">
                                    <label class="form-check-label" for="aps">APS</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-checkbox" type="radio" id="kontrol"
                                        name="cara_keluar" value="kontrol">
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
                                                    <label for="diagnosa_utama" class="form-label">DIAGNOSA UTAMA
                                                        *</label>
                                                    <textarea class="form-control" id="diagnosa_utama" name="diagnosa_utama" rows="4" required>Diagnosa Kerja    : P3A1POST SC</textarea>
                                                </div>
                                            </td>
                                            <td style="width: 25%">
                                                <div class="form-group">
                                                    <label for="cari_icd" class="form-label">Cari ICD 10</label>
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
                                                    <label for="cari_icd2" class="form-label">Cari ICD 9</label>
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
                                                    <label for="cari_icd2_tambahan" class="form-label">Cari ICD
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
                                                    <img id="signature-display" src="" alt="Signature Image"
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
                                    <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0">
                                            <span class="mdi mdi-printer"></span> Cetak
                                        </button>
                                        <div style="width: 33%" class="d-flex justify-content-between"
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

<script>
    $(document).ready(function() {

        $('.datepicker-input').datepicker({
            format: 'dd/mm/yyyy', // Format tampilan tanggal
            autoclose: true, // Menutup datepicker otomatis setelah memilih tanggal
            todayHighlight: true, // Menyoroti tanggal hari ini
            language: 'id', // Locale Indonesia untuk hari dan bulan
        });


        $('.bsd-resume-medis-rajal').on('click', function() {
            submitFormResume('draft'); // Panggil fungsi submitFormResume dengan parameter final
        });
        $('.bsf-resume-medis-rajal').on('click', function() {
            submitFormResume('final'); // Panggil fungsi submitForm dengan parameter final
        });

        $('.btn-ttd-resume-medis').on('click', function() {
            const idUser = $(this).attr('data-id');
            const token = "{{ csrf_token() }}";
            const ttd = "{{ auth()->user()->employee->ttd ? auth()->user()->employee->ttd : '' }}";

            if (ttd) {
                const path = "/api/simrs/signature/" + ttd + "?token=" + token;
                $(this).hide();
                $('input[name=is_ttd]').val(1);
                $('#signature-display').attr('src', path).show();
            } else {
                showErrorAlert('Tanda tangan tidak ditemukan!');
            }
        });

        function submitFormResume(actionType) {
            const form = $('#resume-medis-rajal-form'); // Ambil form
            const url = "{{ route('resume-medis.dokter-rajal.store') }}" // Ambil URL dari action form

            let formData = form.serialize(); // Ambil data dari form

            // Tambahkan tipe aksi (draft atau final) ke data form
            formData += '&action_type=' + actionType + '&registration_id=' + "{{ $registration->id }}";

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
                    showErrorAlert('Gagal Disimpan!');
                }
            });
        }
    });
</script>
