    @extends('pages.simrs.erm.index')
    @section('erm')
        @if (isset($registration) || $registration != null)
            {{-- content start -- --}}
            <div class="tab-content p-3">
                <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                    <div class="row">
                        <form action="javascript:void(0)" class="w-100" data-tipe-cppt="perawat" data-tipe-rawat="rawat-jalan"
                            id="cppt-perawat-rajal-form">
                            @csrf
                            @method('POST')
                            <div class="col-md-12">
                                <div class="p-3">
                                    <div class="p-3" id="accordion_soap">
                                        <div class="card-head collapsed d-flex justify-content-between">
                                            <div class="title">
                                                <header class="text-primary text-center font-weight-bold mb-4">
                                                    <h2 class="font-weight-bold">CPPT PERAWAT</h4>
                                                </header>
                                            </div> <!-- Tambahkan judul jika perlu -->
                                            <div class="tools ml-auto">
                                                <!-- Tambahkan ml-auto untuk memindahkan tombol ke kanan -->
                                                <button class="btn btn-primary btnAdd mr-2" id="btnAdd"
                                                    data-toggle="collapse" data-parent="#accordion_soap"
                                                    data-target="#add_soap" aria-expanded="true">
                                                    <i class="mdi mdi-plus-circle"></i> Tambah CPPT
                                                </button>
                                                <button class="btn btn-secondary collapsed" data-toggle="collapse"
                                                    data-parent="#accordion_soap" data-target="#view-filter-soap"
                                                    aria-expanded="true">
                                                    <i class="mdi mdi-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                        <div id="add_soap" class="panel-content collapse in" aria-expanded="true">
                                            <form method="post" class="form-horizontal" id="fsSOAP" autocomplete="off">
                                                <input type="hidden" name="registration_id"
                                                    value="{{ $registration->id }}" />
                                                <input type="hidden" name="tipe_rawat" value="rawat-jalan" />
                                                <input type="hidden" name="tipe_cppt" value="perawat" />
                                                <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                                    value="{{ $registration->patient->medical_record_number }}" />

                                            <!-- Two Column Layout for Subjective and Objective -->
                                            <div class="row">
                                                <!-- Subjective -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: rgba(0, 123, 255, .2);">
                                                            <span @style('color: rgba(0, 123, 255, 1);')>Subjective</span>
                                                        </div>
                                                    </div>

                                                    <!-- Objective -->
                                                    <div class="col-md-6">
                                                        <div class="card mt-3">
                                                            {{-- PERUBAHAN 2: Header Card Objective --}}
                                                            <div class="card-header text-white d-flex justify-content-between align-items-center"
                                                                style="background-color: rgba(40, 167, 69, .2);">
                                                                <span style="color: rgb(1, 49, 12);)">Objective</span>
                                                                {{-- Tombol template dipindah ke sini --}}
                                                                <div>
                                                                    <button type="button" id="btn_objective_fisik"
                                                                        class="btn btn-success btn-sm">Objective
                                                                        Fisik</button>
                                                                    <button type="button" id="btn_objective_fungsional"
                                                                        class="btn btn-info btn-sm">Objective
                                                                        Fungsional</button>
                                                                </div>
                                                            </div>
                                                            {{-- AKHIR PERUBAHAN 2 --}}
                                                            <div class="card-body p-0">
                                                                <textarea class="form-control border-0 rounded-0" id="objective" name="objective" rows="8">{{ 'Tanda-tanda Vital:' .
                                                                    "\n" .
                                                                    'Nadi (PR): ' .
                                                                    ($data?->pr ?? '') .
                                                                    "\n" .
                                                                    'Respirasi (RR): ' .
                                                                    ($data?->rr ?? '') .
                                                                    "\n" .
                                                                    'Tensi (BP): ' .
                                                                    ($data?->bp ?? '') .
                                                                    "\n" .
                                                                    'Suhu (T): ' .
                                                                    ($data?->temperatur ?? '') .
                                                                    "\n" .
                                                                    'Tinggi Badan: ' .
                                                                    ($data?->body_height ?? '') .
                                                                    ' cm' .
                                                                    "\n" .
                                                                    'Berat Badan: ' .
                                                                    ($data?->body_weight ?? '') .
                                                                    ' kg' .
                                                                    "\n" .
                                                                    'SPO2: ' .
                                                                    ($data?->sp02 ?? '') .
                                                                    '%' .
                                                                    "\n" .
                                                                    'Skor Nyeri: ' .
                                                                    ($data?->skor_nyeri ?? '') .
                                                                    "\n" .
                                                                    'Riwayat Alergi: ' .
                                                                    (isset($data?->allergy_medicine)
                                                                        ? (is_array($data->allergy_medicine)
                                                                            ? (count($data->allergy_medicine) > 0
                                                                                ? implode(', ', $data->allergy_medicine)
                                                                                : 'Tidak ada')
                                                                            : ($data->allergy_medicine ?:
                                                                            'Tidak ada'))
                                                                        : 'Tidak ada') .
                                                                    "\n\n" .
                                                                    'Pemeriksaan Fisik:' .
                                                                    "\n" .
                                                                    ($data?->diagnosis ?? '') .
                                                                    "\n\n" .
                                                                    'Catatan Tambahan:' .
                                                                    "\n" .
                                                                    ($data?->registration_notes ?? '') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Sisanya tidak ada perubahan -->
                                                <!-- Two Column Layout for Assessment and Planning -->
                                                <div class="row">
                                                    <!-- Assessment -->
                                                    <div class="col-md-6">
                                                        <div class="card mt-3">
                                                            <div class="card-header text-white d-flex justify-content-between"
                                                                style="background-color: #dc3545;">
                                                                <span>Assessment</span>
                                                                <span id="diag_perawat"
                                                                    class="badge badge-warning pointer">Diagnosa
                                                                    Keperawatan</span>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="8">{{ 'Diagnosa Kerja:' .
                                                                    "\n" .
                                                                    ($data?->diagnosis ?? '') .
                                                                    "\n\n" .
                                                                    'Diagnosa Keperawatan:' .
                                                                    "\n" .
                                                                    ($data?->diagnosa_keperawatan ?? '') .
                                                                    "\n\n" .
                                                                    'Analisis Masalah:' .
                                                                    "\n" .
                                                                    ($data?->registration_notes ?? '') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Planning -->
                                                    <div class="col-md-6">
                                                        <div class="card mt-3">
                                                            <div class="card-header text-white d-flex justify-content-between"
                                                                style="background-color: #ffc107;">
                                                                <span>Planning</span>
                                                                <span id="intervensi_perawat"
                                                                    class="badge badge-dark pointer">Intervensi</span>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <textarea class="form-control border-0 rounded-0" id="planning" name="planning" rows="8">
    Terapi / Tindakan :
                                                            </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Objective -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: rgba(40, 167, 69, .2);">
                                                            <span @style('color: rgba(40, 167, 69, 1);')>Objective</span>
                                                        </div>
                                                    </div>
                                                    <!-- Evaluation Section -->
                                                    <div class="col-md-6">
                                                        <div class="card mt-3">
                                                            <div class="card-header text-white"
                                                                style="background-color: #17a2b8;">
                                                                Evaluasi/Revaluasi
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <textarea class="form-control border-0 rounded-0" id="evaluasi" name="evaluasi" rows="8"
                                                                    placeholder="Evaluasi">
                                                            </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Two Column Layout for Assessment and Planning -->
                                            <div class="row">
                                                <!-- Assessment -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white d-flex justify-content-between"
                                                            style="background-color: rgba(220, 53, 69, .2);">
                                                            <span @style('color: rgba(220, 53, 69, 1);')>Assessment</span>
                                                            <span id="diag_perawat"
                                                                class="badge badge-warning pointer">Diagnosa
                                                                Keperawatan</span>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="8">{{ 'Diagnosa Kerja:' .
                                                                "\n" .
                                                                ($data?->diagnosis ?? '') .
                                                                "\n\n" .
                                                                'Diagnosa Keperawatan:' .
                                                                "\n" .
                                                                ($data?->diagnosa_keperawatan ?? '') .
                                                                "\n\n" .
                                                                'Analisis Masalah:' .
                                                                "\n" .
                                                                ($data?->registration_notes ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Planning -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white d-flex justify-content-between"
                                                            style="background-color: rgba(255, 193, 7, .2);">
                                                            <span @style('color: rgba(255, 193, 7, 1);')>Planning</span>
                                                            <span id="intervensi_perawat"
                                                                class="badge badge-dark pointer">Intervensi</span>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="planning" name="planning" rows="8">
Terapi / Tindakan :
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                            <div class="row">
                                                <!-- Implementation Section -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: rgba(108, 117, 125, .2);">
                                                            <span @style('color: rgba(108, 117, 125, 1);')>Implementasi</span>
                                                        </div>
                                                    </x-form-filter-group>

                                                    {{-- 2. Komponen untuk Select Status Rawat --}}
                                                    <x-form-filter-group label="Status Rawat" for="dept">
                                                        <select class="form-control sel2" id="dept" name="dept"
                                                            style="width: 100%;">
                                                            {{-- Opsi bisa di-generate dari controller atau hardcode di sini --}}
                                                            <option value="">Semua Status</option>
                                                            <option value="ri">Rawat Inap</option>
                                                            <option value="rj">Rawat Jalan</option>
                                                            <option value="igd">IGD</option>
                                                        </select>
                                                    </x-form-filter-group>

                                                    {{-- 3. Komponen untuk Select Tipe CPPT --}}
                                                    <x-form-filter-group label="Tipe CPPT" for="role">
                                                        <select class="form-control sel2" id="role" name="role"
                                                            style="width: 100%;">
                                                            <option value="">Semua Tipe</option>
                                                            <option value="dokter">Dokter</option>
                                                            <option value="perawat">Perawat</option>
                                                        </select>
                                                    </x-form-filter-group>

                                                    {{-- 4. Komponen untuk Tombol Aksi --}}
                                                    <x-form-filter-group label="">
                                                        <button type="button" class="btn btn-primary"
                                                            id="btn-apply-filter">Terapkan Filter</button>
                                                    </x-form-filter-group>

                                                </div>
                                                <!-- Evaluation Section -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: rgba(23, 162, 184, .2);">
                                                            <span @style('color: rgba(23, 162, 184, 1);')>Evaluasi/Revaluasi</span>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="evaluasi" name="evaluasi" rows="8"
                                                                placeholder="Evaluasi">
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- Instruction Section -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: rgba(102, 16, 242, .2);">
                                                            <span @style('color: rgba(102, 16, 242, 1);')>Instruksi</span>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="instruksi" name="instruksi" rows="8"
                                                                placeholder="Evaluasi">
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @include('pages.simrs.erm.partials.signature-field', [
                                                'judul' => 'Perawat,',
                                                'pic' => auth()->user()->employee->fullname,
                                                'role' => 'perawat',
                                                'prefix' => 'cppt_perawat', // Berikan prefix unik
                                                'signature_model' => $pengkajian?->signature, // Kirim model data tanda tangan yang relevan
                                            ])

                                            <!-- Action Buttons -->
                                            <div class="d-flex justify-content-between mt-4">
                                                <button type="button" class="btn btn-outline-secondary" id="tutup">
                                                    <span class="mdi mdi-arrow-up-bold-circle-outline"></span> Tutup
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-saves-soap"
                                                    id="bsSOAP" name="save">
                                                    <span class="mdi mdi-content-save"></span> Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Filter Section -->
                                    <div id="view-filter-soap" class="panel-content collapse">
                                        <div class="card-body border-top">
                                            <div class="row">

                                                {{-- 1. Komponen untuk Rentang Tanggal --}}
                                                <x-form-filter-group label="Tgl. CPPT" for="sdate">
                                                    <div class="input-group">
                                                        <input name="sdate" type="text"
                                                            class="datepicker form-control" id="sdate"
                                                            placeholder="Dari Tanggal" readonly />
                                                        <div class="input-group-append input-group-prepend">
                                                            <span class="input-group-text">s/d</span>
                                                        </div>
                                                        <input name="edate" type="text"
                                                            class="datepicker form-control" id="edate"
                                                            placeholder="Sampai Tanggal" readonly />
                                                    </div>
                                                </x-form-filter-group>

                                                {{-- 2. Komponen untuk Select Status Rawat --}}
                                                <x-form-filter-group label="Status Rawat" for="dept">
                                                    <select class="form-control sel2" id="dept" name="dept"
                                                        style="width: 100%;">
                                                        {{-- Opsi bisa di-generate dari controller atau hardcode di sini --}}
                                                        <option value="">Semua Status</option>
                                                        <option value="ri">Rawat Inap</option>
                                                        <option value="rj">Rawat Jalan</option>
                                                        <option value="igd">IGD</option>
                                                    </select>
                                                </x-form-filter-group>

                                                {{-- 3. Komponen untuk Select Tipe CPPT --}}
                                                <x-form-filter-group label="Tipe CPPT" for="role">
                                                    <select class="form-control sel2" id="role" name="role"
                                                        style="width: 100%;">
                                                        <option value="">Semua Tipe</option>
                                                        <option value="dokter">Dokter</option>
                                                        <option value="perawat">Perawat</option>
                                                    </select>
                                                </x-form-filter-group>

                                                {{-- 4. Komponen untuk Tombol Aksi --}}
                                                <x-form-filter-group label="">
                                                    <button type="button" class="btn btn-primary"
                                                        id="btn-apply-filter">Terapkan Filter</button>
                                                </x-form-filter-group>

                                            </div>
                                        </div>
                                        <!-- End Filter Section -->
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="col-md-12">
                            <hr style="border-color: #868686; margin-bottom: 30px;">
                            {{-- Container utama untuk semua data CPPT --}}
                            <div id="cppt-container" class="cppt-container">
                                <!-- Konten (Header tanggal dan kolom) akan di-generate oleh JavaScript -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        @include('pages.simrs.erm.partials.modal-diagnosa')
        @include('pages.simrs.erm.partials.modal-intervensi')
        @include('pages.simrs.erm.partials.modal-sbar')

    @endsection
    @section('plugin-erm')
        <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
        <script src="/js/formplugins/select2/select2.bundle.js"></script>
        <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
        <script src="/js/datagrid/datatables/datatables.export.js"></script>

        <script>
            $(document).ready(function() {
                // ======================================================
                // KODE BARU: LOGIKA UNTUK TOMBOL TEMPLATE CPPT
                // ======================================================
                const subjectiveAwalTemplate = `Keluhan Utama :
    Riwayat Penyakit Sekarang (RPS) :
    Riwayat Penyakit Dahulu (RPD) :
    Riwayat Penyakit Keluarga :`;

                const objectiveFisikTemplate = `Keadaan Umum :
    Nadi : x/menit
    Respirasi(RR) : x/menit
    Tensi (BP) : mmHg
    Suhu (T) : C
    Berat badan : Kg
    Skor EWS :
    Skor nyeri :
    Saturasi :
    Skor resiko jatuh :
    Primary Survey :
    Airway :
    Breathing :
    Circulation :
    Disability :
    Exposure :`;

                const objectiveFungsionalTemplate = `Kepala :
    Mata :
    Telinga :
    Hidung :
    Mulut:
    Leher:
    Dada :
    Perut :
    Inguinal :
    Genital :
    Extremitas Atas :
    Extremitas Bawah :`;

                // --- Fungsi helper untuk menambahkan teks ke textarea ---
                function appendToTextarea(textareaId, newText) {
                    const textarea = $(textareaId);
                    const currentVal = textarea.val();

                    // Cek jika textarea kosong atau diakhiri dengan baris baru
                    if (currentVal.trim() === '' || currentVal.endsWith('\n')) {
                        // Langsung tambahkan teks baru
                        textarea.val(currentVal + newText);
                    } else {
                        // Tambahkan baris baru dulu, baru teks baru
                        textarea.val(currentVal + '\n' + newText);
                    }
                }

                // Event listener untuk tombol 'Subjective Awal'
                $('#btn_subjective_awal').on('click', function() {
                    // Gunakan fungsi append, jangan replace
                    appendToTextarea('#subjective', subjectiveAwalTemplate);
                });

                // Event listener untuk tombol 'Objective Fisik'
                $('#btn_objective_fisik').on('click', function() {
                    // Gunakan fungsi append, jangan replace
                    appendToTextarea('#objective', objectiveFisikTemplate);
                });

                // Event listener untuk tombol 'Objective Fungsional'
                $('#btn_objective_fungsional').on('click', function() {
                    // Gunakan fungsi append, jangan replace
                    appendToTextarea('#objective', objectiveFungsionalTemplate);
                });
                // ======================================================
                // AKHIR KODE BARU
                // ======================================================


                function submitFormCPPT(actionType) {
                    const form = $('#cppt-perawat-rajal-form');
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

                // Saat tombol Save Final diklik
                $('#bsSOAP').on('click', function() {
                    submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
                });

                $('body').addClass('layout-composed');
                $('.select2').select2({
                    placeholder: 'Pilih Item',
                });
                $('#departement_id').select2({
                    placeholder: 'Pilih Klinik',
                });
                // $('#doctor_id').select2({
                //     placeholder: 'Pilih Dokter',
                // });

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

                let diagnosaTable; // Variabel untuk menyimpan instance Datatable

                // 1. Event listener untuk membuka modal
                $('#diag_perawat').on('click', function() {
                    // Hanya inisialisasi Datatable jika belum ada
                    if (!$.fn.DataTable.isDataTable('#diagnosa-table')) {
                        diagnosaTable = $('#diagnosa-table').DataTable({
                            processing: true,
                            serverSide: true,
                            // Mengambil data dari API yang sudah kita buat
                            ajax: {
                                url: "{{ url('api/simrs/master-data/diagnosa-keperawatan/nursing-diagnoses') }}",
                                // Menambahkan parameter pencarian kustom
                                data: function(d) {
                                    d.search_query = $('#diagnosa_search_input').val();
                                }
                            },
                            columns: [{
                                    data: 'category_name',
                                    name: 'category.name'
                                },
                                {
                                    data: 'code',
                                    name: 'code'
                                },
                                {
                                    data: 'diagnosa',
                                    name: 'diagnosa'
                                },
                                // Kolom 'action' kita buat secara kustom di sini
                                {
                                    data: null, // tidak terikat ke kolom database
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row) {
                                        // Membuat tombol "Pilih" dengan data-attributes
                                        return '<button class="btn btn-success btn-sm select-diagnosa-btn" data-code="' +
                                            row.code + '" data-diagnosa="' + row.diagnosa +
                                            '">Pilih</button>';
                                    }
                                }
                            ]
                        });
                    }

                    // Tampilkan modal
                    $('#modal-diagnosa-keperawatan').modal('show');
                });

                // 2. Event listener untuk form pencarian di dalam modal
                $('#diagnosa-search-form').on('submit', function(e) {
                    e.preventDefault(); // Mencegah form submit standar
                    diagnosaTable.draw(); // Memuat ulang data Datatable dengan parameter pencarian baru
                });

                // 3. Event listener untuk tombol "Pilih" (menggunakan event delegation)
                $('#diagnosa-table tbody').on('click', '.select-diagnosa-btn', function() {
                    // Ambil data dari data-attributes tombol
                    const code = $(this).data('code');
                    const diagnosaText = $(this).data('diagnosa');

                    // Format teks yang akan disisipkan
                    const newEntry = code + ' ' + diagnosaText;

                    // Ambil isi textarea assesment saat ini
                    const currentAssesment = $('#assesment').val();

                    let newContent;

                    // Cek apakah textarea sudah berisi, jika ya tambahkan baris baru
                    if (currentAssesment.trim() === '' || currentAssesment.endsWith('\n')) {
                        newContent = currentAssesment + newEntry;
                    } else {
                        newContent = currentAssesment + '\n' + newEntry;
                    }

                    // Set nilai baru ke textarea dan tutup modal
                    $('#assesment').val(newContent);
                    $('#modal-diagnosa-keperawatan').modal('hide');
                });

                // ===================================================================
                // LOGIKA UNTUK MODAL INTERVENSI KEPERAWATAN
                // ===================================================================

                let intervensiTable; // Variabel untuk instance Datatable

                // 1. Event listener untuk membuka modal
                $('#intervensi_perawat').on('click', function() {
                    if (!$.fn.DataTable.isDataTable('#intervensi-table')) {
                        intervensiTable = $('#intervensi-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ url('api/simrs/master-data/interventions') }}",
                                data: function(d) {
                                    // Mengambil tipe rawat dari form utama dan mengirimkannya sebagai parameter
                                    // d.tipe_rawat = $('#cppt-perawat-rajal-form').data('tipe-rawat');
                                    d.search_query = $('#intervensi_search_input').val();
                                }
                            },
                            columns: [{
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: null,
                                    paginate: false,
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row) {
                                        return '<button class="btn btn-success btn-sm select-intervensi-btn" data-name="' +
                                            row.name + '">Pilih</button>';
                                    }
                                }
                            ]
                        });
                    }

                    $('#modal-intervensi-keperawatan').modal('show');
                });

                // 2. Event listener untuk form pencarian
                $('#intervensi-search-form').on('submit', function(e) {
                    e.preventDefault();
                    intervensiTable.draw();
                });

                // 3. Event listener untuk tombol "Pilih"
                $('#intervensi-table tbody').on('click', '.select-intervensi-btn', function() {
                    const name = $(this).data('name');

                    // Target textarea adalah #planning
                    const currentPlanning = $('#planning').val();

                    let newContent;
                    if (currentPlanning.trim() === '' || currentPlanning.endsWith('\n')) {
                        newContent = currentPlanning + '- ' + name; // Menambahkan tanda '-' untuk list
                    } else {
                        newContent = currentPlanning + '\n- ' + name;
                    }

                    $('#planning').val(newContent);
                    $('#modal-intervensi-keperawatan').modal('hide');
                });

                // ===================================================================
                // INISIALISASI FILTER CPPT
                // ===================================================================

                // 1. Inisialisasi Select2 pada elemen dengan class 'sel2' di dalam div filter
                $('#view-filter-soap .sel2').select2({
                    placeholder: "Pilih Opsi",
                    allowClear: true // Menambahkan tombol (x) untuk menghapus pilihan
                });

                // 2. Inisialisasi Bootstrap Datepicker untuk rentang tanggal
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd', // Format yang umum digunakan untuk backend
                    autoclose: true,
                    todayHighlight: true,
                    orientation: 'bottom' // Menampilkan kalender di bawah input
                });
            });
        </script>
        @include('pages.simrs.erm.partials.action-js.cppt')
    @endsection
