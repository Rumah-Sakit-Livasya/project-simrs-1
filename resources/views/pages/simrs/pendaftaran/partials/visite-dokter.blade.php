@extends('pages.simrs.pendaftaran.detail-registrasi-pasien')
@section('page-layanan')
    <div class="panel" id="visite-dokter-panel" data-registration-id="{{ $registration->id ?? null }}">
        <div class="panel-hdr">
            <h2>
                <i class="mdi mdi-stethoscope mr-2"></i> Visite Dokter
                <span class="fw-300"><i>Data Kunjungan Dokter</i></span>
            </h2>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <table id="dt-visite-dokter" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Waktu</th>
                            <th>Dokter</th>
                            <th>Kelas</th>
                            <th>User Input</th>
                            <th>Status Billing</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data diisi oleh server-side DataTables --}}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-center">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-themed"
                                    data-toggle="modal" data-target="#modal-tambah-visite">
                                    <span class="fal fa-plus-circle mr-1"></span>
                                    Tambah Visite
                                </button>
                            </th>
                        </tr>
                    </tfoot>
                </table>
                @if (str_contains(\Illuminate\Support\Facades\Route::currentRouteName(), 'daftar-registrasi-pasien') ||
                        str_contains(url()->current(), '/daftar-registrasi-pasien/'))
                    <div class="d-flex justify-content-start m-3">
                        <a href="{{ route('detail.registrasi.pasien', ['registrations' => $registration->id]) }}"
                            class="btn btn-outline-primary px-4 shadow-sm d-flex align-items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Kembali ke Menu</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH VISITE DOKTER --}}
    <div class="modal fade" id="modal-tambah-visite" tabindex="-1" role="dialog" aria-hidden="true"
        data-default-doctor-id="{{ $registration->doctor_id }}">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Visite Dokter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="form-tambah-visite">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="visit_date">Tanggal & Waktu Visite</label>
                            <input type="text" id="visit_date" name="visit_date" class="form-control datetimepicker"
                                placeholder="Pilih tanggal dan waktu" required value="{{ now()->format('Y-m-d H:i') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="doctor_id_visite">Dokter</label>
                            <select id="doctor_id_visite" name="doctor_id" class="form-control" required
                                style="width: 100%;">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->employee?->fullname ?? ($doctor->fullname ?? ($doctor->name ?? 'Tanpa Nama')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kelas Rawat Pasien</label>
                            <input type="text" class="form-control"
                                value="{{ $registration->kelas_rawat?->kelas ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="btn-simpan-visite">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script-detail-regis')
    <script>
        $(document).ready(function() {
            let tableVisiteDokter;

            // Fungsi helper untuk mendapatkan ID registrasi dari panel
            function getRegistrationId() {
                return $("#visite-dokter-panel").data("registration-id");
            }

            // --- INISIALISASI DATATABLE ---
            function initDataTableVisiteDokter() {
                const registrationId = getRegistrationId();
                if (!registrationId) {
                    console.warn("ID Registrasi tidak ditemukan, DataTables Visite tidak dapat dimuat.");
                    return;
                }

                // Hancurkan tabel jika sudah ada untuk re-inisialisasi
                if ($.fn.DataTable.isDataTable("#dt-visite-dokter")) {
                    $("#dt-visite-dokter").DataTable().destroy();
                }

                tableVisiteDokter = $("#dt-visite-dokter").DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: `/api/simrs/visite/get-data/${registrationId}`,
                        type: "GET",
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "visit_date",
                            name: "visit_date"
                        },
                        {
                            data: "doctor",
                            name: "doctor"
                        },
                        {
                            data: "class_name",
                            name: "class_name"
                        },
                        {
                            data: "user_by",
                            name: "user_by"
                        },
                        {
                            data: "is_billed",
                            name: "is_billed"
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                            className: "text-center"
                        },
                    ],
                    pageLength: 10,
                    order: [
                        [1, "desc"]
                    ],
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                            extend: "pdfHtml5",
                            text: "PDF",
                            titleAttr: "Generate PDF",
                            className: "btn-outline-danger btn-sm mr-1"
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            titleAttr: "Generate Excel",
                            className: "btn-outline-success btn-sm mr-1"
                        },
                        {
                            extend: "print",
                            text: "Print",
                            titleAttr: "Print Table",
                            className: "btn-outline-primary btn-sm"
                        }
                    ],
                    language: {
                        emptyTable: "Belum ada data visite dokter.",
                        processing: '<i class="fa fa-spinner fa-spin"></i> Memuat data...'
                    }
                });

            }

            // Panggil inisialisasi saat dokumen siap
            initDataTableVisiteDokter();

            // --- PENGELOLAAN MODAL ---
            const modalTambahVisite = $('#modal-tambah-visite');
            const loadingOverlay = modalTambahVisite.find('.modal-loading-overlay');

            modalTambahVisite.on('show.bs.modal', function() {
                loadingOverlay.hide(); // Sembunyikan loading saat modal baru akan ditampilkan

                // Reset form
                $('#form-tambah-visite')[0].reset();
                $('#form-tambah-visite select').val(null).trigger('change');

                // Inisialisasi Select2
                $('#doctor_id_visite').select2({
                    dropdownParent: modalTambahVisite,
                    placeholder: '-- Pilih Dokter --',
                    width: '100%',
                });

                // Set dokter default dari data-attribute
                const defaultDoctorId = $(this).data("default-doctor-id");
                $('#doctor_id_visite').val(defaultDoctorId).trigger('change');
            });

            // --- PROSES SIMPAN DATA ---
            $('#form-tambah-visite').on('submit', function(event) {
                event.preventDefault();
                const form = this;
                const button = $('#btn-simpan-visite');
                const registrationId = getRegistrationId();
                const formData = $(form).serialize();

                button.prop("disabled", true).find(".spinner-border").removeClass("d-none");
                button.contents().last().replaceWith(" Menyimpan...");

                $.ajax({
                    url: `/api/simrs/visite/store/${registrationId}`,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            modalTambahVisite.modal('hide');
                            showSuccessAlert(response.message);
                            tableVisiteDokter.ajax.reload(); // Muat ulang data di tabel
                        } else {
                            showErrorAlert(response.message || 'Gagal menyimpan data.');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan pada server.';
                        if (xhr.responseJSON) {
                            if (xhr.status === 422) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat()
                                    .join('<br>');
                            } else {
                                errorMessage = xhr.responseJSON.message || errorMessage;
                            }
                        }
                        // Tampilkan pesan error menggunakan showErrorAlert
                        showErrorAlertNotRefresh(errorMessage);
                    },
                    complete: function() {
                        button.prop("disabled", false).find(".spinner-border").addClass(
                            "d-none");
                        button.contents().last().replaceWith(" Simpan");
                    }
                });
            });

            // --- PROSES HAPUS DATA ---
            function deleteVisiteDokter(url) {
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                tableVisiteDokter.ajax.reload();
                            } else {
                                showErrorAlert(response.message || 'Gagal menghapus data.');
                            }
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                                "Gagal menghapus data.";
                            showErrorAlert(errorMsg);
                        }
                    });
                });
            }

            // Ekspos fungsi delete ke window agar bisa dipanggil dari HTML yang di-render DataTable
            window.deleteVisiteDokter = deleteVisiteDokter;

        });
    </script>
@endpush
