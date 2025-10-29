@extends('inc.layout')
@section('title', 'Monitoring Stok Proyek')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <style>
        /* Style untuk child row */
        tr.details-shown td.details-control {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item active">Monitoring Stok</li>
        </ol>
        <div class="alert alert-info">
            Halaman ini menampilkan stok terkini. Stok bertambah/berkurang secara otomatis dari modul terkait. Gunakan
            tombol "Tambah Stok Masuk" untuk pencatatan penerimaan manual.
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Stok Item Proyek</h2>
                        <div class="panel-toolbar">
                            {{-- TOMBOL BARU UNTUK MEMBUKA MODAL --}}
                            <button class="btn btn-success btn-sm" id="stockInBtn">
                                <i class="fal fa-plus"></i> Tambah Stok Masuk
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="stock-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th class="text-right">Total Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ================== MODAL BARU UNTUK STOK MASUK ================== -->
    <div class="modal fade" id="stockInModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Catat Penerimaan Barang (Stok Masuk)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="stockInForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="project_build_item_id">Pilih Item</label>
                            <select class="form-control" id="project_build_item_id" name="project_build_item_id" required>
                                <option value="">-- Cari Item --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="gudang_id">Masuk Ke Gudang</label>
                                    <select class="form-control" id="gudang_id" name="gudang_id" required>
                                        <option value="">-- Pilih Gudang --</option>
                                        @foreach ($gudangs as $gudang)
                                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="quantity">Kuantitas</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" required
                                        step="0.01" min="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi / Catatan Penerimaan</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required
                                placeholder="Contoh: Penerimaan dari Supplier X, No. Surat Jalan: 123"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success" id="saveBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // ... (Kode DataTables dan event listener child row tidak berubah) ...
            var table = $('#stock-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock-management.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'item_code',
                        name: 'project_build_items.item_code'
                    },
                    {
                        data: 'item_name',
                        name: 'project_build_items.item_name'
                    },
                    {
                        data: 'kategori.nama',
                        name: 'kategori.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'satuan.nama',
                        name: 'satuan.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'total_stock',
                        name: 'total_stock',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data || 0).toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                responsive: true
            });

            // Event listener untuk tombol 'Stok/Gudang'
            $('#stock-table tbody').on('click', 'button.view-details-btn', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var itemId = $(this).data('id');

                if (row.child.isShown()) {
                    // Jika child row sudah terbuka, tutup
                    row.child.hide();
                    tr.removeClass('details-shown');
                } else {
                    // Jika child row tertutup, buka dan load data
                    row.child(
                        '<td colspan="7" class="text-center"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</td>'
                    ).show();
                    tr.addClass('details-shown');

                    $.ajax({
                        url: `{{ url('stock-management') }}/${itemId}/details`,
                        type: 'GET',
                        success: function(html) {
                            // Bungkus HTML dengan <td> yang memiliki colspan
                            row.child('<td colspan="7">' + html + '</td>').show();
                        },
                        error: function() {
                            row.child(
                                '<td colspan="7" class="text-center text-danger">Gagal memuat detail stok.</td>'
                            ).show();
                        }
                    });
                }
            });

            // ================== JAVASCRIPT BARU ==================

            // Init Select2 untuk modal
            $('#project_build_item_id, #gudang_id').select2({
                dropdownParent: $('#stockInModal'),
                width: '100%'
            });

            // Tombol "Tambah Stok Masuk"
            $('#stockInBtn').on('click', function() {
                $('#stockInForm').trigger("reset");
                $('#project_build_item_id, #gudang_id').val(null).trigger('change');
                $('#stockInModal').modal('show');
            });

            // Submit Form Stok Masuk
            $('#stockInForm').on('submit', function(e) {
                e.preventDefault();
                $('#saveBtn').html('Menyimpan...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('stock-management.manualStockIn') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#stockInModal').modal('hide');
                        table.ajax.reload(); // Refresh DataTable
                        showSuccessAlert(response.success);
                    },
                    error: function(xhr) {
                        let errorMsg = "Terjadi kesalahan.";
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                            } else if (xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                        }
                        showErrorAlertNoRefresh(errorMsg);
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
