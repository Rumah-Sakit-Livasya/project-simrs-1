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

        .status-icon {
            font-size: 12px;
            /* Membuat ikon lebih terlihat */
            cursor: pointer;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .status-icon:hover {
            transform: scale(1.2);
            /* Efek hover */
        }

        .status-icon.green {
            color: #00a65a;
            /* Hijau untuk status 'final' / sudah dibuat */
        }

        .status-icon.grey {
            color: #808080;
            /* Abu-abu/hitam untuk status 'draft' / belum dibuat / dibatalkan */
        }

        td.control-details::before {
            display: none !important;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Panel 1 (Search Panel) -->
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
                                <div class="row">
                                    <!-- Baris 1 -->
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
                                                value="{{ old('tanggal_akhir', date('Y-m-d')) }}" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Tanggal AP untuk Save Selected</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_ap_save" placeholder="Tanggal AP"
                                                value="{{ old('tanggal_ap_save', date('Y-m-d')) }}" autocomplete="off">
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
                                        <label class="mb-1">Status Pembayaran Tagihan</label>
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
                                        <label class="mb-1">Dokter Registrasi</label>
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

        <!-- Panel 2 (Data Table) -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Tagihan Final untuk AP Dokter</i></span></h2>
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

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr class="text-center">
                                        <th class="text-center"><input type="checkbox" id="checkAll"></th>
                                        <th>No</th>
                                        <th>Tgl Bill</th>
                                        <th>No. RM/No. Reg</th>
                                        <th>Nama Pasien</th>
                                        <th>Detail Tagihan</th>
                                        <th>Penjamin</th>
                                        <th>JKP</th>
                                        <th>Jasa Dokter</th>
                                        <th>Status AP</th>
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
                                            <td>
                                                {{ optional($item->tagihanPasien?->bilinganSatu)->created_at
                                                    ? optional($item->tagihanPasien?->bilinganSatu)->created_at->format('d-m-Y')
                                                    : '-' }}
                                            </td>
                                            <td>
                                                {{ $item->tagihanPasien?->registration?->patient?->medical_record_number ?? '-' }}/
                                                {{ $item->tagihanPasien?->registration?->registration_number ?? '-' }}
                                            </td>
                                            <td>{{ $item->tagihanPasien?->registration?->patient?->name ?? '-' }}</td>
                                            <td>{{ $item->nama_tindakan ?? '-' }}</td>
                                            {{-- <td>{{ number_format($item->nominal, 2, ',', '.') }}</td> --}}
                                            <td>{{ $item->tagihanPasien?->registration?->penjamin?->nama_perusahaan ?? '-' }}
                                            </td>
                                            <td>{{ number_format($item->jkp ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ number_format($item->share_dokter ?? 0, 2, ',', '.') }}</td>
                                            <td class="text-center status-cell">
                                                <i class='bx bx-check-circle status-indicator {{ $item->status == 'final' ? 'green' : 'grey' }} status-icon'
                                                    data-toggle="tooltip"
                                                    title="{{ $item->status == 'final' ? 'AP Sudah Dibuat (Klik untuk Edit)' : 'AP Belum Dibuat / Dibatalkan (Klik untuk Buat/Edit)' }}"
                                                    data-tagihan-pasien-id="{{ $item->tagihan_pasien_id }}"
                                                    data-jasa-dokter-id="{{ $item->id }}"
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

            // Validasi tanggal pada filter
            $('#filterForm').on('submit', function(e) {
                $('#panel-1 .panel-container, #panel-2 .panel-container').append(
                    '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
                );

                const startDate = $('[name="tanggal_awal"]').val();
                const endDate = $('[name="tanggal_akhir"]').val();

                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    e.preventDefault();
                    toastr.error('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                    $('.panel-loading').remove();
                    return false;
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
                buttons: [
                    // Buttons disembunyikan jika ingin menggunakan tombol custom
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

                // Filter hanya untuk item yang belum final (draft)
                let idsToCreate = selectedIds.filter(id => {
                    const row = $(`#dt-basic-example tbody input[type="checkbox"][value="${id}"]`)
                        .closest('tr');
                    return row.find('.status-icon.grey').length > 0;
                });

                if (idsToCreate.length === 0) {
                    toastr.warning('Tidak ada item yang dipilih dengan status "Belum Dibuat".');
                    return;
                }

                const tanggalApSave = $('#filterForm [name="tanggal_ap_save"]').val();
                if (!tanggalApSave) {
                    toastr.warning('Silakan isi field "Tanggal AP untuk Save Selected" terlebih dahulu.');
                    return;
                }

                Swal.fire({
                    title: "Konfirmasi",
                    text: `Anda yakin ingin membuat AP Dokter untuk ${idsToCreate.length} item yang dipilih dengan tanggal AP ${tanggalApSave}?`,
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
                                    // Reload halaman untuk melihat perubahan status dan warna ikon
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    toastr.error(response.message ||
                                        'Gagal membuat AP Dokter');
                                }
                            },
                            error: function(xhr) {
                                const errorMessage = xhr.responseJSON?.message ||
                                    'Terjadi kesalahan.';
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

                // Filter hanya untuk item yang sudah final
                let idsToCancel = selectedIds.filter(id => {
                    const row = $(`#dt-basic-example tbody input[type="checkbox"][value="${id}"]`)
                        .closest('tr');
                    return row.find('.status-icon.green').length > 0;
                });

                if (idsToCancel.length === 0) {
                    toastr.warning(
                        'Tidak ada item yang dipilih dengan status "Sudah Dibuat" untuk dibatalkan.');
                    return;
                }

                Swal.fire({
                    title: "Konfirmasi Pembatalan",
                    text: `Anda yakin ingin membatalkan AP Dokter untuk ${idsToCancel.length} item yang dipilih? Status akan kembali menjadi draft.`,
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
                                    // Reload halaman untuk melihat ikon berubah kembali menjadi abu-abu
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    toastr.error(response.message ||
                                        'Gagal membatalkan AP Dokter.');
                                }
                            },
                            error: function(xhr) {
                                const errorMessage = xhr.responseJSON?.message ||
                                    'Terjadi kesalahan.';
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

            // Handle Status Icon Click (Popup Window)
            $('#dt-basic-example tbody').on('click', '.status-icon', function() {
                const tagihanPasienId = $(this).data('tagihan-pasien-id');
                const jasaDokterId = $(this).data('jasa-dokter-id');
                const statusAp = $(this).data('status');

                let urlPopup;
                let popupTitlePrefix = "APDokter";

                // Jika jasaDokterId ada, berarti record sudah ada (baik draft/final), maka selalu EDIT.
                if (jasaDokterId) {
                    urlPopup = `{{ route('keuangan.jasa-dokter.edit-popup', ['jasaDokter' => ':id']) }}`
                        .replace(':id', jasaDokterId);
                    popupTitlePrefix = statusAp === 'final' ? "EditAPDokterFinal" : "LengkapiAPDokterDraft";
                }
                // Jika tidak ada jasaDokterId tapi ada tagihanPasienId, berarti ini adalah pembuatan AP BARU.
                // Note: Logika ini mungkin perlu disesuaikan dengan alur backend, tapi asumsinya,
                // setiap item di list sudah punya record di jasa_dokter (meskipun statusnya draft).
                else {
                    toastr.warning("Aksi tidak valid: ID Jasa Dokter tidak ditemukan.");
                    return; // Hentikan jika tidak ada ID
                }

                const popupWidth = 950;
                const popupHeight = 650;
                const left = (screen.width - popupWidth) / 2;
                const top = (screen.height - popupHeight) / 2;
                const windowName = popupTitlePrefix + "_" + jasaDokterId;

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
        });
    </script>
@endsection
