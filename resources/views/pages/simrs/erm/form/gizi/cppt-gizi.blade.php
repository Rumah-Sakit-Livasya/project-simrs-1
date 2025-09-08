@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <div class="row">
                    <form action="javascript:void(0)" class="w-100" data-tipe-cppt="gizi" id="cppt-gizi-form">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="subjective" value="-">
                        <input type="hidden" name="objective" value="-">
                        <input type="hidden" name="planning" value="-">
                        <div class="col-md-12">
                            <div class="p-3">
                                <div class="p-3" id="accordion_soap">
                                    <div class="card-head collapsed d-flex justify-content-between">
                                        <div class="title">
                                            <header class="text-primary text-center font-weight-bold mb-4">
                                                <h2 class="font-weight-bold">CPPT GIZI</h4>
                                            </header>
                                        </div> <!-- Tambahkan judul jika perlu -->
                                        <div class="tools ml-auto">
                                            <!-- Tambahkan ml-auto untuk memindahkan tombol ke kanan -->
                                            <button class="btn btn-primary btnAdd mr-2" id="btnAdd"
                                                data-toggle="collapse" data-parent="#accordion_soap" data-target="#add_soap"
                                                aria-expanded="true">
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
                                            <input type="hidden" name="registration_id" value="{{ $registration->id }}" />
                                            <input type="hidden" name="tipe_rawat" value="rawat-jalan" />
                                            <input type="hidden" name="tipe_cppt" value="gizi" />
                                            <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                                value="{{ $registration->patient->medical_record_number }}" />

                                            <!-- Perawat -->
                                            <div class="row">
                                                <div class="col-md-6 mt-3">
                                                    <label for="pid_dokter" class="form-label">Ahli Gizi</label>
                                                    <select
                                                        class="select2 form-control @error('perawat_id') is-invalid @enderror"
                                                        name="perawat_id" id="perawat_id">
                                                        <option value=""></option>
                                                        @foreach ($perawat as $item)
                                                            <option value="{{ $item->user->id }}"
                                                                {{ auth()->user()->id == $item->user->id ? 'selected' : '' }}>
                                                                {{ $item->fullname }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Two Column Layout for Assessment and Planning -->
                                            <div class="row">
                                                <!-- Assessment -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white d-flex justify-content-between"
                                                            style="background-color: #dc3545;">
                                                            <span>Assesment Gizi</span>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="8"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Planning -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white d-flex justify-content-between"
                                                            style="background-color: #ffc107;">
                                                            <span>Diagnosa Gizi</span>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="diagnosa_gizi" name="diagnosa_gizi" rows="8"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Implementation Section -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: #6c757d;">
                                                            Intervensi Gizi
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="intervensi_gizi" name="intervensi_gizi" rows="8"
                                                                placeholder="Intervensi">
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Evaluation Section -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: #17a2b8;">
                                                            Monitoring
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="monitoring" name="monitoring" rows="8"
                                                                placeholder="Monitoring">
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- Instruction Section -->
                                                <div class="col-md-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header text-white"
                                                            style="background-color: #6610f2;">
                                                            Evaluasi
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <textarea class="form-control border-0 rounded-0" id="evaluasi" name="evaluasi" rows="8"
                                                                placeholder="Evaluasi">
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @include('pages.simrs.erm.partials.signature-field', [
                                                'judul' => 'Ahli Gizi,',
                                                'pic' => auth()->user()->employee->fullname,
                                                'role' => 'ahli_gizi',
                                                'prefix' => 'cppt_gizi', // Berikan prefix unik
                                                'signature_model' => $pengkajian?->signature, // Kirim model data tanda tangan yang relevan
                                            ])

                                            <!-- Action Buttons -->
                                            <div class="d-flex justify-content-between mt-4">
                                                <button type="button" class="btn btn-outline-secondary" id="tutup">
                                                    <span class="mdi mdi-arrow-up-bold-circle-outline"></span> Tutup
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-saves-soap"
                                                    id="bsaveSOAP" name="save">
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

@endsection
@section('plugin-erm')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script>
        $(document).ready(function() {
            function submitFormCPPT(actionType) {
                console.log("memek");

                const form = $('#cppt-gizi-form');
                const registrationNumber = "{{ $registration->registration_number }}";
                const registrationType = "{{ $registration->registration_type }}";

                const url =
                    "{{ route('cppt.store', ['type' => '__registration_type__', 'registration_number' => '__registration_number__']) }}"
                    .replace('__registration_type__', registrationType)
                    .replace('__registration_number__', registrationNumber);

                // Ambil CSRF token dari meta tag
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let formData = form.serialize(); // Ambil data dari form

                // Tambahkan tipe aksi (draft atau final) ke data form
                formData += '&action_type=' + actionType;

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
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
            $('#bsaveSOAP').on('click', function() {
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
