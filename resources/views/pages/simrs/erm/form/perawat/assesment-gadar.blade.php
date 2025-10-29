@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="assesment-keperawatan-gadar" method="POST">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">ASSESMENT KEPERAWATAN GAWAT DARURAT</h2>
                    </header>
                    <div class="row mt-5">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Tanggal & Jam Masuk</label>
                                <div class="input-group">
                                    <input type="date" name="tgl_masuk" class="form-control"
                                        value="{{ $pengkajian?->tgl_masuk ?? now()->format('Y-m-d') }}">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        value="{{ $pengkajian?->jam_masuk ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Tanggal & Jam Dilayani</label>
                                <div class="input-group">
                                    <input type="date" name="tgl_dilayani" class="form-control"
                                        value="{{ $pengkajian?->tgl_dilayani ?? now()->format('Y-m-d') }}">
                                    <input type="time" name="jam_dilayani" class="form-control"
                                        value="{{ $pengkajian?->jam_dilayani ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Keluhan Utama</label>
                                <textarea class="form-control" name="keluhan_utama" rows="2">{{ $pengkajian?->keluhan_utama }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Riwayat pengobatan / perawatan sebelumnya</label>
                                <textarea class="form-control" name="riwayat_pengobatan" rows="2">{{ $pengkajian?->riwayat_pengobatan }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Riwayat penyakit mayor dalam keluarga</label>
                                <textarea class="form-control" name="riwayat_penyakit_keluarga" rows="2">{{ $pengkajian?->riwayat_penyakit_keluarga }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Diagnosa Keperawatan 1</label>
                                @include('pages.simrs.erm.form.perawat.component.select-diagnosa-keperawatan-1')
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Rencana Tindak Lanjut 1</label>
                                @include('pages.simrs.erm.form.perawat.component.select-rencana-tindak-lanjut-1')
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Diagnosa Keperawatan 2</label>
                                @include('pages.simrs.erm.form.perawat.component.select-diagnosa-keperawatan-2')
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Rencana Tindak Lanjut 2</label>
                                @include('pages.simrs.erm.form.perawat.component.select-rencana-tindak-lanjut-2')
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Diagnosa Keperawatan 3</label>
                                @include('pages.simrs.erm.form.perawat.component.select-diagnosa-keperawatan-3')
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Rencana Tindak Lanjut 3</label>
                                @include('pages.simrs.erm.form.perawat.component.select-rencana-tindak-lanjut-3')
                            </div>
                        </div>
                    </div>

                    @include('pages.simrs.erm.form.perawat.component.kasus')
                    @include('pages.simrs.erm.form.perawat.component.keadaan-prahospital')
                    @include('pages.simrs.erm.form.perawat.component.riwayat-psikososial')
                    @include('pages.simrs.erm.form.perawat.component.skrining-nyeri')
                    @include('pages.simrs.erm.form.perawat.component.skala-flacc')
                    @include('pages.simrs.erm.form.perawat.component.keadaan-umum')
                    @include('pages.simrs.erm.form.perawat.component.skrining-resiko-jatuh')
                    @include('pages.simrs.erm.form.perawat.component.skrining-gizi')
                    @include('pages.simrs.erm.form.perawat.component.barthel-index')
                    @include('pages.simrs.erm.form.perawat.component.perencanaan-pulang')

                    @include('pages.simrs.erm.partials.signature-many', [
                        'judul' => 'Nama Perawat',
                        'name_prefix' => 'signatures[perawat]',
                        'role' => 'perawat',
                        'index' => 'asesmen_keperawatan_gadar',
                        'pic' => auth()->user()->employee->fullname,
                        'signature_model' => $pengkajian?->signatures()->where('role', 'perawat')->first(),
                    ])

                    <div class="row mt-5">
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
    @yield('plugin-gadar')
    <script>
        $(document).ready(function() {
            $('#pic').val('{{ auth()->user()->employee->fullname }}');

            var registrationId = {{ $registration->id }};

            $('#assesment-keperawatan-gadar').on('submit', function(e) {
                console.log('Form submitted with registration ID:', registrationId);
                e.preventDefault(); // mencegah submit form default
                const form = $(this);
                const formData = form.serialize(); // serialize data form
                $.ajax({
                    url: '/api/simrs/erm/assesment-keperawatan-gadar',
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
        });
    </script>

    {{-- SCRIPT BARTHEL INDEX --}}
    <script>
        $(document).ready(function() {
            const barthelScores = {
                "Tidak Mampu": 0,
                "Dibantu": 1,
                "Dibantu Mandi": 0,
                "Dibantu Berhias": 0,
                "Makan Mandiri": 2,
                "Mandi Mandiri": 1,
                "Berhias Mandiri": 1,
                "Berpakaian Mandiri": 2,
                "Transfer Mandiri": 3,
                "Inkontinen": 0,
                "Kadang Inkontinen/Konstipasi": 1,
                "Tidak ada masalah": 2,
                "Inkontinen/pakai kateter": 0,
                "kadang inkontinen": 1,
                "Tergantung total": 0,
                "Dibantu Sebagian": 1,
                "Dibantu lebih dari 1 orang untuk duduk": 1,
                "Dibantu 1 Orang": 2,
                "Mandiri": 2,
                "Kursi Roda": 1,
                "Mandiri walau pakai alat bantu": 3,
                "Dibantu dan Alat bantu": 1
            };

            function calculateBarthelScore() {
                let total = 0;
                $('.barthel-select').each(function() {
                    const val = $(this).val();
                    let score = 0;

                    if (barthelScores.hasOwnProperty(val)) {
                        score = barthelScores[val];
                    }

                    total += score;
                });

                // Update total score
                $('#barthel-skor').val(total);

                // Update analisa berdasarkan nilai
                let analisa = '';
                if (total <= 8) {
                    analisa = "Total care";
                } else if (total >= 9 && total <= 11) {
                    analisa = "Partial care";
                } else if (total >= 12) {
                    analisa = "Self care";
                }

                $('#barthel-analisa').val(analisa);
            }

            // Hitung saat halaman dimuat
            calculateBarthelScore();

            // Hitung ulang saat ada perubahan pilihan
            $('.barthel-select').on('change', calculateBarthelScore);
        });
    </script>

    {{-- SCRIPT SKALA FLACC --}}
    <script>
        $(document).ready(function() {
            // Object map option values to scores
            const scores = {
                "Tersenyum / Tidak ada Ekspresi Khusus": 0,
                "Sesekali Meringis atau mengerutkan kening, ditarik, tertarik": 1,
                "Sering ke dagu bergetar konstan, rahang terkatup": 2,
                "Yang normal posisi atau santai": 0,
                "Gelisah, Tegang": 1,
                "Menendang atau kaki dibuat": 2,
                "Berbaring tenang, posisi normal, bergerak dengan mudah": 0,
                "Menggeliat, pergeseran bolak-balik, tegang": 1,
                "Melengkung, kaku atau menyentak": 2,
                "Tidak ada teriakan (terjaga atau tertidur)": 0,
                "Eregan atau merintih, sesekali keluhan": 1,
                "Mengangis terus, jeritan atau isak tangis, keluhan sering": 2,
                "Bersuara normal, tenang": 0,
                "Tenang bila dipeluk": 1,
                "Sulit untuk ditenangkan": 2
            };

            function calculateScore() {
                let total = 0;
                $('.flacc-select').each(function() {
                    const val = $(this).val();
                    if (scores[val] !== undefined) {
                        total += scores[val];
                    }
                });
                // Ensure total is not negative

                $('#flacc-skor').val(total);
            }
            // Initial calculate on page load
            calculateScore();
            // Recalculate on change in flacc-select dropdowns
            $('.flacc-select').on('change', function() {
                calculateScore();
            });
        });
    </script>

    {{-- SCRIPT SKRINING NYERI --}}
    <script>
        $(document).ready(function() {
            // 1. Definisikan elemen target
            var $skorBadges = $('.wong-baker-scale [data-skor]');
            var $skorInput = $('#skor_nyeri');

            // 2. Buat fungsi klik
            $skorBadges.on('click', function() {
                // Ambil $(this) (badge yang diklik)
                var $clickedBadge = $(this);

                // Ambil nilai dari atribut data-skor
                var skor = $clickedBadge.data('skor');

                // Set nilai input #skor_nyeri
                $skorInput.val(skor);

                // (Opsional) Beri tanda visual pada badge yang aktif
                // Hapus kelas 'skor-aktif' dari semua badge
                $skorBadges.removeClass('skor-aktif');
                // Tambahkan kelas 'skor-aktif' hanya ke badge yang diklik
                $clickedBadge.addClass('skor-aktif');
            });

            // 3. (Opsional) Sinkronkan saat halaman dimuat
            // Jika input #skor_nyeri sudah punya nilai (misal dari data lama),
            // tandai badge yang sesuai.
            var initialSkor = $skorInput.val();
            if (initialSkor !== '') {
                // Cari badge yang data-skor-nya sama dengan nilai awal
                $skorBadges.filter('[data-skor="' + initialSkor + '"]').addClass('skor-aktif');
            }
        });
    </script>

    <script>
        $(function() {
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
@endsection
