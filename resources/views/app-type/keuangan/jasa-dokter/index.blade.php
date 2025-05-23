@extends('inc.layout')
@section('title', 'Jasa Dokter')
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

        /* Table styling */
        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
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
                            <form action="#" method="get">
                                @csrf
                                <div class="row">
                                    <!-- Baris 1: 3 kolom -->
                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Tanggal Bill</label>
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
                                        <label class="mb-1">Sampai</label>
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
                                        <label class="mb-1">Tanggal AP</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_ap" placeholder="Tanggal AP"
                                                value="{{ request('tanggal_ap') ?? '' }}" autocomplete="off">
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
                                        <label class="mb-1">Tagihan Pasien</label>
                                        <select class="form-control form-control-sm select2" name="tagihan_pasien">
                                            <option value="">All</option>
                                            <option value="lunas"
                                                {{ request('tagihan_pasien') == 'lunas' ? 'selected' : '' }}>Lunas</option>
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
                                        <label class="mb-1">Nama Dokter</label>
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
                                    <button type="button" class="btn btn-sm btn-success mr-2">
                                        <i class="fal fa-file-excel mr-1"></i> Export
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info mr-2">
                                        <i class="fal fa-save mr-1"></i> Save AP Dokter
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <i class="fal fa-times mr-1"></i> Cancel AP Dokter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Data Table Panel -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>AP Dokter</i></span></h2>
                        <div class="panel-toolbar">
                            @if (request()->anyFilled([
                                    'tanggal_awal',
                                    'tanggal_akhir',
                                    'tanggal_ap',
                                    'status_ap',
                                    'tipe_registrasi',
                                    'tagihan_pasien',
                                    'dokter_id',
                                ]))
                                <span class="badge bg-primary-600 badge-info p-2">
                                    Filter Aktif:
                                    @if (request('tanggal_awal') && request('tanggal_akhir'))
                                        Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                                    @endif
                                    @if (request('tanggal_ap'))
                                        {{ request('tanggal_awal') ? ' | ' : '' }} Tanggal AP: {{ request('tanggal_ap') }}
                                    @endif
                                    @if (request('status_ap'))
                                        {{ request('tanggal_awal') || request('tanggal_ap') ? ' | ' : '' }}
                                        Status: {{ request('status_ap') == 'belum' ? 'Belum dibuat' : 'Sudah dibuat' }}
                                    @endif
                                    @if (request('tipe_registrasi'))
                                        | Tipe:
                                        {{ request('tipe_registrasi') == 'rawat-jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}
                                    @endif
                                    @if (request('tagihan_pasien'))
                                        | Tagihan: {{ request('tagihan_pasien') == 'umum' ? 'Umum' : 'Asuransi' }}
                                    @endif
                                    @if (request('dokter_id'))
                                        | Dokter: {{ request('dokter_id') == '1' ? 'dr. Andi' : 'dr. Budi' }}
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
                                        <th class="text-center">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>No</th>
                                        <th>Tgl Bill</th>
                                        <th>No. RM/No. Reg</th>
                                        <th>Nama Pasien</th>
                                        <th>Detail Tagihan</th>
                                        <th>Penjamin</th>
                                        <th>JKP</th>
                                        <th>Jasa Dokter</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $i => $item)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="select_item[]" value="{{ $item->id }}">
                                            </td>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ optional($item->registration)->created_at ? \Carbon\Carbon::parse($item->registration->created_at)->format('d-m-Y') : '-' }}
                                            </td>
                                            <td>
                                                {{ optional($item->registration->patient)->medical_record_number ?? '-' }}
                                                /
                                                {{ optional($item->registration)->registration_number ?? '-' }}
                                            </td>
                                            <td>{{ optional($item->registration->patient)->name ?? '-' }}</td>
                                            <td>{{ $item->nama_tindakan ?? '-' }}</td>
                                            <td>{{ optional($item->registration->penjamin)->nama_perusahaan ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ $item->jkp ? number_format($item->jkp, 0, ',', '.') : '-' }}</td>
                                            <td class="text-right">Rp
                                                {{ number_format($item->share_dokter ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->status == 'draft' ? 'badge-waiting' : 'badge-approved' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data jasa dokter.</td>
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

            // Tambahkan validasi range tanggal
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
                placeholder: "Pilih opsi",
                allowClear: true
            });

            // Initialize datatable
            var table = $('#dt-basic-example').DataTable({
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar AP Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar AP Dokter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [9] // Kolom aksi tidak bisa diurutkan
                    },
                    {
                        className: 'text-right',
                        targets: [7] // Kolom jumlah rata kanan
                    },
                    {
                        className: 'text-center',
                        targets: [0, 6, 9] // Kolom nomor, JKP, dan aksi rata tengah
                    }
                ]
            });

            // Form validation and submission
            $('form').on('submit', function(e) {
                $('#panel-1 .panel-container').append(
                    '<div class="panel-loading"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
                );
                return true;
            });

            // Enable tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
