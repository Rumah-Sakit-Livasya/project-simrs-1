@extends('pages.simrs.erm.index')

@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-asesmen-awal-dokter" action="javascript:void(0)" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-center text-success font-weight-bold">ASESMEN AWAL DOKTER</h3>
                    <button class="btn btn-info" id="histori_pengkajian" type="button"><i class="fas fa-history"></i>
                        Histori</button>
                </div>
                <hr>

                {{-- Include partials untuk setiap bagian form --}}
                @include('pages.simrs.erm.form.dokter.component.tanda-vital', [
                    'data' => $data,
                    'pengkajian' => $pengkajianNurse,
                ])
                @include('pages.simrs.erm.form.dokter.component.info-masuk', ['data' => $data])
                @include('pages.simrs.erm.form.dokter.component.anamnesis', ['data' => $data])
                @include('pages.simrs.erm.form.dokter.component.pemeriksaan', ['data' => $data])
                @include('pages.simrs.erm.form.dokter.component.gambar-anatomi', ['data' => $data])
                @include('pages.simrs.erm.form.dokter.component.edukasi-rencana', ['data' => $data])

                {{-- Bagian Tanda Tangan --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6 offset-md-3">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Dokter',
                            'name_prefix' => 'signatures[dokter_pemeriksa]',
                            'role' => 'dokter_pemeriksa',
                            'index' => 'asesmen_dokter_pemeriksa',
                            'signature_model' => $pengkajian->signatures()->where('role', 'dokter_pemeriksa')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="button" class="btn btn-warning save-form" data-status="draft"><i class="fas fa-save"></i>
                    Simpan (Draft)</button>
                <button type="button" class="btn btn-success save-form" data-status="final"><i
                        class="fas fa-check-circle"></i> Simpan (Final)</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Sesuaikan path jika berbeda --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/painterro@1.2.78/build/painterro.min.js"></script>
    <script>
        $(document).ready(function() {

            // --- FUNGSI UTAMA UNTUK MENGUMPULKAN DAN MEMFORMAT DATA ---
            function updatePemeriksaanFisik() {
                // 1. Ambil semua nilai dari input
                const bp = $('input[name="tanda_vital[bp]"]').val();
                const pr = $('input[name="tanda_vital[pr]"]').val();
                const rr = $('input[name="tanda_vital[rr]"]').val();
                const temp = $('input[name="tanda_vital[temperatur]"]').val();
                const spo2 = $('input[name="tanda_vital[spo2]"]').val();
                const tb = $('#height_badan').val();
                const bb = $('#weight_badan').val();
                const imt = $('#bmi').val();
                const kat_imt = $('#kat_bmi').val();

                // 2. Buat array untuk menampung bagian-bagian teks
                let summaryParts = [];

                // 3. Tambahkan teks ke array hanya jika inputnya tidak kosong
                if (bp) summaryParts.push(`TD: ${bp} mmHg`);
                if (pr) summaryParts.push(`N: ${pr} x/menit`);
                if (rr) summaryParts.push(`RR: ${rr} x/menit`);
                if (temp) summaryParts.push(`S: ${temp} °C`);
                if (spo2) summaryParts.push(`SpO2: ${spo2} %`);
                if (tb) summaryParts.push(`TB: ${tb} cm`);
                if (bb) summaryParts.push(`BB: ${bb} kg`);
                if (imt) {
                    summaryParts.push(kat_imt ? `IMT: ${imt} kg/m² (${kat_imt})` : `IMT: ${imt} kg/m²`);
                }

                // ==========================================================
                // PERUBAHAN DI SINI: Ganti ', ' dengan '\n' untuk baris baru
                // ==========================================================
                const summaryText = summaryParts.join('\n');

                // 5. Masukkan hasil akhirnya ke dalam textarea
                $('textarea[name="pemeriksaan_fisik"]').val(summaryText);
            }


            // --- FUNGSI UNTUK MENGHITUNG BMI ---
            function calculateBmi() {
                const heightCm = parseFloat($('#height_badan').val());
                const weightKg = parseFloat($('#weight_badan').val());

                if (heightCm > 0 && weightKg > 0) {
                    const heightM = heightCm / 100;
                    const bmi = weightKg / (heightM * heightM);
                    const bmiFormatted = bmi.toFixed(2);

                    $('#bmi').val(bmiFormatted);

                    let category = '';
                    if (bmi < 18.5) {
                        category = 'Berat badan kurang';
                    } else if (bmi >= 18.5 && bmi <= 24.9) {
                        category = 'Normal';
                    } else if (bmi >= 25 && bmi <= 29.9) {
                        category = 'Berat badan berlebih';
                    } else {
                        category = 'Obesitas';
                    }
                    $('#kat_bmi').val(category);

                } else {
                    $('#bmi').val('');
                    $('#kat_bmi').val('');
                }

                updatePemeriksaanFisik();
            }

            // --- EVENT LISTENERS ---
            $('#vital-signs-container').on('keyup', '.vital-input', function() {
                if ($(this).hasClass('calc-bmi')) {
                    calculateBmi();
                } else {
                    updatePemeriksaanFisik();
                }
            });

            // --- INISIALISASI ---
            updatePemeriksaanFisik();

        });
    </script>
    @include('pages.simrs.erm.form.dokter.component.js.asesmen-awal-dokter-js', ['data' => $data])
@endpush
