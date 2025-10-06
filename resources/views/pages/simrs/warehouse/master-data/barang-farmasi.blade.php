@extends('inc.layout')
@section('title', 'Master Barang Farmasi')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-pills'></i> Master <span class='fw-300'>Barang Farmasi</span>
                <small>Manajemen data master untuk barang farmasi.</small>
            </h1>
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                {{-- Filter Form --}}
                <form id="filter-form" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <input type="text" class="form-control" id="filter_kode" placeholder="Kode Barang">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="text" class="form-control" id="filter_nama" placeholder="Nama Barang">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="text" class="form-control" id="filter_kategori" placeholder="Kategori">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="text" class="form-control" id="filter_satuan" placeholder="Satuan">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select class="form-control" id="filter_status">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-info mb-2" id="btn-filter"><i class="fal fa-filter"></i>
                        Filter</button>
                    <button type="button" class="btn btn-secondary mb-2 ml-2" id="btn-reset"><i class="fal fa-undo"></i>
                        Reset</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Barang Farmasi</i></span></h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm"
                                onclick="openPopup('{{ route('warehouse.master-data.barang-farmasi.create') }}')">
                                <i class="fal fa-plus"></i> Tambah Barang
                            </button>
                            <button class="btn btn-info btn-sm ml-2" data-toggle="modal" data-target="#import-modal">
                                <i class="fal fa-upload"></i> Import Excel
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-barang-farmasi" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>HNA</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Import Modal -->
    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form id="import-form" action="{{ route('warehouse.master-data.barang-farmasi.import') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fal fa-upload"></i> Import Barang Farmasi dari Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (session('import_errors'))
                            <div class="alert alert-danger">
                                <strong>Beberapa data gagal diimpor:</strong>
                                <ul class="mb-0">
                                    @foreach (session('import_errors') as $failure)
                                        <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="file_import">Pilih File Excel <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_import" name="file_import"
                                accept=".xls,.xlsx" required>
                            <small class="form-text text-muted">Format file: .xls, .xlsx</small>
                        </div>
                        <div>
                            <a href="{{ route('warehouse.master-data.barang-farmasi.export') }}" class="btn btn-link p-0">
                                <i class="fal fa-download"></i> Download Template Excel
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info btn-sm"><i class="fal fa-upload"></i> Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        function openPopup(url) {
            var width = screen.width;
            var height = screen.height;
            var popupWindow = window.open(url, 'popupWindow', `width=${width},height=${height},scrollbars=yes`);

            // Reload datatable when popup is closed
            var timer = setInterval(function() {
                if (popupWindow.closed) {
                    clearInterval(timer);
                    $('#dt-barang-farmasi').DataTable().ajax.reload();
                }
            }, 1000);
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#dt-barang-farmasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('warehouse.master-data.barang-farmasi.data') }}",
                    data: function(d) {
                        d.kode = $('#filter_kode').val();
                        d.nama = $('#filter_nama').val();
                        d.kategori = $('#filter_kategori').val();
                        d.satuan = $('#filter_satuan').val();
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [{
                        data: null,
                        name: 'No',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kategori_name',
                        name: 'kategori.nama'
                    },
                    {
                        data: 'satuan_name',
                        name: 'satuan.nama'
                    },
                    {
                        data: 'hna',
                        name: 'hna',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        text: '<i class="fal fa-plus"></i> Tambah',
                        className: 'btn btn-primary',
                        action: function() {
                            openPopup("{{ route('warehouse.master-data.barang-farmasi.create') }}");
                        }
                    }
                    // Anda dapat menambahkan tombol lain di sini jika diperlukan
                ]
            });

            // Filter button click
            $('#btn-filter').on('click', function() {
                table.ajax.reload();
            });

            // Reset button click
            $('#btn-reset').on('click', function() {
                $('#filter-form')[0].reset();
                table.ajax.reload();
            });

            // Enter key triggers filter
            $('#filter-form input, #filter-form select').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.ajax.reload();
                }
            });

            // Delete button click
            $('#dt-barang-farmasi').on('click', '.delete-btn', function() {
                var url = $(this).data('url');
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert('Gagal menghapus data.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
