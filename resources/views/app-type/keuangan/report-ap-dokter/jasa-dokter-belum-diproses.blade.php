@extends('inc.layout')
@section('title', 'Jasa Dokter Belum Diproses')
@section('content')
    <style>
        /* Main styling similar to konfirmasi asuransi */
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

        /* Form styling */
        .form-control {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #eaeaea;
        }

        .select2-selection {
            border: 0 !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
        }

        /* Table styling */
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

        /* Button styling */
        .btn-outline-primary {
            border-color: #021d39;
            color: #021d39;
        }

        .btn-outline-primary:hover {
            background-color: #021d39;
            color: white;
        }

        /* Panel header styling */
        .panel-hdr {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eaeaea;
        }

        /* Datepicker styling */
        .datepicker-dropdown {
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Checkbox styling */
        [type="checkbox"] {
            position: relative;
            width: 18px;
            height: 18px;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>AP Dokter <span class="fw-300"><i>Belum Diverifikasi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tanggal Bill</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_awal" placeholder="Pilih tanggal awal"
                                                        value="{{ request('tanggal_awal') ?? date('Y-m-01') }}"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Sampai</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_akhir" placeholder="Pilih tanggal akhir"
                                                        value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6 mt-3">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Nama Dokter</label>
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
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                        <button type="button" class="btn bg-primary-600 mb-3" id="export-btn">
                                            <span class="fal fa-file-excel mr-1"></span> Export
                                        </button>
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
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Data <span class="fw-300"><i>Jasa Dokter Belum Diproses</i></span></h2>
                        <div class="panel-toolbar">
                            @if (request('tanggal_awal') || request('tanggal_akhir') || request('dokter_id'))
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
                                        Dokter:
                                        {{ $selectedDokter ? $selectedDokter->employee->fullname ?? 'dr. ' . $selectedDokter->id : '' }}
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

                            <table id="dt-jasa-dokter" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" width="30">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No. RM / No. Reg</th>
                                        <th>Nama Pasien</th>
                                        <th>Detail Tagihan</th>
                                        <th>Penjamin</th>
                                        <th>JKP</th>
                                        <th>Jasa Dokter</th>
                                        <th>Status</th>
                                        <th>Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $i => $item)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="select_item[]" value="{{ $item->id }}">
                                            </td>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ $item->registration->patient->medical_record_number ?? '-' }} /
                                                {{ $item->registration->registration_number ?? '-' }}</td>
                                            <td>{{ $item->registration->patient->name ?? '-' }}</td>
                                            <td>{{ $item->detail_tagihan ?? '-' }}</td>
                                            <td>{{ $item->registration->penjamin->nama_perusahaan ?? '-' }}</td>
                                            <td class="text-center">{{ $item->jkp ?? '0' }}%</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($item->jasa_dokter ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge badge-waiting">{{ ucfirst($item->status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-xs btn-outline-primary btn-detail"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fal fa-eye"></i>
                                                </button>
                                                <button class="btn btn-xs btn-outline-success btn-approve"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fal fa-check"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Jasa Dokter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Registrasi</label>
                                    <input type="text" class="form-control" id="detail-registration" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" id="detail-tanggal" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Pasien</label>
                                    <input type="text" class="form-control" id="detail-pasien" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penjamin</label>
                                    <input type="text" class="form-control" id="detail-penjamin" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered child-table">
                                <thead>
                                    <tr>
                                        <th>Jenis Tindakan</th>
                                        <th width="15%">JKP</th>
                                        <th width="20%">Jasa Dokter</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-tindakan">
                                    <!-- Detail tindakan akan diisi via AJAX -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Total</th>
                                        <th class="text-right" id="detail-total">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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

            // Initialize select2
            $('.select2').select2({
                dropdownCssClass: "move-up",
                placeholder: "Pilih Dokter",
                allowClear: true
            });

            // Check all functionality
            $('#checkAll').change(function() {
                $('input[name="select_item[]"]').prop('checked', $(this).prop('checked'));
            });

            // Initialize datatable
            var table = $('#dt-jasa-dokter').DataTable({
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
                        title: 'Daftar Jasa Dokter Belum Diproses',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Jasa Dokter Belum Diproses',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Jasa Dokter Belum Diproses',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    }
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [0, 10]
                    }, // Kolom checkbox dan aksi tidak bisa diurutkan
                    {
                        className: 'text-right',
                        targets: [8]
                    }, // Kolom jumlah rata kanan
                    {
                        className: 'text-center',
                        targets: [0, 1, 7, 9, 10]
                    } // Kolom checkbox, no, jkp, status, aksi rata tengah
                ]
            });

            // Detail button click handler
            $(document).on('click', '.btn-detail', function() {
                var id = $(this).data('id');

                // Here you would typically make an AJAX call to get the details
                // For this example, we'll just show the modal with dummy data
                $('#detail-registration').val('REG-' + id);
                $('#detail-tanggal').val('{{ date('d-m-Y') }}');
                $('#detail-pasien').val('Pasien Contoh');
                $('#detail-penjamin').val('BPJS Kesehatan');

                // Dummy data for tindakan
                var html = '';
                html +=
                    '<tr><td>Tindakan 1</td><td class="text-center">50%</td><td class="text-right">Rp 500.000</td></tr>';
                html +=
                    '<tr><td>Tindakan 2</td><td class="text-center">30%</td><td class="text-right">Rp 300.000</td></tr>';
                $('#detail-tindakan').html(html);
                $('#detail-total').text('Rp 800.000');

                $('#detailModal').modal('show');
            });

            // Approve button click handler
            $(document).on('click', '.btn-approve', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyetujui jasa dokter ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Here you would typically make an AJAX call to approve
                        toastr.success('Jasa dokter berhasil disetujui');
                        // Reload or update the table
                        table.row($(this).closest('tr')).remove().draw();
                    }
                });
            });

            // Export button click handler
            $('#export-btn').click(function() {
                // Here you would typically make an AJAX call to export data
                toastr.info('Fitur export sedang diproses');
            });

            // Form validation and submission
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

                $('#panel-1 .panel-container').append(
                    '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
                );
                return true;
            });
        });
    </script>
@endsection
