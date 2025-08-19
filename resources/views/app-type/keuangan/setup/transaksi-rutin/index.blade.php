@extends('inc.layout')
@section('title', 'Transaksi Rutin')
@section('content')
    <style>
        /* Keep all your existing styles */
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

        /* Toast notification styling */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }

        .toast {
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateX(100%);
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        .toast-success {
            background-color: #28a745;
            color: white;
        }

        .toast-error {
            background-color: #dc3545;
            color: white;
        }
    </style>

    <!-- Toast Notification Container -->


    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel (Sekarang hanya untuk filter halaman, bukan AJAX) -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter <span class="fw-300"><i>Data</i></span></h2>
                    </div>
                    <div class="panel-container show py-4 px-3">
                        <div class="panel-content">
                            <form id="filterForm" method="GET" action="{{ route('transaksi-rutin.index') }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Transaksi</label>
                                        <input type="text" name="nama_transaksi" class="form-control"
                                            placeholder="Cari Nama Transaksi..." value="{{ request('nama_transaksi') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control select2" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak
                                                Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2"><i
                                            class="fal fa-search mr-1"></i> Cari</button>
                                    <a href="{{ route('transaksi-rutin.index') }}"
                                        class="btn btn-sm btn-secondary">Reset</a>
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
                        <h2>Daftar <span class="fw-300"><i>Transaksi Rutin</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="bulkActionForm" method="POST" action="{{ route('transaksi-rutin.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <table id="transaksi-rutin-table"
                                    class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr class="text-center">
                                            <th style="width: 10px;"><input type="checkbox" id="select_all"></th>
                                            <th>No</th>
                                            <th>Nama Transaksi</th>
                                            <th>Akun (COA)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaksiRutin as $item)
                                            <tr data-id="{{ $item->id }}" data-coa-id="{{ $item->chart_of_account_id }}"
                                                data-is-active="{{ $item->is_active ? 1 : 0 }}">
                                                <td class="text-center"><input type="checkbox" class="row-checkbox"
                                                        name="ids[]" value="{{ $item->id }}"></td>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="nama-transaksi">{{ $item->nama_transaksi }}</td>
                                                <td>{{ optional($item->chartOfAccount)->code }} -
                                                    {{ optional($item->chartOfAccount)->name }}</td>
                                                <td class="text-center status-transaksi">
                                                    @if ($item->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- Dibiarkan kosong, DataTables akan menangani ini --}}
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" id="btn-tambah"><i
                                            class="fal fa-plus mr-1"></i> Tambah</button>
                                    <button type="button" class="btn btn-info" id="btn-edit" disabled><i
                                            class="fal fa-pencil mr-1"></i> Edit</button>
                                    <button type="submit" class="btn btn-danger" id="btn-hapus" disabled><i
                                            class="fal fa-trash-alt mr-1"></i> Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Tambah / Edit --}}
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Form Transaksi Rutin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form id="transaksiRutinForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="transaksi_id">
                        <div class="form-group">
                            <label>Nama Transaksi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_transaksi" name="nama_transaksi"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Akun (COA) <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="chart_of_account_id" name="chart_of_account_id"
                                style="width: 100%;" required>
                                <option value="">Pilih Akun...</option>
                                @foreach ($chartOfAccounts as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="is_active" name="is_active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <script>
        function showToast(type, message) {
            const toastContainer = $('.toast-container');
            const toastId = 'toast-' + Date.now();

            const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

            toastContainer.append(toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            // Hapus toast setelah 5 detik
            setTimeout(() => {
                toastElement.remove();
            }, 5000);
        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true
            });
            $('.select2-modal').select2({
                dropdownParent: $('#formModal'),
                placeholder: "Pilih Akun..."
            });

            // ==========================================================
            // INISIALISASI DATATABLES DI SINI
            // ==========================================================
            const table = $('#transaksi-rutin-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthChange: false,
                order: [
                    [2, 'asc']
                ], // Urutkan berdasarkan Nama Transaksi
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    } // Matikan sorting untuk checkbox
                ],
                // Dom untuk menempatkan tombol search dan export
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ /* Tombol export bisa ditambahkan di sini jika perlu */ ],
                // Bahasa untuk DataTables
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak tersedia atau tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)"
                }
            });

            // Logika Checkbox (disesuaikan untuk client-side DataTables)
            function checkCheckboxes() {
                const checkedCount = $('.row-checkbox:checked').length;
                $('#btn-hapus').prop('disabled', checkedCount === 0);
                $('#btn-edit').prop('disabled', checkedCount !== 1);
            }

            $('#select_all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input.row-checkbox', rows).prop('checked', this.checked);
                checkCheckboxes();
            });
            $('#transaksi-rutin-table tbody').on('change', 'input.row-checkbox', checkCheckboxes);

            // Logika untuk form dan modal
            const form = $('#transaksiRutinForm');
            const modal = $('#formModal');

            $('#btn-tambah').on('click', function() {
                form[0].reset();
                $('#transaksi_id').val('');
                $('#formMethod').val('POST');
                form.attr('action', "{{ route('transaksi-rutin.store') }}");
                $('.select2-modal').val(null).trigger('change');
                $('#formModalLabel').text('Tambah Transaksi Rutin');
                modal.modal('show');
            });

            $('#btn-edit').on('click', function() {
                const id = $('.row-checkbox:checked').val();
                const tr = $(`tr:has(input[value="${id}"])`);

                const nama = tr.find('.nama-transaksi').text();
                const coaId = tr.data('coa-id');
                const isActive = tr.data('is-active');

                $('#transaksi_id').val(id);
                $('#nama_transaksi').val(nama);
                $('#chart_of_account_id').val(coaId).trigger('change');
                $('#is_active').val(isActive);

                $('#formMethod').val('PUT');
                form.attr('action', `{{ url('keuangan/setup/transaksi-rutin') }}/${id}`);
                $('#formModalLabel').text('Edit Transaksi Rutin');
                modal.modal('show');
            });

            // Konfirmasi sebelum hapus massal
            $('#bulkActionForm').on('submit', function(e) {
                const checkedCount = $('.row-checkbox:checked').length;
                if (checkedCount === 0) {
                    e.preventDefault();
                    alert('Tidak ada data yang dipilih untuk dihapus.');
                    return false;
                }
                if (!confirm(`Yakin ingin menghapus ${checkedCount} data terpilih?`)) {
                    e.preventDefault();
                }
            });
        });
    </script>


@endsection
