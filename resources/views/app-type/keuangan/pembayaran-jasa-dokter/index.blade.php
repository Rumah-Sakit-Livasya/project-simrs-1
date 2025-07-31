@extends('inc.layout')
@section('title', 'Pembayaran Jasa Dokter')
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

        /* Styling untuk form input */
        .form-control {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0;
        }

        .form-control:focus {
            border-bottom-color: #3c4142;
            box-shadow: none;
        }

        /* Styling untuk select2 */
        .select2-container--default .select2-selection--single {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            border-radius: 0;
            height: auto;
            padding: 0.375rem 0;
        }

        .select2-container--default .select2-selection--single:focus {
            border-bottom-color: #3c4142;
        }

        /* Styling untuk datepicker */
        .input-group .form-control {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            margin-top: 0;
        }

        .input-group-text {
            border-left: 0;
            background-color: #f8f9fa;
        }

        /* Styling untuk table */
        #dt-basic-example thead th {
            color: white !important;
            font-size: 10px !important;
            padding: 8px !important;
            text-align: center;
            vertical-align: middle;
        }

        #dt-basic-example tbody td {
            font-size: 9px !important;
            padding: 6px !important;
            vertical-align: middle;
        }

        /* Status badges */
        .badge-lunas {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
        }

        .badge-belum {
            background-color: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
        }

        /* Button styling */
        .btn-xs {
            padding: 2px 6px;
            font-size: 8px;
            line-height: 1.2;
            border-radius: 3px;
        }

        /* Filter info styling */
        .badge-info {
            font-size: 10px;
            padding: 6px 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            table {
                font-size: 7pt !important;
            }

            .form-group.row .col-xl-4 {
                text-align: left !important;
                margin-bottom: 5px;
            }
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian Pembayaran Jasa Dokter</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.pembayaran-jasa-dokter.index') }}" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Awal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_awal" placeholder="Pilih tanggal awal"
                                                        value="{{ request('tanggal_awal') ?? '' }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Dokter</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" id="dokter_id"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="dokter_id">
                                                    <option value="">Semua Dokter</option>
                                                    @foreach ($dokters as $dokter)
                                                        <option value="{{ $dokter->id }}"
                                                            {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                                            {{ $dokter->employee->fullname ?? 'dr. ' . $dokter->id }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Akhir</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_akhir" placeholder="Pilih tanggal akhir"
                                                        value="{{ request('tanggal_akhir') ?? '' }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Status Pembayaran</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" id="status"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="status">
                                                    <option value="">Semua Status</option>
                                                    <option value="lunas"
                                                        {{ request('status') == 'lunas' ? 'selected' : '' }}>
                                                        Lunas
                                                    </option>
                                                    <option value="belum"
                                                        {{ request('status') == 'belum' ? 'selected' : '' }}>
                                                        Belum Lunas
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <a href="{{ route('keuangan.pembayaran-jasa-dokter.create') }}"
                                            class="btn bg-primary-600 mb-3" id="create-btn">
                                            <span class="fal fa-plus mr-1"></span> Tambah Pembayaran
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Pembayaran Jasa Dokter</i></span></h2>
                        <div class="panel-toolbar">
                            @if (request('tanggal_awal') || request('tanggal_akhir') || request('dokter_id') || request('status'))
                                <span class="badge bg-primary-600 badge-info p-2">
                                    Filter Aktif:
                                    @if (request('tanggal_awal') && request('tanggal_akhir'))
                                        Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                                    @endif
                                    @if (request('dokter_id'))
                                        @php
                                            $selectedDokter = $dokters->firstWhere('id', request('dokter_id'));
                                        @endphp
                                        {{ request('tanggal_awal') ? ' | ' : '' }}
                                        Dokter: {{ $selectedDokter ? $selectedDokter->employee->fullname : '' }}
                                    @endif
                                    @if (request('status'))
                                        {{ request('tanggal_awal') || request('dokter_id') ? ' | ' : '' }}
                                        Status: {{ ucfirst(request('status')) }}
                                    @endif
                                </span>
                            @endif
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

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Nama Dokter</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Kas/Bank</th>
                                        <th>Pajak</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>{{ $item->dokter->employee->fullname ?? '-' }}</td>
                                            <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                                            <td>{{ $item->bank->name ?? '-' }}</td>
                                            <td class="text-center">{{ $item->pajak_persen ?? '0' }}%</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($item->nominal ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                @if (isset($item->status))
                                                    @if ($item->status == 'lunas')
                                                        <span class="badge badge-lunas">Lunas</span>
                                                    @else
                                                        <span class="badge badge-belum">Belum Lunas</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-belum">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('keuangan.pembayaran-jasa-dokter.show', $item->id) }}"
                                                    class="btn btn-xs btn-info" data-toggle="tooltip" title="Detail">
                                                    <i class="fal fa-eye"></i>
                                                </a>

                                                <a href="{{ route('keuangan.pembayaran-jasa-dokter.edit', $item->id) }}"
                                                    class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                                                    <i class="fal fa-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-xs btn-success"
                                                    data-toggle="tooltip" title="Cetak Bukti"
                                                    onclick="printReceipt({{ $item->id }})">
                                                    <i class="fal fa-print"></i>
                                                </button>

                                                <form
                                                    action="{{ route('keuangan.pembayaran-jasa-dokter.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data pembayaran ini?')"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Tidak ada data pembayaran</td>
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
            // Initialize datepickers
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

            // Validasi range tanggal
            $('form').on('submit', function(e) {
                var startDate = $('[name="tanggal_awal"]').val();
                var endDate = $('[name="tanggal_akhir"]').val();

                if (startDate && endDate) {
                    var start = new Date(startDate);
                    var end = new Date(endDate);

                    if (start > end) {
                        e.preventDefault();
                        toastr.error('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                        return false;
                    }
                }

                return true;
            });

            // Initialize select2
            $('.select2').select2({
                dropdownCssClass: "move-up",
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Set placeholder untuk setiap select2
            $('#dokter_id').attr('data-placeholder', 'Pilih Dokter');
            $('#status').attr('data-placeholder', 'Pilih Status');

            // Initialize money format
            $('.money').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                autoGroup: true,
                digits: 0,
                digitsOptional: false,
                prefix: 'Rp ',
                placeholder: '0',
                rightAlign: false
            });

            // Initialize datatable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 20,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                        className: 'btn-outline-danger btn-sm mr-1',
                        title: 'Daftar Pembayaran Jasa Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude action column
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Pembayaran Jasa Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude action column
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Pembayaran Jasa Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude action column
                        }
                    }
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [0, 8] // Kolom nomor dan aksi tidak bisa diurutkan
                    },
                    {
                        className: 'text-right',
                        targets: [6] // Kolom nominal rata kanan
                    },
                    {
                        className: 'text-center',
                        targets: [0, 5, 7, 8] // Kolom nomor, pajak, status, dan aksi rata tengah
                    }
                ],
                order: [
                    [1, 'desc']
                ], // Urutkan berdasarkan tanggal terbaru
                language: {
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Form validation and submission
            $('form[action="{{ route('keuangan.pembayaran-jasa-dokter.index') }}"]').on('submit', function(e) {
                var tanggalAwal = $('[name="tanggal_awal"]').val();
                var tanggalAkhir = $('[name="tanggal_akhir"]').val();

                if (tanggalAwal && tanggalAkhir) {
                    var startDate = new Date(tanggalAwal);
                    var endDate = new Date(tanggalAkhir);

                    if (startDate > endDate) {
                        e.preventDefault();
                        toastr.error('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                        return false;
                    }
                }

                $('#panel-1 .panel-container').append(
                    '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
                );
                return true;
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Function untuk cetak bukti pembayaran
            window.printReceipt = function(id) {
                // Implementasi cetak bukti pembayaran
                window.open('/keuangan/pembayaran-jasa-dokter/' + id + '/print', '_blank');
            };

            // Auto-hide success alert after 5 seconds
            setTimeout(function() {
                $('.alert-success').fadeOut('slow');
            }, 5000);

            // Konfirmasi delete dengan SweetAlert
            $(document).on('submit', 'form[method="POST"]', function(e) {
                if ($(this).find('input[name="_method"][value="DELETE"]').length) {
                    e.preventDefault();
                    var form = this;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pembayaran ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
