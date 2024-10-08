<div class="tab-pane fade" id="pengkajian-dokter-rajal" role="tabpanel">
    <div class="card-body">
        <header class="text-primary text-center font-weight-bold mb-4">
            <h2>PENGKAJIAN DOKTER</h4>
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
                        <label for="pr">Nadi (PR)</label>
                        <div class="input-group">
                            <input class="form-control numeric" id="pr" name="pr" type="text">
                            <div class="input-group-append">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="rr">Respirasi (RR)</label>
                        <div class="input-group">
                            <input class="form-control numeric" id="rr" name="rr" type="text">
                            <div class="input-group-append">
                                <span class="input-group-text">x/menit</span>
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
                            <input class="form-control numeric" id="bp" name="bp" type="text">
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
                            <input class="form-control numeric" id="temperatur" name="temperatur" type="text">
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
                        <label for="height_badan">Tinggi Badan</label>
                        <div class="input-group">
                            <input class="form-control numeric calc-bmi" id="height_badan" name="height_badan"
                                type="text">
                            <div class="input-group-append">
                                <span class="input-group-text">Cm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="weight_badan">Berat Badan</label>
                        <div class="input-group">
                            <input class="form-control numeric calc-bmi" id="weight_badan" name="weight_badan"
                                type="text">
                            <div class="input-group-append">
                                <span class="input-group-text">Kg</span>
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
                            <input class="form-control numeric" id="bmi" name="bmi" readonly type="text">
                            <div class="input-group-append">
                                <span class="input-group-text">Kg/m²</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="kat_bmi">Katerogi IMT</label>
                        <input class="form-control" id="kat_bmi" name="kat_bmi" readonly type="text">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="sp02">SP 02</label>
                        <input class="form-control" id="sp02" name="sp02" type="text">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="diagnosa_keperawatan">Diagnosa Keperawatan</label>
                        <select name="diagnosa_keperawatan" id="diagnosa_keperawatan" class="form-control">
                            <option value="-">-</option>
                            <option value="Gangguan rasa nyaman">Gangguan rasa nyaman</option>
                            <option value="Nyeri">Nyeri</option>
                            <option value="Pola Nafas tidak efektif">Pola Nafas tidak efektif</option>
                            <option value="Bersihan jalan nafas tidak efektif">Bersihan jalan nafas tidak efektif
                            </option>
                            <option value="Nyeri Akut">Nyeri Akut</option>
                            <option value="Nyeri Kronis">Nyeri Kronis</option>
                            <option value="Resiko Infeksi">Resiko Infeksi</option>
                            <option value="Harga diri Rendah">Harga diri Rendah</option>
                            <option value="Resiko Perilaku Kekerasan">Resiko Perilaku Kekerasan</option>
                            <option value="Halusinasi">Halusinasi</option>
                            <option value="Isolasi Sosial">Isolasi Sosial</option>
                            <option value="Resiko Bunuh Diri">Resiko Bunuh Diri</option>
                            <option value="Waham">Waham</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="rencana_tindak_lanjut">Rencana Tindak Lanjut</label>
                        <select name="rencana_tindak_lanjut" id="rencana_tindak_lanjut" class="form-control">
                            <option value="-">-</option>
                            <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                            <option value="Perawatan Luka">Perawatan Luka</option>
                            <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                            <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda vital</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="bg-primary text-white">ASESMENT AWAL MEDIS RAWAT JALAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="20%">Asesment dilakukan melalui</td>
                                <td>
                                    <div class="form-check form-check-inline mr-2">
                                        <input type="checkbox" id="autoanamnesa" name="asesmen_dilakukan_melalui_1"
                                            value="autoanamnesa" class="form-check-input">
                                        <label for="autoanamnesa" class="form-check-label">Autoanamnesa</label>
                                    </div>
                                    <div class="form-check form-check-inline mr-2">
                                        <input type="checkbox" id="alloamnesa" name="asesmen_dilakukan_melalui_2"
                                            value="alloamnesa" class="form-check-input">
                                        <label for="alloamnesa" class="form-check-label">Alloanamnesa</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal / Jam</td>
                                <td colspan="2">
                                    <input type="date" name="awal_tgl_rajal" class="form-control d-inline"
                                        style="width: 40%;" value="{{ now()->format('Y-m-d') }}">

                                    /
                                    <input type="time" name="awal_jam_rajal" class="form-control d-inline"
                                        style="width: 40%;">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Keluhan</td>
                                <td colspan="2">
                                    <textarea name="awal_keluhan" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Riwayat Penyakit Sekarang</td>
                                <td colspan="2">
                                    <textarea name="awal_riwayat_penyakit_sekarang" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Riwayat Penyakit Dahulu</td>
                                <td colspan="2">
                                    <textarea name="awal_riwayat_penyakit_dahulu" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Riwayat Penyakit Keluarga</td>
                                <td colspan="2">
                                    <textarea name="awal_riwayat_penyakit_keluarga" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Riwayat Alergi Obat</td>
                                <td colspan="2">
                                    <div class="form-check">
                                        <input type="radio" id="tidak_ada" name="awal_riwayat_alergi_obat"
                                            value="Tidak" class="form-check-input">
                                        <label for="tidak_ada" class="form-check-label">Tidak Ada</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="ada" name="awal_riwayat_alergi_obat"
                                            value="Ya" class="form-check-input">
                                        <label for="ada" class="form-check-label">Ada, Sebutkan</label>
                                        <input type="text" name="awal_riwayat_alergi_obat_lain"
                                            class="form-control d-inline" style="width: 60%;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Pemeriksaan Fisik</td>
                                <td colspan="2">
                                    <textarea name="awal_pemeriksaan_fisik" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Pemeriksaan Penunjang</td>
                                <td colspan="2">
                                    <textarea name="awal_pemeriksaan_penunjang" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Diagnosa Kerja</td>
                                <td colspan="2">
                                    <textarea name="awal_diagnosa_kerja" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Diagnosa Banding</td>
                                <td colspan="2">
                                    <textarea name="awal_diagnosa_banding" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Terapi/Tindakan</td>
                                <td colspan="2">
                                    <textarea name="awal_terapi_tindakan" rows="4" class="form-control" style="width: 80%;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Edukasi</td>
                                <td colspan="2">
                                    <div class="form-check">
                                        <input type="checkbox" id="edukasi_proses_penyakit" name="awal_edukasi1"
                                            value="proses_penyakit" class="form-check-input">
                                        <label for="edukasi_proses_penyakit" class="form-check-label">Proses
                                            Penyakit</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="edukasi_terapi" name="awal_edukasi2"
                                            value="terapi" class="form-check-input">
                                        <label for="edukasi_terapi" class="form-check-label">Terapi</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="edukasi_tindakan_medis" name="awal_edukasi3"
                                            value="tindakan_medis" class="form-check-input">
                                        <label for="edukasi_tindakan_medis" class="form-check-label">Tindakan
                                            Medis</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Evaluasi Penyakit</td>
                                <td colspan="2">
                                    <div class="form-check">
                                        <input type="checkbox" id="evaluasi_akut" name="awal_evaluasi_penyakit1"
                                            value="akut" class="form-check-input">
                                        <label for="evaluasi_akut" class="form-check-label">Akut</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="evaluasi_kronis" name="awal_evaluasi_penyakit2"
                                            value="kronis" class="form-check-input">
                                        <label for="evaluasi_kronis" class="form-check-label">Kronis</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Rencana Tindak Lanjut</td>
                                <td colspan="2">
                                    <div class="form-check">
                                        <input type="checkbox" id="rencana_rawat_jalan"
                                            name="awal_rencana_tindak_lanjut1" value="akut"
                                            class="form-check-input">
                                        <label for="rencana_rawat_jalan" class="form-check-label">Rawat Jalan</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="rencana_rawat_inap"
                                            name="awal_rencana_tindak_lanjut2" value="kronis"
                                            class="form-check-input">
                                        <label for="rencana_rawat_inap" class="form-check-label">Rawat Inap</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="rencana_rujuk" name="awal_rencana_tindak_lanjut3"
                                            value="rujuk" class="form-check-input">
                                        <label for="rencana_rujuk" class="form-check-label">Rujuk</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="rencana_konsul" name="awal_rencana_tindak_lanjut4"
                                            value="konsul" class="form-check-input">
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
                    <div class="form-group">
                        <div class="input-group mb-4">
                            <div class="d-flex flex-column align-items-center">
                                <span>Dokter,</span>
                                <img src="http://192.168.1.253/real/pengkajian/54974/images/pkid_166671_data_ttd.png?6703600e7ad50"
                                    id="img_ttd" class="img-fluid" style="max-width: 200px; max-height: 100px;"
                                    onerror="this.onerror=null; this.src='http://192.168.1.253/real/include/ttd_pegawai/955.png'">
                                <input type="text" name="nama_dokter" class="form-control text-center"
                                    value="{{ $registration->doctor->employee->fullname }}" style="width: 100%;">
                                <span class="badge bg-primary pointer" id="btn-ttd">TTD Pen Tablet</span>
                            </div>
                        </div>
                    </div>
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
                            <div style="width: 33%" class="d-flex justify-content-between">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let actionType = '';

        // Saat tombol Save Draft diklik
        $('#sd-pengkajian-dokter-rajal').on('click', function() {
            actionType = 'draft';
            submitForm(actionType); // Panggil fungsi submitForm dengan parameter draft
        });

        // Saat tombol Save Final diklik
        $('#sf-pengkajian-dokter-rajal').on('click', function() {
            actionType = 'final';
            submitForm(actionType); // Panggil fungsi submitForm dengan parameter final
        });

        function submitForm(actionType) {
            const form = $('#pengkajian-dokter-rajal-form'); // Ambil form
            const url = "{{ route('pengkajian.dokter-rajal.store') }}" // Ambil URL dari action form
            let formData = form.serialize(); // Ambil data dari form

            // Tambahkan tipe aksi (draft atau final) ke data form
            formData += '&action_type=' + actionType;

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    if (actionType === 'draft') {
                        alert('Data berhasil disimpan sebagai draft!');
                    } else {
                        alert('Data berhasil disimpan sebagai final!');
                    }
                },
                error: function(response) {
                    // Tangani error
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        alert(value[0]);
                    });
                }
            });
        }
    })
</script>
