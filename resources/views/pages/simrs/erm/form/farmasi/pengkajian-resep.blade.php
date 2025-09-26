@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <div class="row">
                    <div class="col-md-12">

                        <table class="table">
                            <tbody>
                                <tr style="background-color: #ccc; font-weight: bold; text-align: center;">
                                    <td colspan="2">TELAAH RESEP</td>
                                    <td>Ada</td>
                                    <td>Tidak</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Kejelasan tulisan R/</td>
                                    <td align="center"><input type="radio" name="kejelasan" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="kejelasan" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_kejelasan"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Tepat Pasien</td>
                                    <td align="center"><input type="radio" name="tepat_pasien" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="tepat_pasien" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_tepat_pasien"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Tepat indikasi</td>
                                    <td align="center"><input type="radio" name="tepat_indikasi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="tepat_indikasi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_tepat_indikasi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Tepat obat</td>
                                    <td align="center"><input type="radio" name="tepat_obat" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="tepat_obat" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_tepat_obat"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Tepat rute/sedian</td>
                                    <td align="center"><input type="radio" name="tepat_rute" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="tepat_rute" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_tepat_rute"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Tepat waktu / frekuensi</td>
                                    <td align="center"><input type="radio" name="tepat_waktu" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="tepat_waktu" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_tepat_waktu"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Dupliksi</td>
                                    <td align="center"><input type="radio" name="duplikasi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="duplikasi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_duplikasi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Alergi</td>
                                    <td align="center"><input type="radio" name="alergi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="alergi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_alergi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Interaksi</td>
                                    <td align="center"><input type="radio" name="interaksi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="interaksi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_interaksi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Kontra indikasi</td>
                                    <td align="center"><input type="radio" name="kontraindikasi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="kontraindikasi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_kontraindikasi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr style="background-color: #ccc; font-weight: bold; text-align: center;">
                                    <td colspan="2">ADMINISTRASI</td>
                                    <td>Ada</td>
                                    <td>Tidak</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nama dokter</td>
                                    <td align="center"><input type="radio" name="nama_dokter" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="nama_dokter" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_nama_dokter"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">SIP</td>
                                    <td align="center"><input type="radio" name="sip" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="sip" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_sip" style="width: 300px;"
                                            value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Paraf dokter</td>
                                    <td align="center"><input type="radio" name="paraf_dokter" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="paraf_dokter" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_paraf_dokter"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Tanggal resep</td>
                                    <td align="center"><input type="radio" name="tgl_resep" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="tgl_resep" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_tgl_resep"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Kelengkapan identitas pasien</td>
                                    <td align="center"><input type="radio" name="kelengkapan_identitas" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="kelengkapan_identitas" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_kelengkapan_identitas"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr style="background-color: #ccc; font-weight: bold; text-align: center;">
                                    <td colspan="2">FARMASETIK</td>
                                    <td>Ada</td>
                                    <td>Tidak</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Obat sesuai R/</td>
                                    <td align="center"><input type="radio" name="obat_sesuai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="obat_sesuai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_obat_sesuai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Jumlah Sesaui R/</td>
                                    <td align="center"><input type="radio" name="jumlah_sesuai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="jumlah_sesuai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_jumlah_sesuai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Dosis sesuai R/</td>
                                    <td align="center"><input type="radio" name="dosis_sesuai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="dosis_sesuai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_dosis_sesuai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Sediaan sesuai R/</td>
                                    <td align="center"><input type="radio" name="sediaan_sesuai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="sediaan_sesuai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_sediaan_sesuai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Waktu &amp; frekuensi pemberian sesuai R/</td>
                                    <td align="center"><input type="radio" name="waktu_sesuai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="waktu_sesuai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_waktu_sesuai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Ket: kelengkapan identitas pasien diantaranya adalah: nama, no
                                        RM, gender, umur, jenis pasien, ruang/poli</td>
                                    <td colspan="2" align="center">Paraf petugas telaah<br><br><br><br></td>
                                </tr>
                                <tr style="background-color: #ccc; font-weight: bold; text-align: center;">
                                    <td colspan="2">PELAYANAN FARMASI</td>
                                    <td>Ada</td>
                                    <td>Tidak</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Penelusuran riwayat pengunaan obat</td>
                                    <td align="center"><input type="radio" name="penelusuran_riwayat" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="penelusuran_riwayat" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_penelusuran_riwayat"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Rekonsiliasi obat</td>
                                    <td align="center"><input type="radio" name="rekonsiliasi_obat" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="rekonsiliasi_obat" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_rekonsiliasi_obat"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Pelayanan Informasi Obat (PIO)</td>
                                    <td align="center"><input type="radio" name="pio" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="pio" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_pio" style="width: 300px;"
                                            value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Konseling</td>
                                    <td align="center"><input type="radio" name="konseling" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="konseling" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_konseling"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Visite</td>
                                    <td align="center"><input type="radio" name="visite" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="visite" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_visite"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Pemantauan Terapi Obat (PTO)</td>
                                    <td align="center"><input type="radio" name="pto" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="pto" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_pto" style="width: 300px;"
                                            value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Monitoring Efek Samping Obat (MESO)</td>
                                    <td align="center"><input type="radio" name="meso" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="meso" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_meso"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Evaluasi Pengunaan Obat (EPO)</td>
                                    <td align="center"><input type="radio" name="epo" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="epo" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_epo" style="width: 300px;"
                                            value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Dispensing sediaan steril</td>
                                    <td align="center"><input type="radio" name="dispensing_sediaan" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="dispensing_sediaan" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_dispensing_sediaan"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Pemantauan Kadar Obat dalam Darah (PKOD)</td>
                                    <td align="center"><input type="radio" name="pkod" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="pkod" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="keterangan_pkod"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Dokter</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input type="text" name="nama_dokter_dpjp" style="width: 300px;"></td>
                                </tr>
                                <tr style="background-color: #ccc; font-weight: bold; text-align: center;">
                                    <td colspan="2">VERIFIKASI RESEP (7 BENAR)</td>
                                    <td>Ya</td>
                                    <td>Tidak</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr>
                                    <td colspan="2">BENAR PASIEN</td>
                                    <td align="center"><input type="radio" name="benar_pasien" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_pasien" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_pasien"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">BENAR INDIKASI</td>
                                    <td align="center"><input type="radio" name="benar_indikasi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_indikasi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_indikasi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">BENAR OBAT</td>
                                    <td align="center"><input type="radio" name="benar_obat" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_obat" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_obat" style="width: 300px;"
                                            value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">BENAR DOSIS</td>
                                    <td align="center"><input type="radio" name="benar_dosis" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_dosis" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_dosis"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">BENAR CARA PAKAI</td>
                                    <td align="center"><input type="radio" name="benar_cara_pakai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_cara_pakai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_cara_pakai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>

                                <tr>
                                    <td colspan="2">BENAR WAKTU PAKAI</td>
                                    <td align="center"><input type="radio" name="benar_waktu_pakai" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_waktu_pakai" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_waktu_pakai"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>

                                <tr>
                                    <td colspan="2">BENAR DOKUMENTASI</td>
                                    <td align="center"><input type="radio" name="benar_dokumentasi" value="t"
                                            id="check_cara"></td>
                                    <td align="center"><input type="radio" name="benar_dokumentasi" value="f"
                                            id="check_cara"></td>
                                    <td align="center"><input type="text" name="ket_benar_dokumentasi"
                                            style="width: 300px;" value="" id="check_cara"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {

            $('#cppt_doctor_id').val("{{ $registration->doctor_id }}")
            $('.btnAdd').click(function() {
                $('#add_soap').collapse('show');
            });

            $('#tutup').on('click', function() {
                $('#add_soap').collapse('hide');

                $('.btnAdd').attr('aria-expanded', 'false');
                $('.btnAdd').addClass('collapsed');
            });

            // Saat tombol Save Final diklik
            $('#bsSOAP').on('click', function() {
                submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
            });

            function loadCPPTData() {
                $.ajax({
                    // url: '{{-- route('cppt.get') --}}', // Mengambil route Laravel
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Bersihkan tabel
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
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

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
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            //     $('#doctor_id').select2({
            //         placeholder: 'Pilih Dokter',
            //     });

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
