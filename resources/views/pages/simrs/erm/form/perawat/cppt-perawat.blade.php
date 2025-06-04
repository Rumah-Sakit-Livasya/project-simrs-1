@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
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
                                <div class="card-head collapsed d-flex justify-content-between">
                                    <div class="title">
                                        <header class="text-primary text-center font-weight-bold mb-4">
                                            <h2 class="font-weight-bold">CPPT PERAWAT</h4>
                                        </header>
                                    </div> <!-- Tambahkan judul jika perlu -->
                                    <div class="tools ml-auto">
                                        <!-- Tambahkan ml-auto untuk memindahkan tombol ke kanan -->
                                        <button class="btn btn-primary btnAdd mr-2" id="btnAdd" data-toggle="collapse"
                                            data-parent="#accordion_soap" data-target="#add_soap" aria-expanded="true">
                                            <i class="mdi mdi-plus-circle"></i> Tambah CPPT
                                        </button>
                                        <button class="btn btn-secondary collapsed" data-toggle="collapse"
                                            data-parent="#accordion_soap" data-target="#view-fitler-soap"
                                            aria-expanded="false">
                                            <i class="mdi mdi-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                                <div id="add_soap" class="panel-content collapse in" aria-expanded="true">
                                    <form method="post" class="form-horizontal" id="fsSOAP" autocomplete="off">
                                        <input type="hidden" name="registration_id" value="{{ $registration->id }}" />
                                        <input type="hidden" name="tipe_rawat" value="rawat-jalan" />
                                        <input type="hidden" name="tipe_cppt" value="perawat" />
                                        <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                            value="{{ $registration->patient->medical_record_number }}" />

                                        <!-- Perawat -->
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label for="pid_dokter" class="form-label">Perawat</label>
                                                <select
                                                    class="select2 form-control @error('perawat_id') is-invalid @enderror"
                                                    name="perawat_id" id="perawat_id">
                                                    <option value=""></option>
                                                    @foreach ($perawat as $item)
                                                        <option value="{{ $item->user->id }}">{{ $item->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Two Column Layout for Subjective and Objective -->
                                        <div class="row">
                                            <!-- Subjective -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-primary text-white">
                                                        <span>Subjective</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="subjective" name="subjective" rows="8"
                                                            placeholder="Keluhan Utama">Keluhan Utama: {{ $registration?->pengkajian_nurse_rajal?->keluhan_utama }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Objective -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-success text-white">
                                                        <span>Objective</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="objective" name="objective" rows="8">
Nadi (PR): {{ $registration?->pengkajian_dokter_rajal?->pr }}
Respirasi (RR): {{ $registration?->pengkajian_dokter_rajal?->rr }}
Tensi (BP): {{ $registration?->pengkajian_dokter_rajal?->bp }}
Suhu (T): {{ $registration?->pengkajian_dokter_rajal?->temperatur }}
Tinggi Badan: {{ $registration?->pengkajian_dokter_rajal?->body_height }}
Berat Badan: {{ $registration?->pengkajian_dokter_rajal?->body_weight }}
SPO2 : {{ $registration?->pengkajian_dokter_rajal?->sp02 }}
Skor Nyeri: {{ $registration?->pengkajian_dokter_rajal?->skor_nyeri }}
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Two Column Layout for Assessment and Planning -->
                                        <div class="row">
                                            <!-- Assessment -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div
                                                        class="card-header bg-danger text-white d-flex justify-content-between">
                                                        <span>Assessment</span>
                                                        <span id="diag_perawat" class="badge badge-warning pointer">Diagnosa
                                                            Keperawatan</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="8">
Diagnosa Kerja:
Diagnosa Keperawatan: {{ $registration?->pengkajian_dokter_rajal?->diagnosa_keperawatan }}
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Planning -->
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div
                                                        class="card-header bg-warning text-white d-flex justify-content-between">
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

                                        <!-- Evaluation Section -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-info text-white">
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

                                        <!-- Action Buttons -->
                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary" id="tutup">
                                                <span class="mdi mdi-arrow-up-bold-circle-outline"></span> Tutup
                                            </button>
                                            <button type="button" class="btn btn-primary btn-saves-soap" id="bsSOAP"
                                                name="save">
                                                <span class="mdi mdi-content-save"></span> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Filter Section -->
                                <div id="view-fitler-soap" class="panel-content collapse" aria-expanded="false">
                                    <div class="card-body no-padding">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="s_tgl_1" class="col-sm-4 control-label">Tgl.
                                                        CPPT</label>
                                                    <div class="input-daterange input-group col-sm-8"
                                                        id="demo-date-range">
                                                        <input name="sdate" type="text"
                                                            class="datepicker form-control" id="sdate" readonly />
                                                        <span class="input-group-addon">s/d</span>
                                                        <input name="edate" type="text"
                                                            class="datepicker form-control" id="edate" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="dept" class="col-sm-4 control-label">Status
                                                        Rawat</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control sel2" id="dept" name="dept">
                                                            <option value=""></option>
                                                            <option value="ri">Rawat Inap</option>
                                                            <option value="rj">Rawat Jalan</option>
                                                            <option value="igd">IGD</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="role" class="col-sm-4 control-label">Tipe
                                                        CPPT</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control sel2" id="role" name="role">
                                                            <option value=""></option>
                                                            <option value="dokter">Dokter</option>
                                                            <option value="perawat">Perawat</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Filter Section -->
                            </div>
                        </div>
                    </form>

                    <div class="col-md-6">
                        <hr style="border-color: #868686; margin-bottom: 50px;">
                        <div class="card-body">
                            <div class="table-responsive no-margin">
                                <table id="cppt-dokter" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:25%;">Tanggal</th>
                                            <th style="width: 70%;">Catatan</th>
                                            <th style="width: 6%;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_soap">
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <table width="100%" class="table-soap nurse">
                                                </table>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <!-- Additional rows here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <!-- Pagination will be handled by DataTables -->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!--end .table-responsive -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <hr style="border-color: #868686; margin-bottom: 50px;">
                        <div class="card-body">
                            <div class="table-responsive no-margin">
                                <table id="cppt-perawat" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:25%;">Tanggal</th>
                                            <th style="width: 70%;">Catatan</th>
                                            <th style="width: 6%;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_soap_perawat">
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <table width="100%" class="table-soap nurse">
                                                </table>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <!-- Additional rows here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <!-- Pagination will be handled by DataTables -->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!--end .table-responsive -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
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

            function loadCPPTPerawat() {
                $.ajax({
                    url: '{{ route('cppt.get') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        registration_id: "{{ $registration->id }}",
                    },
                    success: function(response) {
                        // Bersihkan isi tbody
                        $('#list_soap_perawat').empty();

                        // Hancurkan DataTable kalau sudah diinisialisasi
                        if ($.fn.DataTable.isDataTable('#cppt-perawat')) {
                            $('#cppt-perawat').DataTable().destroy();
                        }

                        // Loop data dan tambahkan baris
                        $.each(response, function(index, data) {
                            console.log(data);

                            let formattedDate = new Intl.DateTimeFormat('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                                timeZone: 'Asia/Jakarta'
                            }).format(new Date(data.created_at));
                            var row = `
                                <tr>
                                    <td class="text-center">
                                        <div class="deep-purple-text">
                                            <div class="text-primary mt-3" style="font-weight:400;">${formattedDate}</div>
                                            <div class="text-success mt-3" style="font-weight:400;">${data.tipe_rawat}</div>
                                            <div class="input-oleh text-warning mb-2 mt-1">${data.user.name}</div>
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
                            $('#list_soap_perawat').append(row);
                        });

                        // Inisialisasi ulang DataTable
                        $('#cppt-perawat').DataTable({
                            paging: false,
                            searching: false,
                            ordering: false,
                            responsive: true,
                            pageLength: 5,
                            info: false // <-- ini yang menyembunyikan "Showing x to y of z entries"
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function submitFormCPPT(actionType) {
                const form = $('#cppt-perawat-rajal-form');
                const registrationNumber = "{{ $registration->registration_number }}";

                const url =
                    "{{ route('cppt.dokter-rajal.store', ['type' => 'rawat-jalan', 'registration_number' => '__registration_number__']) }}"
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

            loadCPPTPerawat();
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
            $('#doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });

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
