@extends('pages.simrs.pendaftaran.detail-registrasi-pasien')

@push('css-detail-regis')
    {{-- Path CSS disesuaikan dengan template Anda --}}
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <style>
        .select2-container {
            z-index: 1051 !important;
        }

        /* Z-index di atas modal backdrop */
    </style>
@endpush

@section('page-layanan')
    <div class="panel" id="pemakaian-alat-panel" data-registration-id="{{ $registration->id ?? 0 }}">
        <div class="panel-hdr">
            <h2>
                <i class="fas fa-medkit mr-2 text-primary"></i> PEMAKAIAN ALAT
            </h2>
            <div class="panel-toolbar">
                <button type="button" class="btn btn-sm btn-primary waves-effect waves-themed" data-toggle="modal"
                    data-target="#modal-tambah-alat">
                    <span class="fal fa-plus-circle mr-1"></span> Tambah Alat
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary waves-effect waves-themed ml-2"
                    id="btn-reload-alat">
                    <span class="fal fa-sync mr-1"></span> Reload
                </button>
            </div>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <div class="table-responsive">
                    <table id="dt-pemakaian-alat" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Dokter</th>
                                <th>Alat</th>
                                <th>Jml</th>
                                <th>Kelas</th>
                                <th>Lokasi</th>
                                <th>Entry By</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan dimuat oleh server-side DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
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

    @push('modals')
        <div class="modal fade" id="modal-tambah-alat" tabindex="-1" role="dialog" aria-labelledby="modalTambahAlatLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalTambahAlatLabel">Tambah Pemakaian Alat Medis</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <form id="form-tambah-alat">
                        <div class="modal-body">
                            {{-- Input tersembunyi untuk data penting --}}
                            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="departement_id" value="{{ $registration->departement_id }}">
                            <input type="hidden" name="kelas_rawat_id" value="{{ $registration->kelas_rawat_id }}">

                            <div class="form-group row">
                                <label for="tglOrderAlat" class="col-sm-3 col-form-label">Tgl Order</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datepicker" id="tglOrderAlat" name="tanggal_order"
                                        value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="doctor-pemakaian-alat" class="col-sm-3 col-form-label">Dokter</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="doctor-pemakaian-alat" name="doctor_id"
                                        style="width: 100%;" required>
                                        @foreach ($doctorsAlat as $dAlat)
                                            <option value="{{ $dAlat->id }}"
                                                {{ $dAlat->id == $registration->doctor_id ? 'selected' : '' }}>
                                                {{ $dAlat?->employee?->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alat_medis" class="col-sm-3 col-form-label">Alat Medis</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="alat_medis" name="peralatan_id" style="width: 100%;"
                                        required>
                                        <option value="" disabled selected>Pilih Alat Medis</option>
                                        @foreach ($list_peralatan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lokasi" class="col-sm-3 col-form-label">Lokasi</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="lokasi" name="lokasi" style="width: 100%;" required>
                                        <option value="">Pilih Lokasi</option>
                                        <option value="OK">OK</option>
                                        <option value="KTD">KTD</option>
                                        <option value="VK">VK</option>
                                        <option value="LAINNYA">LAINNYA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="qty" name="qty" value="1"
                                        min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-alat">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endsection

@push('script-detail-regis')
    {{-- Path JS disesuaikan dengan template Anda --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    {{-- Script AJAX dan logika halaman --}}
    <script>
        $(document).ready(function() {
            // --- KONFIGURASI & INISIALISASI ---
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const registrationId = $('#pemakaian-alat-panel').data('registration-id');
            const modalTambahAlat = $('#modal-tambah-alat');
            let dtPemakaianAlat;

            // --- DATATABLES ---
            function initDataTable() {
                if (!registrationId) return;

                dtPemakaianAlat = $('#dt-pemakaian-alat').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true, // Menggunakan Server-Side
                    ajax: `/api/simrs/pemakaian-alat/data/${registrationId}`, // Ganti dengan URL API Anda
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tanggal_order',
                            name: 'tanggal_order'
                        },
                        {
                            data: 'doctor_name',
                            name: 'doctor.employee.fullname'
                        },
                        {
                            data: 'alat_name',
                            name: 'alat.nama'
                        },
                        {
                            data: 'qty',
                            name: 'qty'
                        },
                        {
                            data: 'kelas_name',
                            name: 'kelas_rawat.kelas'
                        }, // Asumsi dari relasi
                        {
                            data: 'lokasi',
                            name: 'lokasi'
                        },
                        {
                            data: 'user_name',
                            name: 'user.name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    pageLength: 10,
                    order: [
                        [1, 'desc']
                    ],
                    language: {
                        /* ... Konfigurasi bahasa ... */
                    }
                });
            }

            // --- PENGELOLAAN MODAL & FORM ---
            modalTambahAlat.on('show.bs.modal', function() {
                // Reset form
                const form = $('#form-tambah-alat')[0];
                form.reset();
                modalTambahAlat.find('select').val(null).trigger('change');

                // Inisialisasi plugin di dalam event show.bs.modal
                modalTambahAlat.find('#doctor-pemakaian-alat, #alat_medis, #lokasi').select2({
                    dropdownParent: modalTambahAlat,
                    width: '100%',
                });
                modalTambahAlat.find('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
                // Set dokter default
                modalTambahAlat.find('#doctor-pemakaian-alat').val("{{ $registration->doctor_id }}")
                    .trigger('change');
            });

            // --- PROSES SIMPAN DATA ---
            $('#form-tambah-alat').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const button = $('#btn-save-alat');
                const formData = new FormData(form);

                button.prop('disabled', true).find('.spinner-border').removeClass('d-none');
                button.contents().last().replaceWith(" Menyimpan...");

                $.ajax({
                    url: "{{ route('layanan.rajal.pemakaian_alat.store') }}", // Pastikan route ini benar
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            modalTambahAlat.modal('hide');
                            showSuccessAlert(response.message);
                            dtPemakaianAlat.ajax.reload(); // Muat ulang data di tabel
                        } else {
                            showErrorAlert(response.message || 'Gagal menyimpan data.');
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON ? (xhr.responseJSON.message ||
                            'Terjadi kesalahan.') : 'Terjadi kesalahan.';
                        const validationErrors = xhr.responseJSON ? xhr.responseJSON.errors :
                            null;
                        let fullErrorMsg = errorMsg;
                        if (validationErrors) {
                            fullErrorMsg += '<ul class="text-left pl-4">';
                            $.each(validationErrors, (key, value) => {
                                fullErrorMsg += `<li>${value[0]}</li>`;
                            });
                            fullErrorMsg += '</ul>';
                        }
                        showErrorAlert(fullErrorMsg);
                    },
                    complete: function() {
                        button.prop('disabled', false).find('.spinner-border').addClass(
                            'd-none');
                        button.contents().last().replaceWith(" Simpan");
                    }
                });
            });

            // --- PROSES HAPUS DATA ---
            $('#dt-pemakaian-alat').on('click', '.delete-action', function() {
                const itemId = $(this).data('id');
                const deleteUrl =
                    `/api/simrs/pemakaian-alat/destroy/${itemId}`; // Ganti dengan URL API Anda

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                dtPemakaianAlat.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert(xhr.responseJSON.message ||
                                'Gagal menghapus data.');
                        }
                    });
                });
            });

            // Tombol Reload Tabel
            $('#btn-reload-alat').on('click', function() {
                const btn = $(this);
                btn.find('.fal').removeClass('fa-sync').addClass('fa-spin fa-spinner');
                if (dtPemakaianAlat) {
                    dtPemakaianAlat.ajax.reload(() => {
                        btn.find('.fal').removeClass('fa-spin fa-spinner').addClass('fa-sync');
                    });
                }
            });

            // Jalankan inisialisasi awal
            initDataTable();
        });
    </script>
@endpush
