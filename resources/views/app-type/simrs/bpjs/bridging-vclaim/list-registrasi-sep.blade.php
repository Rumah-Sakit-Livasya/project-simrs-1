@extends('inc.layout')
@section('title', 'List Registrasi SEP Peserta')

@section('extended-css')
    {{-- CSS untuk DataTables sudah ada di layout utama (datatables.bundle.css) --}}
    <style>
        /* Class untuk highlight baris yang belum ada SEP */
        .row-sep-null {
            background-color: #fff3cd !important;
        }

        /* Class untuk child row (detail) */
        table.detail-table {
            width: 100%;
            background-color: #f8f9fa;
        }

        .detail-table td {
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }

        .detail-table td:first-child {
            font-weight: bold;
            width: 150px;
        }

        /* Tombol child row control */
        td.details-control {
            background: url('{{ asset('img/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }

        tr.details td.details-control {
            background: url('{{ asset('img/details_close.png') }}') no-repeat center center;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fas fa-search mr-2"></i> Form Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-search">
                                <div class="row">
                                    {{-- Kolom Kiri --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tgl1" class="form-label">Awal Periode</label>
                                            <input name="tgl1" type="text" class="form-control bg-white"
                                                id="tgl1" value="{{ date('d-m-Y') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Layanan</label>
                                            <select class="form-control select2" name="layanan" id="layanan">
                                                <option value="">SEMUA</option>
                                                <option value="f">RAWAT JALAN</option>
                                                <option value="t">RAWAT INAP</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">No SEP</label>
                                            <input type="text" value="" id="nosep" name="nosep"
                                                class="form-control">
                                        </div>
                                    </div>
                                    {{-- Kolom Kanan --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tgl2" class="form-label">Akhir Periode</label>
                                            <input name="tgl2" type="text" class="form-control bg-white"
                                                id="tgl2" value="{{ date('d-m-Y') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Poliklinik</label>
                                            <select name="did" class="select2" id="did">
                                                <option value="">SEMUA POLIKLINIK</option>
                                                {{-- CATATAN: Pastikan variabel di controller adalah $departements --}}
                                                @foreach ($departements as $departement)
                                                    <option value="{{ $departement->id }}">{{ $departement->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">No Rujukan</label>
                                            <input type="text" value="" id="norujuk" name="norujuk"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex">
                            <button type="button" class="btn btn-primary ml-auto" id="btSearch">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fas fa-list-alt mr-2"></i> Daftar Pasien BPJS Kesehatan</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="registrasi-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th></th> {{-- Kolom untuk child row control --}}
                                        <th>Tgl Reg</th>
                                        <th>Tgl SEP</th>
                                        <th>Nama Pasien</th>
                                        <th>No Reg</th>
                                        <th>departement</th>
                                        <th>No Kartu</th>
                                        <th>No SEP</th>
                                        <th>No Rujukan</th>
                                        <th>Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan diisi oleh Server-Side DataTables --}}
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
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                width: '100%'
            });

            // Inisialisasi Datepicker
            $('#tgl1, #tgl2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            function formatDetail(d) {
                // `d` adalah objek JSON yang dikembalikan dari AJAX detail
                return `<table class="detail-table">
                    <tr><td>Nama Peserta:</td><td>${d.nama}</td></tr>
                    <tr><td>Jenis Peserta:</td><td>${d.jnspeserta}</td></tr>
                    <tr><td>Diagnosa:</td><td>${d.diagnosa}</td></tr>
                    <tr><td>Kelas Rawat:</td><td>${d.kelasrawat}</td></tr>
                    <tr><td>Hak Kelas:</td><td>${d.hakkelas}</td></tr>
                    <tr><td>Jenis Pelayanan:</td><td>${d.jnspelayanan}</td></tr>
                    <tr><td>Poli:</td><td>${d.poli}</td></tr>
                </table>`;
            }

            // Inisialisasi DataTable
            var table = $('#registrasi-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-data-sep') }}",
                    type: "POST",
                    data: function(d) {
                        // Mengirim data filter dari form
                        d._token = "{{ csrf_token() }}";
                        d.tgl1 = $('#tgl1').val();
                        d.tgl2 = $('#tgl2').val();
                        d.layanan = $('#layanan').val();
                        d.did = $('#did').val();
                        d.nosep = $('#nosep').val();
                        d.norujuk = $('#norujuk').val();
                    }
                },
                columns: [{
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: 'tglreg',
                        name: 'tglreg'
                    },
                    {
                        data: 'tglsep',
                        name: 'tglsep'
                    },
                    {
                        data: 'nama_pasien',
                        name: 'nama_pasien'
                    },
                    {
                        data: 'no_reg',
                        name: 'no_reg'
                    },
                    {
                        data: 'departement',
                        name: 'departement'
                    },
                    {
                        data: 'nokartu',
                        name: 'nokartu'
                    },
                    {
                        data: 'sep',
                        name: 'sep'
                    },
                    {
                        data: 'norujukan',
                        name: 'norujukan'
                    },
                    {
                        data: 'id',
                        name: 'fungsi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Rute perlu dibuat jika belum ada
                            let printUrl = `{{ url('vclaim/print_sep_pdf') }}/${row.id}`;
                            let updateTglUrl = `{{ url('vclaim/update_tgl_pulang') }}/${row.id}`;
                            let editUrl = `{{ url('vclaim/edit_sep') }}/${row.id}`;

                            let buttons = `<div class="btn-group btn-group-sm">
                                <a href="javascript:void(0);" onclick="popupwindow('${printUrl}','Print SEP', 1100, 900, 'no');" class="btn btn-success" data-toggle="tooltip" title="Print SEP"><i class="fas fa-print"></i></a>
                                <a href="javascript:void(0);" onclick="popupwindow('${updateTglUrl}','Update Tgl Pulang', 500, 400, 'no');" class="btn btn-warning" data-toggle="tooltip" title="Update Tanggal Pulang"><i class="fas fa-calendar-alt"></i></a>
                                <a href="javascript:void(0);" onclick="popupFull('${editUrl}');" class="btn btn-danger" data-toggle="tooltip" title="Edit SEP"><i class="fas fa-edit"></i></a>
                            </div>`;
                            return buttons;
                        }
                    }
                ],
                order: [
                    [1, 'desc']
                ], // Default order by Tgl Reg desc
                createdRow: function(row, data, dataIndex) {
                    if (data.tglsep === '-') {
                        $(row).addClass('row-sep-null');
                    }
                },
                drawCallback: function(settings) {
                    // Inisialisasi tooltip setelah tabel digambar ulang
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            // Handle klik tombol Cari
            $('#btSearch').on('click', function() {
                table.draw();
            });

            // Handle child row expand/collapse
            $('#registrasi-table tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('details');
                } else {
                    // Open this row
                    $.get("{{ url('bpjs/bridging-vclaim/detail-registrasi') }}/" + row.data().id, function(
                        data) {
                        row.child(formatDetail(data)).show();
                        tr.addClass('details');
                    });
                }
            });
        });

        // Fungsi helper untuk popup (disimpan dari kode asli)
        function popupwindow(url, title, w, h, scroll) {
            let left = (screen.width / 2) - (w / 2);
            let top = (screen.height / 2) - (h / 2);
            return window.open(url, title,
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=${scroll}, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`
            );
        }

        function popupFull(url) {
            let params = 'width=' + screen.width;
            params += ', height=' + screen.height;
            params += ', top=0, left=0, scrollbars=yes, fullscreen=yes';
            let newwin = window.open(url, 'windowname4', params);
            if (window.focus) {
                newwin.focus()
            }
            return false;
        }
    </script>
@endsection
