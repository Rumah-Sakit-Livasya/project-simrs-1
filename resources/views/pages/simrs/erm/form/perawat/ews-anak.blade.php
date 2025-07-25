@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" autocomplete="off" id="ews-anak">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">EARLY WARNING SCORING SYSTEM (ANAK)</h2>
                    </header>
                    <header class="text-success">
                        <h4 class="mt-5 font-weight-bold text-center">MASUK RUMAH SAKIT</h4>
                    </header>
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tgl" class="text-primary d-block text-center">Tanggal &amp; jam
                                    masuk</label>
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="date" name="tgl" class="form-control " placeholder="Tanggal"
                                            id="tgl" value="{{ $registration->created_at->format('Y-m-d') }}">
                                        <input type="time" name="jam" class="form-control " placeholder="Jam"
                                            id="jam" value="{{ $registration->created_at->format('h:i:s') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <table class="table table-bordered table-hover m-0">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 2</th>
                                        <th>Skor : 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">KEADAAN UMUM</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="interaksi-biasa">
                                                        <input name="keadaan_umum" id="interaksi-biasa"
                                                            value="Interaksi Biasa" type="radio"
                                                            class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Interaksi Biasa
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="sedikit-mengantuk">
                                                        <input name="keadaan_umum" id="sedikit-mengantuk"
                                                            value="Sedikit mengantuk atau rewel tetapi dapat ditenangkan"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Sedikit mengantuk
                                                            atau rewel tetapi dapat ditenangkan</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="iritabel">
                                                        <input name="keadaan_umum" id="iritabel"
                                                            value="Iritabel, tidak dapat ditenangkan" type="radio"
                                                            class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Iritabel, tidak
                                                            dapat ditenangkan</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="letargi">
                                                        <input name="keadaan_umum" id="letargi"
                                                            value="Letargi, Gelisah, Somnolen, penurunan, respon terhadap nyeri"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Letargi, Gelisah,
                                                            Somnolen, penurunan, respon terhadap nyeri</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">KARDIO VASKULAR </th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="tidak-sianosis">
                                                        <input name="kardio_vaskular" id="tidak-sianosis"
                                                            value="Tidak sianosis, ATAU pengisian kapiler <2 detik"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Tidak sianosis,
                                                            ATAU
                                                            pengisian kapiler < 2 detik </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="tampak-pucat">
                                                        <input name="kardio_vaskular" id="tampak-pucat"
                                                            value="Tampak pucat ATAU pengisian kapiler 2 detik"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Tampak pucat ATAU
                                                            pengisian kapiler 2 detik
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="tampak-sianotik">
                                                        <input name="kardio_vaskular" id="tampak-sianotik"
                                                            value="Tampak sianotik ATAU Pengisian kapiler > 2 detik, ATAU Takikardi > 20x diatas parameter RR sesuai usia"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Tampak sianotik
                                                            ATAU Pengisian kapiler > 2 detik, ATAU Takikardi > 20x diatas
                                                            parameter RR sesuai usia
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="sianotik-motlet">
                                                        <input name="kardio_vaskular" id="sianotik-motlet"
                                                            value="Sianotik dan motlet, ATAU Pengisian kapiler > 5 detik ATAU Takiardi > 30x diatas parameter RR sesuai usia/mt ATAU, Bradikardia (sesuai usia)"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Sianotik dan
                                                            motlet, ATAU Pengisian kapiler > 5 detik ATAU Takiardi > 30x
                                                            diatas parameter RR sesuai usia/mt ATAU, Bradikardia (sesuai
                                                            usia)
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">RESPIRASI</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="normal">
                                                        <input name="respirasi" id="normal"
                                                            value="Respirasi dalam bentuk normal, tidak terdapat retraksi"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Respirasi dalam
                                                            bentuk normal, tidak terdapat retraksi
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="respirasi-10">
                                                        <input name="respirasi" id="respirasi-10"
                                                            value="Respirasi > 10x diatas parameter RR sesuai usia permenit, ATAU menggunakan otot alat bantu napas, ATAU menggunakan FiO2 lebih dari 30% (Pemberian 02 dengan Nasal Kanul 3-5lpm)"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Respirasi > 10x
                                                            diatas parameter RR sesuai usia permenit, ATAU menggunakan otot
                                                            alat bantu napas, ATAU menggunakan FiO2 lebih dari 30%
                                                            (Pemberian 02 dengan Nasal Kanul 3-5lpm)
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="respirasi-20">
                                                        <input name="respirasi" id="respirasi-20"
                                                            value="Respirasi > 20x diatas parameter RR sesuai usia /menit, ATAU ada retraksi, ATAU menggunakan FiO2 lebih dari 40% (Pemberian O2 dengan Simple Mask 4-6lpm)"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Respirasi > 20x
                                                            diatas parameter RR sesuai usia /menit, ATAU ada retraksi, ATAU
                                                            menggunakan FiO2 lebih dari 40% (Pemberian O2 dengan Simple Mask
                                                            4-6lpm)
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="respirasi-30">
                                                        <input name="respirasi" id="respirasi-30"
                                                            value="Respirasi > 30x diatas parameter normal, ATAU >= 5x dibawah RR Sesuai usia per menit dengan retraksi berat ATAU merintih, ATAU Menggunakan FiO2 lebih dari 50% (Pemberian 02 dengan NRM 8-10 lpm)"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Respirasi > 30x
                                                            diatas parameter normal, ATAU >= 5x dibawah RR Sesuai usia per
                                                            menit dengan retraksi berat ATAU merintih, ATAU Menggunakan FiO2
                                                            lebih dari 50% (Pemberian 02 dengan NRM 8-10 lpm)
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Skor Total <br>
                                            <input class="form-control border-0" readonly
                                                style="font-size: 25pt; border-bottom: 2px solid #eaeaea !important"
                                                type="text" name="skor_total" id="score">
                                        </td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="2">Kelompok Usia</th>
                                        <th>Usia</th>
                                        <th>Nadi saat istirahat<br>(kali/menit)</th>
                                        <th>Napas saat istirahat<br>(napas/menit)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2">Neonatus</td>
                                        <td>0 - 1 bulan</td>
                                        <td>100 - 180</td>
                                        <td>40 - 60</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Bayi</td>
                                        <td>1 - 12 bulan</td>
                                        <td>100 - 180</td>
                                        <td>35 - 40</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Balita</td>
                                        <td>12 - 36 bulan</td>
                                        <td>70 - 110</td>
                                        <td>25 - 30</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Pra-Sekolah</td>
                                        <td>4 - 6 tahun</td>
                                        <td>70 - 110</td>
                                        <td>21 - 23</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Sekolah</td>
                                        <td>7 - 12 tahun</td>
                                        <td>70 - 110</td>
                                        <td>19 - 21</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Remaja</td>
                                        <td>13 - 19 tahun</td>
                                        <td>55 - 90</td>
                                        <td>16 - 18</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>SKOR 0 - 2</strong></td>
                                        <td colspan="3">Pasien dalam keadaan stabil, jika
                                            skor 0 lakukan evaluasi rutin tiap 8 jam, jika skor 1 atau 2, lakukan evaluasi
                                            setiap 4 jam, jika diperlukan assessment oleh dokter jaga bangsal</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>SKOR 3 - 4</strong></td>
                                        <td colspan="3">Ada penurunan kondisi pasien,
                                            assessment oleh dokter jaga bangsal, lakukan evaluasi ulang setiap 2 jam atau
                                            lebih cepat, Konsultasi ke DPJP, lakukan terapi sesuai instruksi, jika
                                            diperlukan dipindahkan ke area dengan monitoring yang sesuai</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>SKOR 5 atau lebih</strong></td>
                                        <td colspan="3">Ada perubahan yang
                                            signifikan, lakukan resusitasi, monitoring secara kontinu, aktivitas code blue
                                            kegawatan medis (224) Respon Tim Medis Reaksi Cepat (TMRC) segera, maksimal 10
                                            menit, informasikan dan konsultasikan ke DPJP</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="bg-primary text-white"><strong>HENTI JANTUNG</strong>
                                        </td>
                                        <td colspan="3">Lakukan RJP oleh petugas/tim
                                            primer, aktivitas code blue henti jantung (199), respon Tim Medis Reaksi Cepat
                                            (TMRC), maksimal 5 menit, informasikan dan konsultasikan ke DPJP</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer mr-2"></span> Print
                                    </button>
                                    <div style="width: 33%" class="d-flex justify-content-between">
                                        {{-- <button type="button"
                                            class="btn btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-transfer-pasien-antar-ruangan">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button> --}}
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-transfer-pasien-antar-ruangan">
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
    <script>
        function calculateTotalScore() {
            let totalScore = 0;
            const rows = document.querySelectorAll('tbody tr:not(:last-child)');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    const input = cell.querySelector('input[type="radio"]');
                    if (input && input.checked) {
                        totalScore += index;
                    }
                });
            });
            console.log(totalScore);
            document.getElementById('score').value = totalScore;
        }

        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', calculateTotalScore);
        });

        $(document).ready(function() {
            // Tangani submit form
            $('#ews-anak').on('submit', function(e) {
                e.preventDefault(); // mencegah submit form default
                const form = $(this);
                const formData = form.serialize(); // serialize data form
                $.ajax({
                    url: '/api/simrs/erm/ews-anak',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showSuccessAlert(response.message || 'Data berhasil disimpan');
                        // lakukan tindakan lain jika perlu
                    },
                    error: function(xhr) {
                        let errMsg = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(errMsg);
                    }
                });
            });

            // GET DATA REGISTRATION BY ID
            var registrationId = {{ $registration->id }};
            console.log('Registration ID:', registrationId);

            if (registrationId) {
                $.ajax({
                    url: `/api/simrs/erm/ews-anak/${registrationId}`, // Ganti dengan URL endpoint yang sesuai
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#loading-indicator').show(); // Tampilkan loader
                    },
                    success: function(response) {
                        console.log(response.data); // 👉 Lihat data di console
                        if (response.data) {
                            let data = response.data;

                            // Isi input tanggal dan jam
                            $('input[name="tgl"]').val(data.tgl);
                            $('input[name="jam"]').val(data.jam);
                            $('input[name="skor_total"]').val(data.skor_total);

                            // Pilih radio keadaan_umum sesuai value
                            $('input[name="keadaan_umum"]').each(function() {
                                if ($(this).val() === data.keadaan_umum) {
                                    $(this).prop('checked', true);
                                }
                            });

                            // Pilih radio kardio_vaskular sesuai value
                            $('input[name="kardio_vaskular"]').each(function() {
                                if ($(this).val() === data.kardio_vaskular) {
                                    $(this).prop('checked', true);
                                }
                            });

                            // Pilih radio respirasi sesuai value
                            $('input[name="respirasi"]').each(function() {
                                if ($(this).val() === data.respirasi) {
                                    $(this).prop('checked', true);
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Load data error:', xhr.responseText);
                    },
                    complete: function() {
                        $('#loading-indicator').hide(); // Sembunyikan loader setelah selesai
                    }
                });
            }
        });
    </script>
@endsection
