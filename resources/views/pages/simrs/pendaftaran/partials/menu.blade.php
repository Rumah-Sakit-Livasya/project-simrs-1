<div class="row menu-detail-regist">
    <div class="col-md-12">
        <ul class="nav nav-tabs px-3 py-2" role="tablist" style="border-top: 1px solid #dddddd !important;">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">PERAWAT</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item click-menu" data-toggle="tab" href="#pengkajian-perawat-rajal"
                        role="tab" data-regist="{{ $registration->registration_number }}" data-type="rawat-jalan"
                        data-action="perawat-pengkajian">Pengkajian</a>
                    <a class="dropdown-item" href="#">CPPT</a>
                    <a class="dropdown-item" data-toggle="tab" href="#transfer-pasien-antar-ruangan"
                        role="tab">Transfer
                        Pasien Antar Ruangan</a>
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
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Form
                        Rekonsoliasi
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

            const targetTab = $(this).attr('href');

            // Menghapus class 'active' dari semua dropdown-item dan tab
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').removeClass('active');
            $('.dropdown-item').removeClass('active');
            $('.tab-pane').removeClass('show active');

            // Menambahkan class 'active' pada item yang diklik dan menampilkan tab terkait
            $(this).closest('.dropdown-menu').prev('.dropdown-toggle').addClass('active');
            $(this).parent().addClass('hide');
            $(this).addClass('active');
            $(targetTab).addClass('show active');

            let menu = $(this).attr('data-action');
            let type = $(this).attr('data-type');
            let registration_number = $(this).attr('data-regist');
            console.log(`/api/simrs/erm/${menu}/${type}/${registration_number}/get`);


            $.ajax({
                type: "GET",
                url: `/api/simrs/erm/${menu}/${type}/${registration_number}/get`,
                success: function(response) {
                    if (menu == 'dokter-pengkajian') {
                        handleDokterPengkajian(response);
                    } else if (menu == 'dokter-cppt') {
                        console.log(true);
                    } else if (menu == 'perawat-pengkajian') {
                        handlePerawatPengkajian(response);
                    }
                },
                error: function(xhr, status, error) {
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

            function handleDokterPengkajian(response) {
                $('#pengkajian-dokter-rajal #registration_id').val(response.registration_id);
                $('#pengkajian-dokter-rajal #pr').val(response.pr);
                $('#pengkajian-dokter-rajal #rr').val(response.rr);
                $('#pengkajian-dokter-rajal #body_height').val(response.body_height);
                $('#pengkajian-dokter-rajal #body_weight').val(response.body_weight);
                $('#pengkajian-dokter-rajal #bp').val(response.bp);
                $('#pengkajian-dokter-rajal #temperatur').val(response.temperatur);
                $('#pengkajian-dokter-rajal #bmi').val(response.bmi);
                $('#pengkajian-dokter-rajal #kat_bmi').val(response.kat_bmi);
                $('#pengkajian-dokter-rajal #sp02').val(response.sp02);
                $('#pengkajian-dokter-rajal #diagnosa_keperawatan').val(response.diagnosa_keperawatan);
                $('#pengkajian-dokter-rajal #rencana_tindak_lanjut').val(response
                    .rencana_tindak_lanjut);
                $('#pengkajian-dokter-rajal #awal_tgl_rajal').val(response.awal_tgl_rajal);
                $('#pengkajian-dokter-rajal #awal_jam_rajal').val(response.awal_jam_rajal);
                $('#pengkajian-dokter-rajal #awal_keluhan').text(response.awal_keluhan);
                $('#pengkajian-dokter-rajal #awal_riwayat_penyakit_dahulu').text(response
                    .awal_riwayat_penyakit_dahulu);
                $('#pengkajian-dokter-rajal #awal_riwayat_penyakit_keluarga').text(response
                    .awal_riwayat_penyakit_keluarga);
                $('#pengkajian-dokter-rajal #awal_pemeriksaan_fisik').text(response
                    .awal_pemeriksaan_fisik);
                $('#pengkajian-dokter-rajal #awal_pemeriksaan_penunjang').text(response
                    .awal_pemeriksaan_penunjang);
                $('#pengkajian-dokter-rajal #awal_diagnosa_kerja').text(response.awal_diagnosa_kerja);
                $('#pengkajian-dokter-rajal #awal_diagnosa_banding').text(response
                    .awal_diagnosa_banding);
                $('#pengkajian-dokter-rajal #awal_terapi_tindakan').text(response.awal_terapi_tindakan);
                $('#pengkajian-dokter-rajal #awal_riwayat_penyakit_sekarang').text(response
                    .awal_riwayat_penyakit_sekarang);

                const asesmenArray = JSON.parse(response.asesmen_dilakukan_melalui);
                const awal_edukasi = JSON.parse(response.awal_edukasi);
                const awal_rencana_tindak_lanjut = JSON.parse(response.awal_rencana_tindak_lanjut);
                const awal_evaluasi_penyakit = JSON.parse(response.awal_evaluasi_penyakit);

                // Menandai checkbox yang sesuai
                $.each(asesmenArray, function(index, value) {
                    $(`input[name="asesmen_dilakukan_melalui[]"][value="${value}"]`).prop(
                        'checked', true);
                });
                $.each(awal_edukasi, function(index, value) {
                    $(`input[name="awal_edukasi[]"][value="${value}"]`).prop('checked', true);
                });
                $.each(awal_evaluasi_penyakit, function(index, value) {
                    $(`input[name="awal_evaluasi_penyakit[]"][value="${value}"]`).prop(
                        'checked', true);
                });
                $.each(awal_rencana_tindak_lanjut, function(index, value) {
                    $(`input[name="awal_rencana_tindak_lanjut[]"][value="${value}"]`).prop(
                        'checked', true);
                });

                // Menangani riwayat alergi
                if (response.awal_riwayat_alergi_obat === 1) {
                    $('#pengkajian-dokter-rajal #ada').prop('checked', true);
                    $('#alergiInput').prop('disabled', false).val(response
                        .awal_riwayat_alergi_obat_lain);
                } else {
                    $('#pengkajian-dokter-rajal #tidak_ada').prop('checked', true);
                    $('#alergiInput').val('').prop('disabled', true);
                }

                // Menampilkan alert jika masih dalam draft
                if (response.is_final == 0) {
                    $('#alert-pengkajian').html(`
                        <div class="alert alert-warning" role="alert">
                            <strong>Pegnkajian masih save draft! harap save final jika sudah fix!</strong>
                        </div>
                    `);
                }
            }

            function handlePerawatPengkajian(response) {
                $('#pengkajian-nurse-rajal-form #tgl_masuk').val(response.tgl_masuk);
                $('#pengkajian-nurse-rajal-form #jam_masuk').val(response.jam_masuk);
                $('#pengkajian-nurse-rajal-form #tgl_dilayani').val(response
                    .tgl_dilayani);
                $('#pengkajian-nurse-rajal-form #jam_dilayani').val(response
                    .jam_dilayani);
                $('#pengkajian-nurse-rajal-form #keluhan_utama').val(response
                    .keluhan_utama);
                $('#pengkajian-nurse-rajal-form #pr').val(response.pr);
                $('#pengkajian-nurse-rajal-form #rr').val(response.rr);
                $('#pengkajian-nurse-rajal-form #bp').val(response.bp);
                $('#pengkajian-nurse-rajal-form #temperatur').val(response
                    .temperatur);
                $('#pengkajian-nurse-rajal-form #body_height').val(response
                    .body_height);
                $('#pengkajian-nurse-rajal-form #body_weight').val(response
                    .body_weight);
                $('#pengkajian-nurse-rajal-form #bmi').val(response.bmi);
                $('#pengkajian-nurse-rajal-form #kat_bmi').val(response.kat_bmi);
                $('#pengkajian-nurse-rajal-form #sp02').val(response.sp02);
                $('#pengkajian-nurse-rajal-form #lingkar_kepala').val(response
                    .lingkar_kepala);
                $('#pengkajian-nurse-rajal-form #lingkar_kepala').val(response
                    .lingkar_kepala);
                // Set the value for Select2 elements
                $('#pengkajian-nurse-rajal-form #diagnosa_keperawatan').val(response
                    .diagnosa_keperawatan).trigger('change');
                $('#pengkajian-nurse-rajal-form #rencana_tindak_lanjut').val(response
                    .rencana_tindak_lanjut).trigger('change');
                // Assuming 'response' is the object retrieved from the responsebase
                if (response.alergi_obat === "Ya") {
                    $('#pengkajian-nurse-rajal-form #ket_alergi_obat').val(response
                        .ket_alergi_obat);
                    $('#pengkajian-nurse-rajal-form #alergi_obat1').prop(
                        'checked', true);
                } else if (response.alergi_obat === "Tidak") {
                    $('#pengkajian-nurse-rajal-form #alergi_obat2').prop(
                        'checked', true);
                }
                if (response.alergi_makanan === "Ya") {
                    $('#pengkajian-nurse-rajal-form #ket_alergi_makanan').val(
                        response
                        .ket_alergi_makanan);
                    $('#pengkajian-nurse-rajal-form #alergi_makanan1').prop(
                        'checked', true);
                } else if (response.alergi_makanan === "Tidak") {
                    $('#pengkajian-nurse-rajal-form #alergi_makanan2').prop(
                        'checked', true);
                }
                if (response.alergi_lainnya === "Ya") {
                    $('#pengkajian-nurse-rajal-form #ket_alergi_lainnya').val(
                        response
                        .ket_alergi_lainnya);
                    $('#pengkajian-nurse-rajal-form #alergi_lainnya1').prop(
                        'checked', true);
                } else if (response.alergi_lainnya === "Tidak") {
                    $('#pengkajian-nurse-rajal-form #alergi_lainnya2').prop(
                        'checked', true);
                }

                // Menampilkan alert jika masih dalam draft
                if (response.is_final == 0) {
                    $('#alert-pengkajian').html(`
                        <div class="alert alert-warning" role="alert">
                            <strong>Pegnkajian masih save draft! harap save final jika sudah fix!</strong>
                        </div>
                    `);
                }
            }

        });
    });
</script>
