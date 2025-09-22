    @extends('inc.layout')
    @section('title', 'AP Dokter')
    @section('content')
        <style>
            table {
                font-size: 8pt !important;
            }

            .badge-waiting {
                background-color: #f39c12;
                color: white;
            }

            .badge-approved {
                background-color: #00a65a;
                color: white;
            }

            .badge-rejected {
                background-color: #dd4b39;
                color: white;
            }

            .modal-lg {
                max-width: 800px;
            }

            .panel-loading {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999;
            }

            /* PENTING: Tambahkan CSS ini jika belum ada untuk memastikan toggle berfungsi */
            .child-row {
                display: none;
                /* Sembunyikan secara default */
            }

            .dropdown-icon {
                font-size: 14px;
                transition: transform 0.3s ease;
                display: inline-block;
            }

            .dropdown-icon.bxs-down-arrow {
                transform: rotate(180deg);
            }

            /* Styling tambahan untuk memperjelas batas row */
            .child-row td {
                background-color: #f9f9f9;
                border-bottom: 2px solid #ddd;
            }

            /* Pastikan table di dalam child row memiliki margin dan padding yang tepat */
            .child-row td>div {
                padding: 15px;
                margin: 0;
            }

            /* Pastikan parent dan child row terhubung secara visual */
            tr.parent-row.active {
                border-bottom: none !important;
            }

            /* Tambahkan di bagian style */
            .control-details {
                cursor: pointer;
                text-align: center;
                width: 30px;
            }

            .control-details .dropdown-icon {
                font-size: 18px;
                transition: transform 0.3s ease, color 0.3s ease;
                display: inline-block;
                color: #3498db;
                /* Warna biru */
            }

            .control-details .dropdown-icon.bxs-up-arrow {
                transform: rotate(180deg);
                color: #e74c3c;
                /* Warna merah saat terbuka */
            }

            .control-details:hover .dropdown-icon {
                color: #2980b9;
                /* Warna biru lebih gelap saat hover */
            }

            /* Sembunyikan ikon sort bawaan DataTables */
            table.dataTable thead .sorting:after,
            table.dataTable thead .sorting_asc:after,
            table.dataTable thead .sorting_desc:after,
            table.dataTable thead .sorting_asc_disabled:after,
            table.dataTable thead .sorting_desc_disabled:after {
                display: none !important;
            }

            /* Styling untuk child row */
            /* Pastikan content di child row tidak overflow */
            .child-row td>div {
                padding: 15px;
                width: 100%;
            }

            /* Styling untuk tabel di dalam child row */
            .child-table {
                width: 98% !important;
                margin: 10px auto !important;
                border-radius: 4px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .child-table thead th {
                background-color: #021d39;
                color: white;
                font-size: 12px;
                padding: 8px !important;
            }

            .child-table tbody td {
                padding: 8px !important;
                font-size: 12px;
                background-color: white;
            }

            /* Animasi untuk transisi smooth */
            .child-row {
                transition: all 0.3s ease;
            }

            .child-row.show {
                opacity: 1;
            }

            td.control-details::before {
                display: none !important;
            }

            /* Efek hover untuk row */
            #dt-basic-example tbody tr.parent-row:hover {
                background-color: #f8f9fa;
                cursor: pointer;
            }

            /* Warna berbeda untuk child row */
            #dt-basic-example tbody tr.child-row:hover {
                background-color: #f1f1f1;
            }
        </style>
        <main id="js-page-content" role="main" class="page-content">
            <!-- Panel 1 (Search Panel) - col-xl-10 -->
            <div class="row justify-content-center">
                <div class="col-xl-12"> <!-- Diubah dari col-xl-12 menjadi col-xl-10 -->
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
                                            <label class="mb-1">Tanggal AP untuk Save Selected</label>
                                            {{-- Ganti label agar jelas fungsinya --}}
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
                                                <option value="draft"
                                                    {{ request('status_ap') == 'draft' ? 'selected' : '' }}>
                                                    Belum dibuat</option>
                                                <option value="final"
                                                    {{ request('status_ap') == 'final' ? 'selected' : '' }}>
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
            </div>

            <!-- Panel 2 (Data Table) - col-xl-12 tanpa justify-content-center -->
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
                                        <tr class="text-center">
                                            {{-- Checkbox untuk memilih semua --}}
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
                                        @forelse ($jasaDokterItems as $index => $item)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="selected_items[]"
                                                        value="{{ $item->id }}">
                                                </td>
                                                <td>{{ $loop->iteration }}</td>

                                                {{-- Tanggal Billing dari relasi bilinganSatu --}}
                                                <td>
                                                    {{ optional($item->tagihanPasien?->bilinganSatu)->created_at
                                                        ? optional($item->tagihanPasien?->bilinganSatu)->created_at->format('d-m-Y')
                                                        : '-' }}
                                                </td>

                                                {{-- Nomor RM / Registrasi --}}
                                                <td>
                                                    {{ $item->tagihanPasien?->registration?->patient?->medical_record_number ?? '-' }}/
                                                    {{ $item->tagihanPasien?->registration?->registration_number ?? '-' }}
                                                </td>

                                                {{-- Nama Pasien --}}
                                                <td>{{ $item->tagihanPasien?->registration?->patient?->name ?? '-' }}</td>

                                                {{-- Nama Tindakan --}}
                                                <td>{{ $item->nama_tindakan ?? '-' }}</td>

                                                {{-- Nominal --}}
                                                <td>{{ number_format($item->nominal, 2, ',', '.') }}</td>

                                                {{-- Penjamin --}}
                                                <td>{{ $item->tagihanPasien?->registration?->penjamin?->nama_perusahaan ?? '-' }}
                                                </td>

                                                {{-- JKP --}}
                                                <td>{{ number_format($item->jkp ?? 0, 2, ',', '.') }}</td>

                                                {{-- Share Dokter --}}
                                                <td>{{ number_format($item->share_dokter ?? 0, 2, ',', '.') }}</td>

                                                {{-- Status Final / Draft --}}
                                                <td class="text-center status-cell">
                                                    <i class='bx bx-check-circle status-indicator {{ $item->status == 'final' ? 'green' : 'grey' }} status-icon'
                                                        data-toggle="tooltip"
                                                        title="{{ $item->status == 'final' ? 'AP Sudah Dibuat (Klik untuk Edit)' : 'AP Belum Dibuat (Klik untuk Buat)' }}"
                                                        data-id="{{ $item->tagihan_pasien_id }}" {{-- ID of the original TagihanPasien --}}
                                                        data-jasa-dokter-id="{{ $item->id }}" {{-- ID of the JasaDokter (AP) record --}}
                                                        data-status="{{ $item->status }}">
                                                    </i>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    <div class="alert alert-info p-2 m-0">
                                                        <i class="fal fa-info-circle mr-2"></i>
                                                        Tidak ada data AP Dokter ditemukan untuk filter yang dipilih.
                                                    </div>
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
                // $('#dokter').select2({
                //     dropdownCssClass: "move-up",
                //     placeholder: "Pilih opsi",
                //     allowClear: true,
                //     dropdownParent: $('#apDokterModal .modal-body') // Pastikan dropdown muncul di dalam modal
                // });



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
                // Handle Cancel Selected button
                $('#cancelApDokterBtn').on('click', function() {
                    let selectedIds = [];
                    $('#dt-basic-example tbody input[type="checkbox"]:checked').each(function() {
                        selectedIds.push($(this).val());
                    });

                    let idsToCancel = selectedIds.filter(id => {
                        const row = $(`#dt-basic-example tbody input[type="checkbox"][value="${id}"]`)
                            .closest('tr');
                        return row.find('.status-icon.green').length >
                            0; // Check for green (final) status
                    });

                    if (idsToCancel.length === 0) {
                        toastr.warning('Tidak ada item yang dipilih yang statusnya "Sudah dibuat AP Dokter".');
                        return;
                    }

                    Swal.fire({
                        title: "Konfirmasi",
                        text: `Anda yakin ingin membatalkan AP Dokter untuk ${idsToCancel.length} item yang dipilih?`,
                        icon: "warning",
                        // ... (rest of Swal config)
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
                                    item_ids: idsToCancel // These are JasaDokter IDs
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success(response.message);
                                        setTimeout(() => window.location.reload(), 1500);
                                    } else {
                                        toastr.error(response.message ||
                                            'Gagal membatalkan AP Dokter.');
                                    }
                                },
                                error: function(xhr) {
                                    const errorMessage = xhr.responseJSON?.message || xhr
                                        .responseText || 'Terjadi kesalahan.';
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




                    // Fetch data for the modal
                    // >>> PERBAIKAN URL DI SINI <<<
                    $.ajax({
                        url: "{{ route('keuangan.jasa-dokter.get-modal-data', ['jasaDokterId' => '__ID__']) }}"
                            .replace('__ID__', idToFetch),

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

                                }



                            } else {
                                toastr.error(response.message || 'Gagal mengambil data item.');
                                $('#apDokterModal').modal('hide');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX Error:', xhr.responseText);
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
                // Handle Save Selected button
                $('#saveApDokterBtn').on('click', function() {
                    let selectedIds = [];
                    $('#dt-basic-example tbody input[type="checkbox"]:checked').each(function() {
                        selectedIds.push($(this).val());
                    });
                    // console.log('Selected JasaDokter IDs:', selectedIds);

                    let idsToCreate = selectedIds.filter(id => {
                        const row = $(`#dt-basic-example tbody input[type="checkbox"][value="${id}"]`)
                            .closest('tr');
                        const statusBadge = row.find('.status-cell .badge');
                        // Memeriksa apakah statusnya 'Draft' atau 'Belum dibuat' (sesuai teks di badge)
                        const isDraft = statusBadge.hasClass('badge-warning') || statusBadge.text()
                            .trim().toLowerCase() === 'draft' || statusBadge.text().trim()
                            .toLowerCase() === 'final';
                        return isDraft;
                    });
                    // console.log('JasaDokter IDs to Create AP for (Draft):', idsToCreate);

                    if (idsToCreate.length === 0) {
                        toastr.warning(
                            'Tidak ada item "Draft" atau "Final" yang dipilih untuk dibuatkan AP Dokter.'
                        );
                        return;
                    }

                    const tanggalApSave = $('input[name="tanggal_ap_save"]')
                        .val(); // Ambil tanggal AP dari filter
                    if (!tanggalApSave) {
                        toastr.error('Silakan pilih "Tanggal AP untuk Save Selected" terlebih dahulu.');
                        $('input[name="tanggal_ap_save"]').focus();
                        return;
                    }

                    Swal.fire({
                        title: "Konfirmasi",
                        text: `Anda yakin ingin membuat AP Dokter untuk ${idsToCreate.length} item yang dipilih dengan tanggal AP ${tanggalApSave}?`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Buat AP!",
                        cancelButtonText: "Tidak"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const $btn = $(this);
                            $btn.prop('disabled', true).html(
                                '<i class="fal fa-spinner-third fa-spin mr-1"></i> Processing...');

                            $.ajax({
                                url: "{{ route('keuangan.jasa-dokter.store-selected') }}", // Pastikan route ini ada di web.php
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    item_ids: idsToCreate,
                                    tanggal_ap_save: tanggalApSave // Kirim tanggal AP
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success(response.message);
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1500); // Beri waktu toastr tampil
                                    } else {
                                        toastr.error(response.message ||
                                            'Gagal membuat AP Dokter.');
                                    }
                                },
                                error: function(xhr) {
                                    const errorMessage = xhr.responseJSON?.message || xhr
                                        .responseText ||
                                        'Terjadi kesalahan saat memproses permintaan.';
                                    toastr.error(errorMessage);
                                    console.log("AJAX Error Save AP:", xhr.responseText);
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

            // --- Event Handler untuk Klik Ikon Status ---
            $('#dt-basic-example tbody').on('click', '.status-icon', function() {
                const tagihanPasienId = $(this).data('tagihan-pasien-id');
                const jasaDokterId = $(this).data('jasa-dokter-id'); // Ini adalah ID dari tabel jasa_dokter
                const statusAp = $(this).data('status'); // 'draft' atau 'final' dari record jasa_dokter

                console.log(
                    `Status icon clicked. Status AP: ${statusAp}, JasaDokter ID: ${jasaDokterId}, TagihanPasien ID: ${tagihanPasienId}`
                );

                let urlPopup;
                let popupTitlePrefix = "APDokter";

                // KONDISI UTAMA: Jika jasaDokterId ADA, berarti record di tabel jasa_dokter sudah terbentuk,
                // baik statusnya 'draft' maupun 'final'. Maka, kita selalu buka popup edit.
                if (jasaDokterId) {
                    urlPopup = `{{ route('keuangan.jasa-dokter.edit-popup', ['jasaDokter' => ':id']) }}`.replace(':id',
                        jasaDokterId);
                    // Judul popup bisa dibedakan jika mau
                    popupTitlePrefix = statusAp === 'final' ? "EditAPDokterFinal" : "LengkapiAPDokterDraft";
                    console.log(
                        `Opening popup for EXISTING JasaDokter ID: ${jasaDokterId}. Status: ${statusAp}. ACTION: EDIT/LENGKAPI. URL: ${urlPopup}`
                    );
                }
                // KONDISI KEDUA: Jika jasaDokterId TIDAK ADA, DAN statusnya 'draft' (artinya "belum dibuatkan record AP sama sekali"),
                // DAN tagihanPasienId ADA, maka kita buka popup untuk membuat AP baru berdasarkan tagihan tersebut.
                else if (!jasaDokterId && statusAp === 'draft' && tagihanPasienId) {
                    urlPopup = `{{ route('keuangan.jasa-dokter.edit-popup', ['jasaDokter' => ':id']) }}`.replace(':id',
                        jasaDokterId);
                    popupTitlePrefix = "CreateAPDokterBaru";
                    console.log(
                        `Opening CREATE popup for TagihanPasien ID: ${tagihanPasienId}. ACTION: CREATE BARU. URL: ${urlPopup}`
                    );
                }
                // KONDISI LAIN (Error atau tidak valid)
                else {
                    let warningMessage = "Aksi tidak dapat dilakukan. ";
                    if (!tagihanPasienId && statusAp === 'draft' && !jasaDokterId) {
                        warningMessage += "ID Tagihan Pasien tidak ditemukan untuk membuat AP baru.";
                    } else if (!jasaDokterId && statusAp === 'final') {
                        warningMessage += "ID Jasa Dokter tidak ditemukan untuk item yang berstatus final.";
                    } else {
                        warningMessage += "Kombinasi status dan ID tidak valid.";
                    }
                    toastr.warning(warningMessage);
                    console.warn(`Cannot open popup: ${warningMessage}`);
                    return; // Hentikan eksekusi jika tidak ada URL popup yang valid
                }

                // Jika urlPopup berhasil ditentukan, buka popupnya
                const popupWidth = 950;
                const popupHeight = 650;
                const left = (screen.width - popupWidth) / 2;
                const top = (screen.height - popupHeight) / 2;
                const windowName = popupTitlePrefix + "_" + (jasaDokterId || tagihanPasienId); // Nama window unik

                window.open(
                    urlPopup,
                    windowName,
                    `width=${popupWidth},height=${popupHeight},top=${top},left=${left},scrollbars=yes,resizable=yes,status=yes`
                );
            });


            // Handle Export Excel
            $('#exportExcelBtn').on('click', function() {
                const filters = $('#filterForm').serialize();
                window.location.href = "{{ route('keuangan.jasa-dokter.export-excel') }}?" + filters;
            });

            // Akhir dari $(document).ready(function() {
        </script>
    @endsection
