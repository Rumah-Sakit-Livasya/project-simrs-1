@extends('pages.simrs.erm.index')
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <header class="text-primary text-center mt-5">
            <h4 class="text-center font-weight-bold mb-4">SURVEILANS INFEKSI RUMAH SAKIT</h4>
        </header>
        <form id="form-surveilans-infeksi" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">

            <div class="card-body">

                {{-- Bagian Data Awal --}}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Tgl Masuk / Jam</label>
                        <input class="form-control" name="tgl_masuk" type="datetime-local"
                            value="{{ optional($pengkajian->tgl_masuk)->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Cara Dirawat</label>
                        <select class="form-control" name="cara_dirawat">
                            <option value=""></option>
                            <option value="Emergency" @selected($pengkajian->cara_dirawat == 'Emergency')>Emergency</option>
                            <option value="Elektif" @selected($pengkajian->cara_dirawat == 'Elektif')>Elektif</option>
                        </select>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Diagnosa Waktu Masuk</label>
                        <input type="text" class="form-control" name="diagnosa_masuk"
                            value="{{ $pengkajian->diagnosa_masuk }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Pindah ke Ruangan</label>
                        <input class="form-control" name="pindah_ke_ruangan" type="text"
                            value="{{ $pengkajian->pindah_ke_ruangan }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Tanggal Pindah</label>
                        <input class="form-control" name="tgl_pindah" type="date"
                            value="{{ optional($pengkajian->tgl_pindah)->format('Y-m-d') }}">
                    </div>
                </div>

                <hr>
                <h5 class="font-weight-bold">Faktor Resiko Selama Dirawat</h5>
                {{-- Tabel Faktor Resiko --}}
                {{-- Di sini kita akan menggunakan @include untuk membuat kode lebih rapi --}}
                @include('pages.simrs.erm.form.perawat.component.surveilans-faktor-resiko', [
                    'data' => $pengkajian->faktor_resiko ?? [],
                ])

                <hr>
                {{-- Bagian Lainnya --}}
                @include('pages.simrs.erm.form.perawat.component.surveilans-faktor-penyakit', [
                    'data' => $pengkajian->faktor_penyakit ?? [],
                ])

                <hr>
                @include('pages.simrs.erm.form.perawat.component.surveilans-tindakan-operasi', [
                    'data' => $pengkajian->tindakan_operasi ?? [],
                ])

                <hr>
                @include('pages.simrs.erm.form.perawat.component.surveilans-komplikasi', [
                    'data' => $pengkajian->komplikasi_infeksi ?? [],
                ])

                <hr>
                @include('pages.simrs.erm.form.perawat.component.surveilans-antimikroba', [
                    'data' => $pengkajian->pemakaian_antimikroba ?? [],
                ])

                <hr>
                <h5 class="font-weight-bold">Data Pasien Keluar</h5>
                {{-- Bagian Data Keluar --}}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Tgl. Pasien keluar RS / Meninggal</label>
                        <input class="form-control" name="tgl_keluar" type="date"
                            value="{{ optional($pengkajian->tgl_keluar)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Keterangan Pasien Keluar</label>
                        <select class="form-control" name="keterangan_keluar">
                            <option value=""></option>
                            <option value="BLPL" @selected($pengkajian->keterangan_keluar == 'BLPL')>BLPL</option>
                            <option value="MENINGGAL" @selected($pengkajian->keterangan_keluar == 'MENINGGAL')>MENINGGAL</option>
                            <option value="APS" @selected($pengkajian->keterangan_keluar == 'APS')>APS</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Pindah ke RS Lain</label>
                        <input class="form-control" name="pindah_rs_lain" type="text"
                            value="{{ $pengkajian->pindah_rs_lain }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Diagnosa Akhir</label>
                        <input class="form-control" name="diagnosa_akhir" type="text"
                            value="{{ $pengkajian->diagnosa_akhir }}">
                    </div>
                </div>

                <hr>
                {{-- Tanda Tangan --}}
                <div class="row text-center mt-4">
                    <div class="col-md-6">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Perawat penanggung jawab/ pengisi formulir',
                            'name_prefix' => 'signatures[penanggung_jawab]',
                            'role' => 'penanggung_jawab',
                            'index' => 'surveilans_pj',
                            'signature_model' => $pengkajian->signatures()->where('role', 'penanggung_jawab')->first(),
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'IPCLN',
                            'name_prefix' => 'signatures[ipcln]',
                            'role' => 'ipcln',
                            'index' => 'surveilans_ipcln',
                            'signature_model' => $pengkajian->signatures()->where('role', 'ipcln')->first(),
                        ])
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-surveilans">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script>
        $(function() {
            // Inisialisasi plugin jika ada (misal: select2, datepicker)
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

            // AJAX untuk menyimpan form
            $('#form-surveilans-infeksi').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const saveButton = $('#btn-save-surveilans');

                saveButton.prop('disabled', true).html('Menyimpan...');

                $.ajax({
                    url: "{{ route('erm.surveilans-infeksi.store') }}",
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire('Sukses!', response.success, 'success');
                    },
                    error: function(jqXHR) {
                        // Tampilkan error validasi jika ada
                        let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                        if (jqXHR.status === 422) {
                            errorMsg = Object.values(jqXHR.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        Swal.fire('Error!', errorMsg, 'error');
                    },
                    complete: function() {
                        saveButton.prop('disabled', false).html('Simpan Data');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // ==========================================================
            // LOGIKA BARU UNTUK POPUP TANDA TANGAN
            // ==========================================================

            // Fungsi ini dipanggil dari window popup untuk mengupdate halaman utama
            window.updateSignature = function(targetInputId, targetPreviewId, dataURL) {
                // Cari elemen di halaman utama dan isi nilainya
                const inputField = document.getElementById(targetInputId);
                const previewImage = document.getElementById(targetPreviewId);

                if (inputField) {
                    inputField.value = dataURL;
                }
                if (previewImage) {
                    previewImage.src = dataURL;
                    previewImage.style.display = 'block';
                }
            };

            // Fungsi ini dipanggil oleh tombol "Tanda Tangan" untuk membuka popup
            window.openSignaturePopup = function(targetInputId, targetPreviewId) {
                const windowWidth = screen.availWidth;
                const windowHeight = screen.availHeight;
                const left = 0;
                const top = 0;

                // Bangun URL dengan query string untuk memberitahu popup elemen mana yang harus diupdate
                const url =
                    `{{ route('signature.pad') }}?targetInput=${targetInputId}&targetPreview=${targetPreviewId}`;

                // Buka popup window
                window.open(
                    url,
                    'SignatureWindow',
                    `width=${windowWidth},height=${windowHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
                );
            };
        });
    </script>
@endpush
