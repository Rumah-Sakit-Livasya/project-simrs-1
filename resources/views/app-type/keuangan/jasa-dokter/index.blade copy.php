@extends('inc.layout')
@section('title', 'AP Dokter')
@section('content')
<style>
    /* ... your existing styles ... */

    /* Style for status icons */
    .status-icon {
        cursor: pointer;
    }

    .status-icon.grey {
        color: #999;
        /* Warna abu-abu */
    }

    .status-icon.green {
        color: #00a65a;
        /* Warna hijau */
    }

    /* Style for validation errors in modal */
    #modalValidationErrorMessagesInsideModal {
        margin-top: 15px;
    }

    #modalValidationErrorMessagesInsideModal ul {
        padding-left: 20px;
        margin-bottom: 0;
    }
</style>
<main id="js-page-content" role="main" class="page-content">
    <!-- Search Panel -->
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>AP <span class="fw-300"><i>Dokter</i></span></h2>
                </div>
                <div class="panel-container show py-4 px-3">
                    <div class="panel-content">
                        {{-- Gunakan GET untuk filter --}}
                        <form action="{{ route('keuangan.jasa-dokter.index') }}" method="get" id="filterForm">
                            {{-- @csrf  GET requests don't need CSRF token --}}
                            <div class="row">
                                <!-- Baris 1: 3 kolom -->
                                <div class="col-md-4 mb-3">
                                    <label class="mb-1">Tanggal Bill (Awal)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm datepicker"
                                            name="tanggal_awal" placeholder="Tanggal awal"
                                            value="{{ request('tanggal_awal') ?? '' }}" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text fs-sm">
                                                <i class="fal fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="mb-1">Tanggal Bill (Akhir)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm datepicker"
                                            name="tanggal_akhir" placeholder="Tanggal akhir"
                                            value="{{ request('tanggal_akhir') ?? '' }}" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text fs-sm">
                                                <i class="fal fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="mb-1">Tanggal AP untuk Save Selected</label> {{-- Ganti label agar jelas fungsinya --}}
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm datepicker"
                                            name="tanggal_ap_save" placeholder="Tanggal AP" {{-- Ganti name --}}
                                            value="{{ request('tanggal_ap_save') ?? '' }}" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text fs-sm">
                                                <i class="fal fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris 2 -->
                                <div class="col-md-6 mb-3">
                                    <label class="mb-1">Tipe Registrasi</label>
                                    <select class="form-control form-control-sm select2" name="tipe_registrasi">
                                        <option value="">All</option>
                                        <option value="rawat-jalan"
                                            {{ request('tipe_registrasi') == 'rawat-jalan' ? 'selected' : '' }}>
                                            Rawat Jalan
                                        </option>
                                        <option value="rawat-inap"
                                            {{ request('tipe_registrasi') == 'rawat-inap' ? 'selected' : '' }}>
                                            Rawat Inap
                                        </option>
                                        <option value="igd"
                                            {{ request('tipe_registrasi') == 'igd' ? 'selected' : '' }}>
                                            IGD
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="mb-1">Status Pembayaran Tagihan</label> {{-- Lebih deskriptif --}}
                                    <select class="form-control form-control-sm select2" name="tagihan_pasien">
                                        <option value="">All</option>
                                        <option value="lunas"
                                            {{ request('tagihan_pasien') == 'lunas' ? 'selected' : '' }}>Lunas
                                        </option>
                                        <option value="belum-lunas"
                                            {{ request('tagihan_pasien') == 'belum-lunas' ? 'selected' : '' }}>Belum
                                            Lunas</option>
                                    </select>
                                </div>

                                <!-- Baris 3 -->
                                <div class="col-md-6 mb-3">
                                    <label class="mb-1">Status AP</label>
                                    <select class="form-control form-control-sm select2" name="status_ap">
                                        <option value="">All</option>
                                        <option value="draft" {{ request('status_ap') == 'draft' ? 'selected' : '' }}>
                                            Belum dibuat</option>
                                        <option value="final" {{ request('status_ap') == 'final' ? 'selected' : '' }}>
                                            Sudah dibuat</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="mb-1">Dokter Registrasi</label> {{-- Lebih spesifik --}}
                                    <select class="form-control form-control-sm select2" name="dokter_id">
                                        <option value="">Pilih Dokter</option>
                                        @foreach ($dokters as $dokter)
                                        <option value="{{ $dokter->id }}"
                                            {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                            {{ optional($dokter->employee)->fullname ?? 'Tanpa Nama' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-sm btn-primary mr-2">
                                    <i class="fal fa-search mr-1"></i> Cari
                                </button>
                                {{-- Export button will trigger data retrieval, maybe add custom JS for this --}}
                                <button type="button" class="btn btn-sm btn-success mr-2" id="exportExcelBtn">
                                    <i class="fal fa-file-excel mr-1"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-info mr-2" id="saveApDokterBtn">
                                    <i class="fal fa-save mr-1"></i> Save AP Dokter Selected
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" id="cancelApDokterBtn">
                                    <i class="fal fa-times mr-1"></i> Cancel AP Dokter Selected
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Data Table Panel -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Tagihan Final untuk AP Dokter</i></span></h2>
                        <div class="panel-toolbar">

                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                </button>
                                <strong>Sukses!</strong> {{ session('success') }}
                            </div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                </button>
                                <strong>Error!</strong> {{ session('error') }}
                            </div>
                            @endif
                            {{-- Display validation errors from modal saves if any --}}
                            {{-- Dipindahkan ke dalam modal --}}


                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>No</th>
                                        <th>Tgl Bill</th> {{-- Ini created_at Bilingan --}}
                                        <th>No. RM/No. Reg</th>
                                        <th>Nama Pasien</th>
                                        <th>Detail Tagihan</th> {{-- Ini Nama Tindakan --}}
                                        <th>Nominal Tagihan</th> {{-- Ini Total Tarif Tindakan --}}
                                        <th>Penjamin</th>
                                        <th>JKP</th> {{-- Ini JKP Default dari view --}}
                                        <th>Jasa Dokter</th> {{-- Ini Share Dr Default dari view --}}
                                        <th>Status AP</th> {{-- Kolom Status AP --}}
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($tagihanPasienItems as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            {{-- Checkbox selalu tampil, logika tombol save/cancel yang memproses --}}
                                            <input type="checkbox" name="selected_items[]"
                                                value="{{ $item->id }}">
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        {{-- Tgl Bill diambil dari created_at Bilingan --}}
                                        <td>{{ $item->bilinganSatu->created_at?->format('d-m-Y') ?? '-' }}</td>
                                        <td>{{ $item->registration->patient->medical_record_number ?? '-' }}/{{ $item->registration->registration_number ?? '-' }}
                                        </td>
                                        <td>{{ $item->registration->patient->name ?? '-' }}</td>
                                        {{-- Detail Tagihan diambil dari nama tindakan (sesuai diskusi) --}}
                                        <td>{{ $item->tagihan ?? '-' }}</td>
                                        {{-- Nominal Tagihan diambil dari Total Tarif (sesuai diskusi) --}}
                                        <td>
                                            {{ number_format(
                                                        $item->tindakan_medis?->getTotalTarif(
                                                            $item->registration->penjamin_id ?? null,
                                                            $item->registration->kelas_rawat_id ?? null,
                                                        ) ?? 0,
                                                        2,
                                                        ',',
                                                        '.',
                                                    ) }}
                                        </td>
                                        <td>{{ $item->registration->penjamin->nama_perusahaan ?? '-' }}</td>
                                        <td>0</td> {{-- JKP Default 0 di view --}}
                                        {{-- Jasa Dokter diambil dari Share Dr (sesuai diskusi) --}}
                                        <td>
                                            {{ number_format(
                                                        $item->tindakan_medis?->getShareDr(
                                                            $item->registration->penjamin_id ?? null,
                                                            $item->registration->kelas_rawat_id ?? null,
                                                        ) ?? 0,
                                                        2,
                                                        ',',
                                                        '.',
                                                    ) }}
                                        </td>
                                        <td class="text-center status-cell">
                                            @if ($item->jasaDokter)
                                            <i class="fal fa-check-circle fs-xl green status-icon"
                                                data-toggle="tooltip" data-placement="left"
                                                title="AP Dokter Sudah Dibuat ({{ $item->jasaDokter->ap_number ?? 'N/A' }})"
                                                data-id="{{ $item->id }}"
                                                data-jasa-dokter-id="{{ $item->jasaDokter->id }}"
                                                data-status="final">
                                            </i>
                                            @else
                                            {{-- Icon abu-abu jika belum dibuat --}}
                                            <i class="fal fa-circle fs-xl grey status-icon"
                                                data-toggle="tooltip" data-placement="left"
                                                title="AP Dokter Belum Dibuat" data-id="{{ $item->id }}"
                                                data-status="draft"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data AP Dokter ditemukan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal for creating/editing Jasa Dokter AP --}}
        <div class="modal fade" id="apDokterModal" tabindex="-1" role="dialog"
            aria-labelledby="apDokterModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="apDokterModalTitle">Buat / Edit AP Dokter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Loading indicator --}}
                        <div id="modalLoading" style="display: none; text-align: center; padding: 20px;">
                            <i class="fal fa-spinner-third fa-spin fa-2x"></i> Loading...
                        </div>
                        {{-- Form content --}}
                        <form id="apDokterForm" method="POST">
                            @csrf
                            <input type="hidden" id="tagihan_pasien_id" name="tagihan_pasien_id"
                                value="{{ $tagihan_pasien_id ?? '' }}">
                            <input type="hidden" id="jasa_dokter_id" name="jasa_dokter_id"
                                value="{{ $jasa_dokter_id ?? '' }}">

                            <div class="row">
                                <!-- Pilih Dokter -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dokter</label>
                                        <select name="dokter_id" class="form-control" required>
                                            <option value="">-- Pilih Dokter --</option>
                                            @foreach ($dokters as $dokter)
                                            <option value="{{ $dokter->id }}"
                                                {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                                {{ optional($dokter->employee)->fullname ?? 'Tanpa Nama' }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Nominal (readonly) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nominal</label>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($nominal ?? 0) }}" readonly>
                                    </div>
                                </div>

                                <!-- Diskon (readonly) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Diskon</label>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($diskon ?? 0) }}" readonly>
                                    </div>
                                </div>

                                <!-- Share Dokter -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Share Dokter</label>
                                        <input type="number" class="form-control" name="share_dokter"
                                            value="{{ old('share_dokter', $share_dokter ?? 0) }}" min="0"
                                            required>
                                    </div>
                                </div>

                                <!-- JKP -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>JKP</label>
                                        <input type="number" class="form-control" name="jkp"
                                            value="{{ old('jkp', $jkp ?? 0) }}" min="0">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>

                        {{-- Display validation errors from modal saves if any (inside modal body) --}}
                        <div id="modalValidationErrorMessagesInsideModal"
                            class="alert alert-danger alert-dismissible fade show mt-3" role="alert"
                            style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="fal fa-times"></i></span>
                            </button>
                            <strong>Validasi Error!</strong>
                            <ul id="modalValidationErrorListInsideModal"></ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveModalApDokterBtn">Save
                            changes</button>
                    </div>
                </div>
            </div>
        </div>


</main>
@endsection

@section('plugin')
{{-- ... your existing plugin scripts ... --}}
<script src="/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="/js/datagrid/datatables/datatables.export.js"></script>
<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="/js/dependency/moment/moment.js"></script>
<script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
<script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
<script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
<script src="/js/notifications/toastr/toastr.js"></script>
<link rel="stylesheet" href="/css/notifications/toastr/toastr.css">


<script>
    $(document).ready(function() {
        // Inisialisasi datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            clearBtn: true,
            language: 'id',
            orientation: 'bottom auto',
            templates: {
                leftArrow: '<i class="fal fa-angle-left"></i>',
                rightArrow: '<i class="fal fa-angle-right"></i>'
            }
        });

        // Inisialisasi select2 filter form
        $('.select2').select2({
            dropdownCssClass: "move-up",
            placeholder: "Pilih opsi",
            allowClear: true
        });

        // Inisialisasi select2 modal form saat modal terbuka
        $('#apDokterModal').on('shown.bs.modal', function() {
            // Hanya inisialisasi jika elemen ada dan belum menjadi instance Select2
            $('.select2-modal').each(function() {
                if (!$(this).data('select2')) {
                    $(this).select2({
                        dropdownParent: $(
                            '#apDokterModal .modal-body'
                        ), // Penting untuk posisi dropdown
                        dropdownCssClass: "move-up",
                        placeholder: "-- Pilih Dokter --",
                        allowClear: true
                    });
                }
            });
        });

        // Hancurkan select2 modal form saat modal tertutup
        $('#apDokterModal').on('hidden.bs.modal', function() {
            $('.select2-modal').each(function() {
                if ($(this).data(
                        'select2')) { // Hanya hancurkan jika sudah menjadi instance Select2
                    $(this).select2('destroy');
                }
            });
        });


        // Validasi tanggal pada filter
        $('#filterForm').on('submit', function(e) {
            // Tampilkan loading
            $('#panel-1 .panel-container').append(
                '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
            );
            $('#panel-2 .panel-container').append(
                '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
            );

            const startDate = $('[name="tanggal_awal"]').val();
            const endDate = $('[name="tanggal_akhir"]').val();

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (start > end) {
                    e.preventDefault();
                    toastr.error('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                    $('.panel-loading').remove();
                    return false;
                }
            }
            return true;
        });

        // Inisialisasi datatable
        const table = $('#dt-basic-example').DataTable({
            responsive: true,
            lengthChange: false,
            pageLength: 10,
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                    extend: 'pdfHtml5',
                    text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                    className: 'btn-outline-danger btn-sm mr-1',
                    title: 'Daftar AP Dokter',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    orientation: 'landscape'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                    className: 'btn-outline-success btn-sm mr-1',
                    title: 'Daftar AP Dokter',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fal fa-print mr-1"></i> Print',
                    className: 'btn-outline-primary btn-sm',
                    title: 'Daftar AP Dokter',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }
            ]
        });

        // Enable tooltips
        table.on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('[data-toggle="tooltip"]').tooltip();

        // Check All functionality
        $('#checkAll').on('change', function() {
            $('#dt-basic-example tbody input[type="checkbox"]').prop('checked', $(this).prop(
                'checked'));
        });

        // Handle Save Selected button
        $('#saveApDokterBtn').on('click', function() {
            let selectedIds = [];
            $('#dt-basic-example tbody input[type="checkbox"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // Filter untuk item draft saja
            let idsToCreate = selectedIds.filter(id => {
                const row = $(`#dt-basic-example tbody input[type="checkbox"][value="${id}"]`)
                    .closest('tr');
                return row.find('.status-icon.grey').length > 0;
            });

            if (idsToCreate.length === 0) {
                toastr.warning('Tidak ada item yang dipilih yang statusnya "Belum dibuat AP Dokter".');
                return;
            }

            const tanggalApSave = $('#filterForm [name="tanggal_ap_save"]').val();

            if (!tanggalApSave) {
                toastr.warning('Silakan isi field "Tanggal AP untuk Save Selected" di form filter');
                return;
            }

            Swal.fire({
                title: "Konfirmasi",
                text: `Anda yakin ingin membuat AP Dokter untuk ${idsToCreate.length} item yang dipilih?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Buat!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    const $btn = $(this);
                    $btn.prop('disabled', true).html(
                        '<i class="fal fa-spinner-third fa-spin mr-1"></i> Processing...');

                    $.ajax({
                        url: "{{ route('keuangan.jasa-dokter.store-selected') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            item_ids: idsToCreate,
                            tanggal_ap_save: tanggalApSave
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                window.location.reload();
                            } else {
                                toastr.error(response.message ||
                                    'Gagal membuat AP Dokter');
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message ||
                                xhr.responseText ||
                                'Terjadi kesalahan saat memproses permintaan';
                            toastr.error(errorMessage);
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html(
                                '<i class="fal fa-save mr-1"></i> Save AP Dokter Selected'
                            );
                        }
                    });
                }
            });
        });

        // Handle Cancel Selected button
        $('#cancelApDokterBtn').on('click', function() {
            let selectedIds = [];
            $('#dt-basic-example tbody input[type="checkbox"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // Filter untuk item dengan AP saja
            let idsToCancel = selectedIds.filter(id => {
                const row = $(`#dt-basic-example tbody input[type="checkbox"][value="${id}"]`)
                    .closest('tr');
                return row.find('.status-icon.green').length > 0;
            });

            if (idsToCancel.length === 0) {
                toastr.warning('Tidak ada item yang dipilih yang statusnya "Sudah dibuat AP Dokter".');
                return;
            }

            Swal.fire({
                title: "Konfirmasi",
                text: `Anda yakin ingin membatalkan AP Dokter untuk ${idsToCancel.length} item yang dipilih?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Batalkan!",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    const $btn = $(this);
                    $btn.prop('disabled', true).html(
                        '<i class="fal fa-spinner-third fa-spin mr-1"></i> Processing...');

                    $.ajax({
                        url: "{{ route('keuangan.jasa-dokter.cancel-selected') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            item_ids: idsToCancel
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                window.location.reload();
                            } else {
                                toastr.error(response.message ||
                                    'Gagal membatalkan AP Dokter');
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message ||
                                xhr.responseText ||
                                'Terjadi kesalahan saat memproses permintaan pembatalan';
                            toastr.error(errorMessage);
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html(
                                '<i class="fal fa-times mr-1"></i> Cancel AP Dokter Selected'
                            );
                        }
                    });
                }
            });
        });

        // Handle Status Icon Click (Modal)
        $('#dt-basic-example tbody').on('click', '.status-icon', function() {
            const tagihanId = $(this).data('id');
            const status = $(this).data('status');
            const jasaId = $(this).data('jasa-dokter-id') || null;
            const mode = status === 'draft' ? 'create' : 'edit';
            const idToFetch = mode === 'create' ? tagihanId :
                jasaId; // ID TagihanPasien atau JasaDokter

            // Reset modal form and show loading
            $('#apDokterForm')[0].reset();
            $('.select2-modal').select2('destroy'); // Destroy Select2 before loading new data

            $('#modalLoading').show();
            $('#apDokterForm').hide();
            $('#apDokterModalTitle').text(mode === 'create' ? 'Buat AP Dokter' : 'Edit AP Dokter');
            $('#saveModalApDokterBtn').text(mode === 'create' ? 'Buat AP' : 'Update AP');

            // Set hidden fields and mode
            $('#tagihan_pasien_id').val(tagihanId);
            $('#jasa_dokter_id').val(jasaId);
            $('#modal_mode').val(mode);
            // Set _method spoofing for PUT if in edit mode
            $('#method_spoofing').val(mode === 'edit' ? 'PUT' : 'POST');


            // Setup modal appearance based on mode (show/hide fields)
            if (mode === 'create') {
                $('.modal-edit-only').hide();
                $('.modal-create-only').show();
                $('#modal_ap_date').prop('readonly', false);
                $('#modal_nominal_ap').prop('readonly', false);
                $('#modal_diskon').prop('readonly', false);
                $('#modal_ppn_persen').prop('readonly', false);
                $('#modal_jkp').prop('readonly', false);
                $('#modal_share_dokter_ap').prop('readonly', false);

            } else { // mode === 'edit'
                $('.modal-edit-only').show();
                $('.modal-create-only').hide();
                $('#modal_ap_number').prop('readonly', true);
                $('#modal_ap_date').prop('readonly',
                    true); // AP Date readonly in edit? Sesuaikan kebutuhan
                $('#modal_nominal_ap').prop('readonly',
                    true); // Nominal AP readonly in edit? Sesuaikan kebutuhan
                $('#modal_diskon').prop('readonly',
                    true); // Diskon readonly in edit? Sesuaikan kebutuhan
                $('#modal_ppn_persen').prop('readonly',
                    true); // PPN readonly in edit? Sesuaikan kebutuhan
                $('#modal_jkp').prop('readonly', false); // JKP editable in edit
                $('#modal_share_dokter_ap').prop('readonly', false); // Share Dokter editable in edit
            }


            $('#apDokterModal').modal('show'); // Show modal immediately

            // Fetch data for the modal
            // >>> PERBAIKAN URL DI SINI <<<
            $.ajax({
                url: "{{ url('keuangan/jasa-dokter/modal-data') }}" + '/' +
                    idToFetch, // Gunakan route helper
                method: 'GET',
                data: {
                    mode: mode
                }, // Kirim mode ke controller
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        const modalMode = response
                            .mode; // Pastikan mode dari response konsisten

                        // Populate Reference Data (Readonly) - Common for both modes
                        $('#modal_rm_reg').val(data.rm_reg);
                        $('#modal_nama_pasien').val(data.patient_name);
                        $('#modal_detail_tagihan').val(data.tindakan_medis_name);
                        $('#modal_dokter_reg_display').val(data.dokter_name);
                        $('#modal_penjamin').val(data.penjamin_name);
                        $('#modal_kelas_rawat').val(data.kelas_rawat_name);
                        $('#modal_bill_date_billing_ref').val(data.bill_date_bilingan_ref);
                        $('#modal_nominal_total_tarif_ref').val(formatRupiah(data
                            .nominal_total_tarif_ref));
                        $('#modal_share_dr_default_ref').val(formatRupiah(data
                            .share_dr_default_ref));
                        $('#modal_jkp_default_ref').val(formatRupiah(data.jkp_default_ref));


                        if (modalMode === 'edit') {
                            // Mode Edit: Populate with existing AP data (editable & readonly AP fields)
                            $('#modal_ap_number').val(data.ap_number);
                            $('#modal_ap_date').val(data
                                .ap_date); // Tanggal AP dari data AP
                            $('#modal_bill_date_ap_stored').val(data
                                .bill_date_ap_stored); // Bill Date tersimpan di AP

                            // Populate editable fields with stored AP values
                            $('#modal_dokter_id').val(data.dokter_id).trigger(
                                'change'); // Dokter dari data AP
                            $('#modal_nominal_ap').val(data
                                .nominal_ap); // Nominal dari data AP
                            $('#modal_diskon').val(data.diskon); // Diskon dari data AP
                            $('#modal_ppn_persen').val(data.ppn_persen); // PPN dari data AP
                            $('#modal_jkp').val(data.jkp); // JKP dari data AP
                            $('#modal_share_dokter_ap').val(data
                                .share_dokter_ap); // Share Dokter dari data AP
                            $('#modal_status_ap_internal').val(data
                                .status); // Status AP dari data AP

                        } else { // modalMode === 'create'
                            // Mode Create: Populate with default/reference values
                            $('#modal_ap_date').val(data
                                .default_ap_date); // Default AP date today
                            $('#modal_dokter_id').val(data.default_dokter_id).trigger(
                                'change'); // Default dokter registrasi
                            $('#modal_nominal_ap').val(data
                                .default_nominal_ap
                            ); // Default Nominal AP dari total tarif ref
                            $('#modal_diskon').val(data.default_diskon); // Default Diskon
                            $('#modal_ppn_persen').val(data
                                .default_ppn_persen); // Default PPN
                            $('#modal_jkp').val(data
                                .default_jkp); // Default JKP dari JKP ref
                            $('#modal_share_dokter_ap').val(data
                                .default_share_dokter_ap
                            ); // Default Share Dokter AP dari Share Dr ref
                            $('#modal_status_ap_internal').val(data
                                .default_status); // Default status 'final'
                            // $('#modal_ap_number') is set to 'Akan Dibuat Otomatis' in the HTML/initial JS setup
                            // $('#modal_bill_date_ap_stored') is not applicable for create mode
                        }

                        // Re-initialize select2 after data is loaded and form is shown
                        // This initialization block was already here, which is correct.
                        // The issue was the extra destroy call BEFORE this.
                        // Handled by the shown.bs.modal event now.

                    } else {
                        toastr.error(response.message || 'Gagal mengambil data item.');
                        $('#apDokterModal').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat mengambil data item.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                    $('#apDokterModal').modal('hide');
                },
                complete: function() {
                    $('#modalLoading').hide();
                    $('#apDokterForm').show();
                    // Re-initialize select2 after data is loaded and form is shown
                    $('#apDokterModal').find('.select2-modal').each(function() {
                        if (!$(this).data(
                                'select2'
                            )) { // Only initialize if not already initialized
                            $(this).select2({
                                dropdownParent: $(
                                    '#apDokterModal .modal-body'),
                                dropdownCssClass: "move-up",
                                placeholder: "-- Pilih Dokter --",
                                allowClear: true
                            });
                        }
                    });
                }
            });
        });

        // Handle Save in Modal
        $('#saveModalApDokterBtn').on('click', function() {
            const jasaId = $('#jasa_dokter_id').val();
            const tagihanId = $('#tagihan_pasien_id').val();
            // Dapatkan mode dari hidden field yang diset saat icon diklik
            const mode = $('#modal_mode').val(); // 'create' atau 'edit'

            // Kumpulkan data form
            // Kumpulkan SEMUA data yang BERPOTENSI diedit di modal
            const formData = {
                _token: "{{ csrf_token() }}",
                // Fields yang selalu ada di form (bisa readonly atau editable)
                ap_date: $('#modal_ap_date').val(), // Jika editable
                dokter_id: $('#modal_dokter_id').val(), // Jika editable
                nominal: $('#modal_nominal_ap').val(), // Jika editable
                diskon: $('#modal_diskon').val(), // Jika editable
                ppn_persen: $('#modal_ppn_persen').val(), // Jika editable
                jkp: $('#modal_jkp').val(), // Jika editable
                share_dokter: $('#modal_share_dokter_ap')
                    .val(), // Jika editable (match DB column name)
                status: $('#modal_status_ap_internal').val() // Jika editable
                // Tambahkan field lain jika mereka bisa diedit
                // jasa_dokter: $('#modal_jasa_dokter_ap').val() // Jika kolom ini terpisah dan diedit
            };


            // Tentukan URL dan method
            let url;
            let httpMethod; // Gunakan nama lain agar tidak bingung dengan _method
            if (mode === 'edit') {
                if (!jasaId) {
                    toastr.error('ID AP Dokter tidak ditemukan untuk mode edit.');
                    return;
                }
                // URL untuk UPDATE menggunakan ID JasaDokter
                url = "{{ url('keuangan/jasa-dokter') }}" + '/' + jasaId + '/update';
                httpMethod = 'POST'; // Gunakan POST dengan spoofing
                formData._method = 'PUT'; // Spoofing
            } else if (mode === 'create') {
                if (!tagihanId) {
                    toastr.error('ID Tagihan Pasien tidak ditemukan untuk mode create.');
                    return;
                }
                // URL untuk CREATE SINGLE menggunakan ID TagihanPasien
                url = "{{ url('keuangan/jasa-dokter') }}" + '/' + tagihanId + '/create-single';
                httpMethod = 'POST';
                // Pastikan formData untuk create mengandung semua field yang divalidasi di createSingle
                // Dokter ID, Nominal, Diskon, PPN, JKP, Share Dokter, Status, AP Date
                // formData sudah dikumpulkan di atas
            } else {
                toastr.error('Mode simpan modal tidak dikenali.');
                return;
            }


            // Tampilkan loading di tombol
            const $btn = $(this);
            $btn.prop('disabled', true).html(
                '<i class="fal fa-spinner-third fa-spin mr-1"></i> Processing...');

            // Sembunyikan error validasi sebelumnya
            $('#modalValidationErrorMessagesInsideModal').hide();
            $('#modalValidationErrorListInsideModal').empty();


            // Kirim request
            $.ajax({
                url: url,
                method: httpMethod, // Kirim POST, Laravel akan melihat _method
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#apDokterModal').modal('hide');
                        window.location.reload();
                    } else {
                        // Logika ini biasanya hanya untuk error non-validasi dari server (contoh: 400, 500)
                        toastr.error(response.message || 'Gagal menyimpan AP Dokter');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                    console.error('Save AJAX Error:', xhr.responseText);

                    // Tangani error validasi (status 422)
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $('#modalValidationErrorMessagesInsideModal').show();
                        $('#modalValidationErrorListInsideModal').empty();
                        for (const field in errors) {
                            errors[field].forEach(error => {
                                $('#modalValidationErrorListInsideModal').append(
                                    `<li>${error}</li>`);
                            });
                        }
                        errorMessage =
                            'Validasi Error. Lihat detail di modal.'; // Pesan umum untuk toastr
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    toastr.error(errorMessage);
                },
                complete: function() {
                    $btn.prop('disabled', false).html(mode === 'create' ? 'Buat AP' :
                        'Update AP');
                }
            });
        });

        // Handle Export Excel
        $('#exportExcelBtn').on('click', function() {
            const filters = $('#filterForm').serialize();
            window.location.href = "{{ route('keuangan.jasa-dokter.export-excel') }}?" + filters;
        });

        // Reset validation saat modal ditutup
        $('#apDokterModal').on('hidden.bs.modal', function() {
            $('#modalValidationErrorMessagesInsideModal').hide();
            $('#modalValidationErrorListInsideModal').empty();
            // Pastikan select2 dihancurkan
            $('.select2-modal').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
        });

        // Function to format number as Rupiah (basic)
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) return '0';
            let number_string = parseFloat(angka).toFixed(2).replace(/\.?0*$/, '').toString();
            let split = number_string.split('.');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined && split[1] !== '' ? rupiah + ',' + split[1] : rupiah;
            if (rupiah === '') rupiah = '0';
            return rupiah;
        }

        // Add format Rupiah ke input saat focus out (optional, for visual feedback)
        $('#apDokterModal').on('blur', 'input[type="number"]', function() {
            let value = $(this).val();
            if (value !== '') {
                $(this).val(parseFloat(value).toFixed(
                    2)); // Atau format Rupiah jika tidak mengganggu input
            }
        });


    });
</script>
@endsection