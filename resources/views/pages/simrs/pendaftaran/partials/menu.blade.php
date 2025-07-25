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
                    <a class="dropdown-item click-menu" data-toggle="tab" data-action="dokter-resume-medis-rajal"
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
                    <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian
                        Resep</a>
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
{{-- Select 2 --}}
<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.click-menu').click(function() {
            const no_rm = "{{ $registration->patient->medical_record_number }}";
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
            // console.log(`/api/simrs/erm/${menu}/${type}/${registration_number}/get`);
            const token = "{{ csrf_token() }}";



            $.ajax({
                type: "GET",
                url: `/api/simrs/erm/${menu}/${type}/${registration_number}/get`,
                data: {
                    no_rm: no_rm,
                    token: token
                },
                success: function(response) {
                    if (menu == 'dokter-pengkajian') {
                        handleDokterPengkajian(response);
                    } else if (menu == 'perawat-pengkajian') {
                        handlePerawatPengkajian(response);
                    } else if (menu == 'dokter-cppt') {
                        console.log(true);
                    } else if (menu == 'dokter-resume-medis-rajal') {
                        handleResumeMedisRajal(response);
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

            function loadCPPTData(response) {
                $('#list_soap').empty();

                // Iterasi setiap data dan tambahkan ke dalam tabel
                $.each(response, function(index, data) {
                    var row = `
                        <tr>
                            <td class="text-center">
                                <div class="deep-purple-text">${data.created_at}<br>
                                    <span class="green-text" style="font-weight:400;">${data.tipe_rawat}</span><br>
                                    <b style="font-weight: 400;">Dokter ID: ${data.doctor_id}</b><br>
                                    <div class="input-oleh deep-orange-text">Input oleh: ${data.user_id}</div>
                                    <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                    <div>
                                        <img src="http://192.168.1.253/real/include/images/ttd_blank.png" width="200px;" height="100px;">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <table width="100%" class="table-soap nurse">
                                    <tbody>
                                        <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
                                        <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
                                        <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
                                        <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment}</td></tr>
                                        <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning}</td></tr>
                                        <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
                                <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
                                <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
                                <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
                            </td>
                        </tr>
                    `;
                    // Tambahkan ke dalam tabel
                    $('#list_soap').append(row);
                });
            }

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
                $('#pengkajian-dokter-rajal #diagnosa_keperawatan').val(response.diagnosa_keperawatan)
                    .trigger('change');
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
                $('#pengkajian-nurse-rajal-form #diagnosa-keperawatan').val(response
                    .diagnosa_keperawatan).trigger('change');
                $('#pengkajian-nurse-rajal-form #rencana-tindak-lanjut').val(response
                    .rencana_tindak_lanjut).trigger('change');
                // Assuming 'response' is the object retrieved from the responsebase
                if (response.alergi_obat === "Ya") {
                    $('#pengkajian-nurse-rajal-form #ket_alergi_obat').val(response
                        .ket_alergi_obat);
                    $('#pengkajian-nurse-rajal-form #alergi_obat1').prop(
                        'checked', true);
                    $('#pengkajian-nurse-rajal-form #reaksi_alergi_obat').val(response
                        .reaksi_alergi_obat);
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
                    $('#pengkajian-nurse-rajal-form #reaksi_alergi_makanan').val(response
                        .reaksi_alergi_makanan);
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
                    $('#pengkajian-nurse-rajal-form #reaksi_alergi_lainnya').val(response
                        .reaksi_alergi_lainnya);
                } else if (response.alergi_lainnya === "Tidak") {
                    $('#pengkajian-nurse-rajal-form #alergi_lainnya2').prop(
                        'checked', true);
                }

                // Dipasang Gelang
                if (response.gelang === 1) {
                    $('#pengkajian-nurse-rajal-form #gelang1').prop(
                        'checked', true);
                }

                // Skor Nyeri
                $('#pengkajian-nurse-rajal-form #skor_nyeri').val(response.skor_nyeri);
                $('#pengkajian-nurse-rajal-form #provokatif').val(response.provokatif);
                $('#pengkajian-nurse-rajal-form #quality').val(response.quality);
                $('#pengkajian-nurse-rajal-form #region').val(response.region);
                $('#pengkajian-nurse-rajal-form #time').val(response.time);

                $('#pengkajian-nurse-rajal-form #nyeri').val(response
                    .nyeri).trigger('change');
                $('#pengkajian-nurse-rajal-form #nyeri_hilang').val(response.nyeri_hilang);

                // Skrining Gizi
                $('#pengkajian-nurse-rajal-form #penurunan_bb').val(response
                    .penurunan_bb).trigger('change');
                $('#pengkajian-nurse-rajal-form #asupan_makan').val(response
                    .asupan_makan).trigger('change');

                // RIWAYAT PSIKOSOSIAL, SPIRITUAL & KEPERCAYAAN
                $('#pengkajian-nurse-rajal-form #status_psikologis').val(response
                    .status_psikologis).trigger('change');
                $('#pengkajian-nurse-rajal-form #status_spiritual').val(response
                    .status_spiritual).trigger('change');
                $('#pengkajian-nurse-rajal-form #masalah_prilaku').val(response.masalah_prilaku);
                $('#pengkajian-nurse-rajal-form #kekerasan_dialami').val(response.kekerasan_dialami);
                $('#pengkajian-nurse-rajal-form #hub_dengan_keluarga').val(response
                    .hub_dengan_keluarga);
                $('#pengkajian-nurse-rajal-form #tempat_tinggal').val(response.tempat_tinggal);
                $('#pengkajian-nurse-rajal-form #kerabat_dihub').val(response.kerabat_dihub);
                $('#pengkajian-nurse-rajal-form #no_kontak_kerabat').val(response.no_kontak_kerabat);
                $('#pengkajian-nurse-rajal-form #status_perkawinan').val(response.status_perkawinan);
                $('#pengkajian-nurse-rajal-form #pekerjaan').val(response.pekerjaan);
                $('#pengkajian-nurse-rajal-form #penghasilan').val(response
                    .penghasilan).trigger('change');
                $('#pengkajian-nurse-rajal-form #pendidikan').val(response.pendidikan);

                // Kondisi Khusus
                if (response.kondisi_khusus1 === "Anak usia 1-5 tahun") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus1').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus2 === "Lansia > 60 tahun") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus2').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus3 === "Penyakit kronis dengan komplikasi") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus3').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus4 === "Kanker stadium III/IV") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus4').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus5 === "HIV/AIDS") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus5').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus6 === "TB") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus6').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus7 === "Bedah mayor degestif") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus7').prop(
                        'checked', true);
                }
                if (response.kondisi_khusus8 === "Luka bakar > 20%") {
                    $('#pengkajian-nurse-rajal-form #kondisi_khusus8').prop(
                        'checked', true);
                }

                // Imunisasi Dasar
                if (response.imunisasi_dasar1 === "BCG") {
                    $('#pengkajian-nurse-rajal-form #imunisasi_dasar1').prop(
                        'checked', true);
                }
                if (response.imunisasi_dasar2 === "DPT") {
                    $('#pengkajian-nurse-rajal-form #imunisasi_dasar2').prop(
                        'checked', true);
                }
                if (response.imunisasi_dasar3 === "Hepatitis B") {
                    $('#pengkajian-nurse-rajal-form #imunisasi_dasar3').prop(
                        'checked', true);
                }
                if (response.imunisasi_dasar4 === "Polio") {
                    $('#pengkajian-nurse-rajal-form #imunisasi_dasar4').prop(
                        'checked', true);
                }
                if (response.imunisasi_dasar5 === "Campak") {
                    $('#pengkajian-nurse-rajal-form #imunisasi_dasar5').prop(
                        'checked', true);
                }

                // Resiko Jatuh
                if (response.resiko_jatuh1 === "Tidak seimbang/sempoyongan/limbung") {
                    $('#pengkajian-nurse-rajal-form #resiko_jatuh1').prop(
                        'checked', true);
                }
                if (response.resiko_jatuh2 === "Alat bantu: kruk,kursi roda/dibantu") {
                    $('#pengkajian-nurse-rajal-form #resiko_jatuh2').prop(
                        'checked', true);
                }
                if (response.resiko_jatuh3 === "Pegang pinggiran meja/kursi/alat bantu untuk duduk") {
                    $('#pengkajian-nurse-rajal-form #resiko_jatuh3').prop(
                        'checked', true);
                }

                // Hambatan Belajar
                if (response.hambatan_belajar1 === "Pendengaran") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar1').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar2 === "Penglihatan") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar2').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar3 === "Kognitif") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar3').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar4 === "Fisik") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar4').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar5 === "Budaya") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar5').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar6 === "Agama") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar6').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar7 === "Emosi") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar7').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar8 === "Bahasa") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar8').prop(
                        'checked', true);
                }
                if (response.hambatan_belajar9 === "Tidak ada Hamabatan") {
                    $('#pengkajian-nurse-rajal-form #hambatan_belajar9').prop(
                        'checked', true);
                }
                $('#pengkajian-nurse-rajal-form #hambatan_lainnya').val(response.hambatan_lainnya);
                $('#pengkajian-nurse-rajal-form #kebutuhan_penerjemah').val(response
                    .kebutuhan_penerjemah);

                // Kebutuhan Belajar
                if (response.kebuthan_pembelajaran1 === "Diagnosa managemen") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran1').prop(
                        'checked', true);
                }
                if (response.kebuthan_pembelajaran2 === "Obat-obatan") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran2').prop(
                        'checked', true);
                }
                if (response.kebuthan_pembelajaran3 === "Perawatan luka") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran3').prop(
                        'checked', true);
                }
                if (response.kebuthan_pembelajaran4 === "Rehabilitasi") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran4').prop(
                        'checked', true);
                }
                if (response.kebuthan_pembelajaran5 === "Manajemen nyeri") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran5').prop(
                        'checked', true);
                }
                if (response.kebuthan_pembelajaran6 === "Diet & nutrisi") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran6').prop(
                        'checked', true);
                }
                if (response.kebuthan_pembelajaran7 === "Tidak ada Hamabatan") {
                    $('#pengkajian-nurse-rajal-form #kebuthan_pembelajaran7').prop(
                        'checked', true);
                }
                $('#pengkajian-nurse-rajal-form #pembelajaran_lainnya').val(response
                    .pembelajaran_lainnya);

                // Assesment Fungsional
                if (response.sensorik_penglihatan === "Normal") {
                    $('#pengkajian-nurse-rajal-form #sensorik_penglihatan1').prop(
                        'checked', true);
                }
                if (response.sensorik_penglihatan === "Kabur") {
                    $('#pengkajian-nurse-rajal-form #sensorik_penglihatan2').prop(
                        'checked', true);
                }
                if (response.sensorik_penglihatan === "Kaca Mata") {
                    $('#pengkajian-nurse-rajal-form #sensorik_penglihatan3').prop(
                        'checked', true);
                }
                if (response.sensorik_penglihatan === "Lensa Kontak") {
                    $('#pengkajian-nurse-rajal-form #sensorik_penglihatan4').prop(
                        'checked', true);
                }
                if (response.sensorik_penciuman === "Normal") {
                    $('#pengkajian-nurse-rajal-form #sensorik_penciuman1').prop(
                        'checked', true);
                }
                if (response.sensorik_penciuman === "Tidak") {
                    $('#pengkajian-nurse-rajal-form #sensorik_penciuman2').prop(
                        'checked', true);
                }
                if (response.sensorik_pendengaran === "Normal") {
                    $('#pengkajian-nurse-rajal-form #sensorik_pendengaran1').prop(
                        'checked', true);
                }
                if (response.sensorik_pendengaran === "Tuli Ka / Ki") {
                    $('#pengkajian-nurse-rajal-form #sensorik_pendengaran2').prop(
                        'checked', true);
                }
                if (response.sensorik_pendengaran === "Ada alat bantu dengar ka/ki") {
                    $('#pengkajian-nurse-rajal-form #sensorik_pendengaran3').prop(
                        'checked', true);
                }

                // Kognitif
                if (response.kognitif === "Normal") {
                    $('#pengkajian-nurse-rajal-form #kognitif1').prop(
                        'checked', true);
                }
                if (response.kognitif === "Bingung") {
                    $('#pengkajian-nurse-rajal-form #kognitif2').prop(
                        'checked', true);
                }
                if (response.kognitif === "Pelupa") {
                    $('#pengkajian-nurse-rajal-form #kognitif3').prop(
                        'checked', true);
                }
                if (response.kognitif === "Tidak Dapat dimengerti") {
                    $('#pengkajian-nurse-rajal-form #kognitif4').prop(
                        'checked', true);
                }

                // Motorik
                if (response.motorik_aktifitas === "Mandiri") {
                    $('#pengkajian-nurse-rajal-form #motorik_aktifitas1').prop(
                        'checked', true);
                }
                if (response.motorik_aktifitas === "Bantuan Minimal") {
                    $('#pengkajian-nurse-rajal-form #motorik_aktifitas2').prop(
                        'checked', true);
                }
                if (response.motorik_aktifitas === "Bantuan Ketergantungan Total") {
                    $('#pengkajian-nurse-rajal-form #motorik_aktifitas3').prop(
                        'checked', true);
                }
                if (response.motorik_berjalan === "Tidak Ada kesulitan") {
                    $('#pengkajian-nurse-rajal-form #motorik_berjalan1').prop(
                        'checked', true);
                }
                if (response.motorik_berjalan === "Perlu Bantuan") {
                    $('#pengkajian-nurse-rajal-form #motorik_berjalan2').prop(
                        'checked', true);
                }
                if (response.motorik_berjalan === "Sering Jatuh") {
                    $('#pengkajian-nurse-rajal-form #motorik_berjalan3').prop(
                        'checked', true);
                }
                if (response.motorik_berjalan === "Kelumpuhan") {
                    $('#pengkajian-nurse-rajal-form #motorik_berjalan4').prop(
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

            function handleResumeMedisRajal(response) {
                $('#resume-medis-rajal-form #nama_pasien').val(response.nama_pasien);
                $('#resume-medis-rajal-form #medical_record_number').val(response
                    .medical_record_number);
                $('#resume-medis-rajal-form #tgl_lahir').val(response
                    .tgl_lahir);
                $('#resume-medis-rajal-form #jenis_kelamin').val(response
                    .jenis_kelamin);
                let tgl_masuk = response.tgl_masuk; // Misalnya '2024-10-23 07:00:00'

                // Ganti spasi dengan 'T' dan potong bagian detik (SS)
                let formatted_tgl_masuk = tgl_masuk.replace(' ', 'T').slice(0, 16);

                // Set nilai ke input datetime-local
                $('#resume-medis-rajal-form #tgl_masuk').val(formatted_tgl_masuk);
                $('#resume-medis-rajal-form #anamnesa').text(response
                    .anamnesa);
                $('#resume-medis-rajal-form #diagnosa_utama').text(response
                    .diagnosa_utama);
                $('#resume-medis-rajal-form #diagnosa_tambahan').text(response
                    .diagnosa_tambahan);
                $('#resume-medis-rajal-form #tindakan_utama').text(response
                    .tindakan_utama);
                $('#resume-medis-rajal-form #tindakan_tambahan').text(response
                    .tindakan_tambahan);

                // Untuk alasan_masuk_rs
                $('#resume-medis-rajal-form input[name="alasan_masuk_rs"][value="' + response
                    .alasan_masuk_rs + '"]').prop(
                    'checked', true);

                // Untuk cara_keluar
                $('#resume-medis-rajal-form input[name="cara_keluar"][value="' + response.cara_keluar +
                    '"]').prop('checked',
                    true);

                if (response.is_final == 0 && response.is_final != null) {
                    $('#alert-resume-medis-rajal').html(`
                        <div class="alert alert-warning" role="alert">
                            <strong>Resume masih save draft! harap save final jika sudah fix!</strong>
                        </div>
                    `);
                } else if (response.is_final == 1 && response.is_final != null) {
                    $('#alert-resume-medis-rajal').html(`
                        <div class="alert alert-primary" role="alert">
                            <strong>Resume medis sudah di save final!</strong>
                        </div>
                    `);

                }

                if (response.is_ttd == 1) {
                    const ttd = "{{ auth()->user()->employee->ttd }}"
                    const path = "/api/simrs/signature/" + ttd + "?token=" + token;
                    $('#resume-medis-rajal-form .btn-ttd-resume-medis').hide();
                    $('#signature-display').attr('src', path).show();
                }

                $('#resume-medis-rajal-form #rmj-button-wrapper').addClass('hidden');
            }

        });
    });
</script>
