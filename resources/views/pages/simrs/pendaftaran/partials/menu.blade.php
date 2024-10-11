<div class="row menu-detail-regist">
    <div class="col-md-12">
        <ul class="nav nav-tabs px-3 py-2" role="tablist" style="border-top: 1px solid #dddddd !important;">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">PERAWAT</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item active" data-toggle="tab" href="#pengkajian-perawat-rajal"
                        role="tab">Pengkajian</a>
                    <a class="dropdown-item" href="#">CPPT</a>
                    <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">DOKTER</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item click-menu" data-toggle="tab" data-action="dokter-pengkajian"
                        data-regist="{{ $registration->registration_number }}" data-type="rawat-jalan"
                        href="#pengkajian-dokter-rajal" role="tab">Pengkajian</a>
                    <a class="dropdown-item click-menu" data-toggle="tab" data-action="dokter-cppt"
                        data-regist="{{ $registration->registration_number }}" data-type="rawat-jalan"
                        href="#cppt-dokter-rajal" role="tab">CPPT</a>
                    <a class="dropdown-item click-menu" data-toggle="tab" data-action="dokter-resume-medis"
                        data-regist="{{ $registration->registration_number }}" data-type="rawat-jalan"
                        href="#resume-medis-rajal" role="tab">Resume
                        Medis</a>
                    <a class="dropdown-item click-menu" href="#">Rujukan Antar Rumah Sakit</a>
                    <a class="dropdown-item click-menu" href="#">Echo</a>
                    <a class="dropdown-item click-menu" href="#">Profil Ringkas Rawat Jalan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">GIZI</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">FARMASI KLINIS</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">CPPT</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian Resep</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Form Meso</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Profil Obat</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Form Rekonsoliasi
                        Obat</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">LAYANAN</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Tindakan
                        Medis</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pemakaian
                        Alat</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Patologi
                        Klinik</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Radiologi</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">LAIN-LAIN</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Upload
                        Document</a>
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Satu Sehat</a>
                </div>
            </li>
        </ul>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.click-menu').click(function() {
            let menu = $(this).attr('data-action');
            let type = $(this).attr('data-type');
            let registration_number = $(this).attr('data-regist');

            $.ajax({
                type: "GET",
                url: `/api/simrs/erm/${menu}/${type}/${registration_number}/get`,
                success: function(response) {

                    if (menu == 'dokter-pengkajian') {
                        $('#pengkajian-dokter-rajal #registration_id').val(response
                            .registration_id);
                        $('#pengkajian-dokter-rajal #pr').val(response.pr);
                        $('#pengkajian-dokter-rajal #rr').val(response.rr);
                        $('#pengkajian-dokter-rajal #body_height').val(response
                            .body_height);
                        $('#pengkajian-dokter-rajal #body_weight').val(response
                            .body_weight);
                        $('#pengkajian-dokter-rajal #bp').val(response.bp);
                        $('#pengkajian-dokter-rajal #temperatur').val(response.temperatur);
                        $('#pengkajian-dokter-rajal #bmi').val(response.bmi);
                        $('#pengkajian-dokter-rajal #kat_bmi').val(response.kat_bmi);
                        $('#pengkajian-dokter-rajal #sp02').val(response.sp02);
                        $('#pengkajian-dokter-rajal #diagnosa_keperawatan').val(response
                            .diagnosa_keperawatan);
                        $('#pengkajian-dokter-rajal #rencana_tindak_lanjut').val(response
                            .rencana_tindak_lanjut);
                        $('#pengkajian-dokter-rajal #rencana_tindak_lanjut').val(response
                            .rencana_tindak_lanjut);
                        $('#pengkajian-dokter-rajal #awal_tgl_rajal').val(response
                            .awal_tgl_rajal);
                        $('#pengkajian-dokter-rajal #awal_jam_rajal').val(response
                            .awal_jam_rajal);
                        $('#pengkajian-dokter-rajal #awal_keluhan').text(response
                            .awal_keluhan);
                        $('#pengkajian-dokter-rajal #awal_riwayat_penyakit_dahulu').text(
                            response
                            .awal_riwayat_penyakit_dahulu);
                        $('#pengkajian-dokter-rajal #awal_riwayat_penyakit_keluarga').text(
                            response
                            .awal_riwayat_penyakit_keluarga);
                        $('#pengkajian-dokter-rajal #awal_pemeriksaan_fisik').text(response
                            .awal_pemeriksaan_fisik);
                        $('#pengkajian-dokter-rajal #awal_pemeriksaan_penunjang').text(
                            response
                            .awal_pemeriksaan_penunjang);
                        $('#pengkajian-dokter-rajal #awal_diagnosa_kerja').text(response
                            .awal_diagnosa_kerja);
                        $('#pengkajian-dokter-rajal #awal_diagnosa_banding').text(response
                            .awal_diagnosa_banding);
                        $('#pengkajian-dokter-rajal #awal_terapi_tindakan').text(response
                            .awal_terapi_tindakan);
                        $('#pengkajian-dokter-rajal #awal_riwayat_penyakit_sekarang').text(
                            response
                            .awal_riwayat_penyakit_sekarang);

                        const asesmenArray = JSON.parse(response.asesmen_dilakukan_melalui);
                        const awal_edukasi = JSON.parse(response.awal_edukasi);
                        const awal_rencana_tindak_lanjut = JSON.parse(response
                            .awal_rencana_tindak_lanjut);
                        const awal_evaluasi_penyakit = JSON.parse(response
                            .awal_evaluasi_penyakit);

                        // Memeriksa setiap checkbox dan menandai yang sesuai
                        $.each(asesmenArray, function(index, value) {
                            $(`input[name="asesmen_dilakukan_melalui[]"][value="${value}"]`)
                                .prop('checked', true);
                        });
                        $.each(awal_edukasi, function(index, value) {
                            $(`input[name="awal_edukasi[]"][value="${value}"]`)
                                .prop('checked', true);
                        });
                        $.each(awal_evaluasi_penyakit, function(index, value) {
                            $(`input[name="awal_evaluasi_penyakit[]"][value="${value}"]`)
                                .prop('checked', true);
                        });
                        $.each(awal_rencana_tindak_lanjut, function(index, value) {
                            $(`input[name="awal_rencana_tindak_lanjut[]"][value="${value}"]`)
                                .prop('checked', true);
                        });

                        if (response.awal_riwayat_alergi_obat === 1) {
                            $('#pengkajian-dokter-rajal #ada').prop('checked', true);
                            $('#alergiInput').prop('disabled', false); // Enable input field
                            $('#alergiInput').val(response
                                .awal_riwayat_alergi_obat_lain
                            ); // Isi dengan data alergi lain
                        } else {
                            $('#pengkajian-dokter-rajal #tidak_ada').prop('checked', true);
                            $('#alergiInput').val('').prop('disabled',
                                true); // Kosongkan dan disable
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $('#tambah-data').modal('hide');
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = '';

                        $.each(errors, function(key, value) {
                            errorMessages += value +
                                '\n';
                        });

                        // $('#modal-tambah-grup-tindakan').modal('hide');
                        console.log('Terjadi kesalahan:\n' +
                            errorMessages);
                    } else {
                        console.log(error);
                    }
                }
            });
        });
    });
</script>
