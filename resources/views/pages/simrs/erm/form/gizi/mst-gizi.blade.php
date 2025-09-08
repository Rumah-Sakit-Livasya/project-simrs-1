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
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Form Skrining Gizi (Malnutrition Screening Tool) Dewasa</h4>
                                </div>
                                <div class="card-body">
                                    <form method="post" class="form" id="form-skrining-gizi" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="registration_id" id="registration_id"
                                            value="{{ $registration->id }}">
                                        {{-- <input type="hidden" name="pkid" id="pkid" value="{{ $pengkajian->id ?? '' }}"> --}}

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="diagnosa_medis">Diagnosa Medis</label>
                                                    <input type="text" name="diagnosa_medis" id="diagnosa_medis"
                                                        class="form-control"
                                                        value="{{ old('diagnosa_medis', $pengkajian->diagnosa_medis ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label for="bb" class="form-label">BB (Kg)</label>
                                                            <input type="number" name="bb" id="bb"
                                                                class="form-control kalkulasi"
                                                                value="{{ old('bb', $pengkajian->bb ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label for="tb" class="form-label">TB (Cm)</label>
                                                            <input type="number" name="tb" id="tb"
                                                                class="form-control kalkulasi"
                                                                value="{{ old('tb', $pengkajian->tb ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label for="imt" class="form-label">IMT (Kg/m2)</label>
                                                            <input type="number" name="imt" id="imt"
                                                                class="form-control" readonly
                                                                value="{{ old('imt', $pengkajian->imt ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label for="lutut" class="form-label">Tinggi Lutut
                                                                (Cm)</label>
                                                            <input type="number" name="lutut" id="lutut"
                                                                class="form-control"
                                                                value="{{ old('tinggi_lutut', $pengkajian->tinggi_lutut ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label for="lla" class="form-label">LLA (Cm)</label>
                                                            <input type="number" name="lla" id="lla"
                                                                class="form-control"
                                                                value="{{ old('lla', $pengkajian->lla ?? '') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table table-bordered mt-3" style="width: 100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>PARAMETER</th>
                                                    <th class="text-center">SKOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><strong>1. Skor IMT</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>- IMT &gt; 20 (Obesitas &gt; 30)</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor1" id="skor1_0" value="0" data-skor="0"
                                                                @if (old('skor1', $pengkajian->skor1 ?? '') == 0) checked @endif>
                                                            <label class="form-check-label" for="skor1_0">0</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>- IMT 18.5 - 20</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor1" id="skor1_1" value="1" data-skor="1"
                                                                @if (old('skor1', $pengkajian->skor1 ?? '') == 1) checked @endif>
                                                            <label class="form-check-label" for="skor1_1">1</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>- IMT &lt; 18.5</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor1" id="skor1_2" value="2"
                                                                data-skor="2"
                                                                @if (old('skor1', $pengkajian->skor1 ?? '') == 2) checked @endif>
                                                            <label class="form-check-label" for="skor1_2">2</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>2. Skor Kehilangan BB Yang Tidak
                                                            Direncanakan 3-6 Bulan Terakhir</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>- BB hilang &lt; 5%</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor2" id="skor2_0" value="0"
                                                                data-skor="0"
                                                                @if (old('skor2', $pengkajian->skor2 ?? '') == 0) checked @endif>
                                                            <label class="form-check-label" for="skor2_0">0</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>- BB hilang 5% - 10%</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor2" id="skor2_1" value="1"
                                                                data-skor="1"
                                                                @if (old('skor2', $pengkajian->skor2 ?? '') == 1) checked @endif>
                                                            <label class="form-check-label" for="skor2_1">1</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>- BB hilang &gt; 10%</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor2" id="skor2_2" value="2"
                                                                data-skor="2"
                                                                @if (old('skor2', $pengkajian->skor2 ?? '') == 2) checked @endif>
                                                            <label class="form-check-label" for="skor2_2">2</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>3. Skor Efek Penyakit Akut</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>- Ada asupan nutrisi &gt; 5 hari</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor3" id="skor3_0" value="0"
                                                                data-skor="0"
                                                                @if (old('skor3', $pengkajian->skor3 ?? '') == 0) checked @endif>
                                                            <label class="form-check-label" for="skor3_0">0</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>- Tidak Ada asupan nutrisi &gt; 5 hari</td>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input skor" type="radio"
                                                                name="skor3" id="skor3_2" value="2"
                                                                data-skor="2"
                                                                @if (old('skor3', $pengkajian->skor3 ?? '') == 2) checked @endif>
                                                            <label class="form-check-label" for="skor3_2">2</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-secondary">
                                                    <td style="font-size: 1.1rem; font-weight: bold;">TOTAL SKOR</td>
                                                    <td>
                                                        <input type="text" name="hasil_skor" id="hasil_skor"
                                                            class="form-control form-control-lg text-center fw-bold"
                                                            readonly
                                                            value="{{ old('hasil_skor', $pengkajian->hasil_skor ?? '') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <label for="analisis_skor" class="form-label fw-bold">Analisis
                                                            Skor / Rencana Tindak Lanjut</label>
                                                        <textarea readonly name="analisis_skor" id="analisis_skor" class="form-control" rows="4">{{ old('analisis_skor', $pengkajian->analisis_skor ?? '') }}</textarea>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="mt-4">
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                <button type="button" class="btn btn-primary print-btn">
                                                    <i class="mdi mdi-printer"></i> Print
                                                </button>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-warning save-form"
                                                        data-status="0">
                                                        <i class="mdi mdi-content-save"></i> Simpan (draft)
                                                    </button>
                                                    <button type="button" class="btn btn-success save-form"
                                                        data-status="1">
                                                        <i class="mdi mdi-content-save"></i> Simpan (final)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
            // Fungsi untuk menghitung total skor dan analisisnya
            function calculateTotalScore() {
                let totalSkor = 0;
                $('.skor:checked').each(function() {
                    totalSkor += parseInt($(this).data('skor'));
                });

                $('#hasil_skor').val(totalSkor);

                let analisisText = '';
                if (totalSkor < 1) {
                    analisisText = 'Beresiko rendah : ulangi skrining setiap 7 hari';
                } else if (totalSkor == 1) {
                    analisisText =
                        'Resiko menengah, monitoring asupan selama 3 hari. jika tidak ada peningkatan, lanjutkan pengkajian dan ulangi skrining setiap 7 hari';
                } else if (totalSkor >= 2) {
                    analisisText =
                        'Beresiko tinggi, bekerja sama dengan Tim Dukungan Gizi / Panitia Asupan Nutrisi. Upaya peningkatan asupan gizi dan memberikan makanan sesuai dengan daya terima. Monitoring asupan makanan setiap hari. Ulangin skrining setiap 7 hari.';
                }
                $('#analisis_skor').val(analisisText);
            }

            // Fungsi untuk menghitung IMT
            function calculateIMT() {
                const bb = parseFloat($("#bb").val());
                const tb = parseFloat($("#tb").val());
                if (!isNaN(bb) && !isNaN(tb) && tb > 0) {
                    const imt = bb / ((tb / 100) * (tb / 100));
                    $("#imt").val(imt.toFixed(2));
                } else {
                    $("#imt").val('');
                }
            }

            // Panggil fungsi kalkulasi saat halaman dimuat jika sudah ada data
            calculateIMT();
            calculateTotalScore();

            // Event listener untuk radio button skor
            $('.skor').on('change', function() {
                calculateTotalScore();
            });

            // Event listener untuk input BB dan TB
            $('.kalkulasi').on("keyup change", function() {
                calculateIMT();
            });

            // Event listener untuk tombol simpan
            $('.save-form').on('click', function(e) {
                e.preventDefault();

                // Tampilkan konfirmasi (misalnya dengan SweetAlert)
                // Swal.fire({...});

                const status = $(this).data('status');
                let formData = new FormData($('#form-skrining-gizi')[0]);
                formData.append('status', status);

                $.ajax({
                    url: '{{ route('erm.skrining-gizi-dewasa.store') }}', // Gunakan nama route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Tampilkan notifikasi sukses
                        // misalnya toastr.success(response.message);
                        alert(response.message);
                        // Opsional: redirect atau refresh
                        // window.location.reload();
                    },
                    error: function(xhr) {
                        // Tampilkan notifikasi error
                        const errors = xhr.responseJSON.errors;
                        let errorMsg = 'Terjadi kesalahan:\n';
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorMsg += value[0] + '\n';
                            });
                        } else {
                            errorMsg = xhr.responseJSON.message || 'Gagal menyimpan data.';
                        }
                        // misalnya toastr.error(errorMsg);
                        alert(errorMsg);
                    }
                });
            });
        });
    </script>
@endsection
