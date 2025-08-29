@extends('inc.layout-no-side')
@section('title', 'Daftar Order Operasi')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        /* CSS untuk Details Control */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

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

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }

        .details-control {
            cursor: pointer;
        }

        .dt-hasChild {
            background-color: #f5f5f5 !important;
        }

        .badge-draft {
            background-color: #f39c12;
        }

        .badge-final {
            background-color: #00a65a;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .btn-group-sm>.btn,
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .btn-action {
            margin-right: 3px;
        }

        .btn-action:last-child {
            margin-right: 0;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tindakan Operasi untuk:
                            <span class="fw-300">
                                <i>{{ $order->registration->patient->name ?? 'Pasien Tidak Ditemukan' }} (RM:
                                    {{ $order->registration->patient->medical_record_number ?? 'N/A' }})</i>
                            </span>
                        </h2>
                        <div class="panel-toolbar">
                            <button id="btn-tambah-tindakan" class="btn btn-primary btn-sm">
                                <i class="fal fa-plus mr-1"></i> Tambah Tindakan
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                    <strong>Berhasil!</strong> {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                    <strong>Gagal!</strong> {{ session('error') }}
                                </div>
                            @endif

                            {{-- Tabel untuk menampilkan daftar tindakan --}}
                            <table id="dt-tindakan" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Tindakan</th>
                                        <th width="15%">Kategori Operasi</th>
                                        <th width="10%">Tipe Penggunaan</th>
                                        <th width="15%">Dokter Operator</th>
                                        <th width="10%">Tgl. Tindakan</th>
                                        <th width="10%">User Create</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prosedurData as $index => $prosedur)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $prosedur['tindakan_nama'] }}</td>
                                            <td>{{ $prosedur['kategori_operasi'] }}</td>
                                            <td>{{ $prosedur['tipe_penggunaan'] }}</td>
                                            <td>{{ $prosedur['dokter_operator'] }}</td>
                                            <td>{{ $prosedur['tgl_tindakan'] }}</td>
                                            <td>{{ $prosedur['user_create'] }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $prosedur['status'] == 'draft' ? 'draft' : 'final' }}">
                                                    {{ strtoupper($prosedur['status']) }}
                                                </span>
                                            </td>
                                            <td class="action-buttons text-center">
                                                @if (strtolower($prosedur['status']) == 'draft')
                                                    {{-- Tombol Edit hanya untuk status draft --}}
                                                    <button class="btn btn-sm btn-icon btn-warning btn-action btn-edit"
                                                        data-toggle="tooltip" title="Edit Tindakan"
                                                        data-id="{{ $prosedur['id'] }}"
                                                        data-order-id="{{ $order->id }}">
                                                        <i class="fal fa-edit"></i>
                                                    </button>
                                                @endif

                                                {{-- Tombol Hapus untuk semua status --}}
                                                <button class="btn btn-sm btn-icon btn-danger btn-action btn-delete"
                                                    data-id="{{ $prosedur['id'] }}" data-toggle="tooltip"
                                                    title="{{ strtolower($prosedur['status']) == 'final' ? 'Hapus Tindakan Final' : 'Hapus Tindakan' }}"
                                                    data-status="{{ strtolower($prosedur['status']) }}">
                                                    <i class="fal fa-trash-alt"></i>
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

        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteConfirmationMessage">Apakah Anda yakin ingin menghapus tindakan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/notifications/sweetalert2/sweetalert2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#dt-tindakan').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    emptyTable: "Belum ada tindakan operasi yang ditambahkan untuk order ini.",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian Anda.",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    search: "Cari:",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    },
                }
            });

            // Event listener untuk tombol "Tambah Tindakan"
            $('#btn-tambah-tindakan').on('click', function() {
                var orderId = "{{ $order->id }}";
                var url = "{{ route('ok.prosedur.create', ['order' => ':id']) }}";
                url = url.replace(':id', orderId);

                var width = 1200;
                var height = 800;
                var left = (screen.width - width) / 2;
                var top = (screen.height - height) / 2;

                window.open(url, 'InputTindakanOperasi',
                    `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
                );
            });

            // Event listener untuk tombol Edit
            $(document).on('click', '.btn-edit', function() {
                var orderId = $(this).data('order-id');
                var prosedurId = $(this).data('id');

                var url =
                    "{{ route('ok.prosedure.edit', ['orderId' => ':orderId', 'prosedurId' => ':prosedurId']) }}";
                url = url.replace(':orderId', orderId).replace(':prosedurId', prosedurId);

                var width = 1200;
                var height = 800;
                var left = (screen.width - width) / 2;
                var top = (screen.height - height) / 2;

                window.open(url, 'EditTindakanOperasi',
                    `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
                );
            });


            // Variabel untuk menyimpan ID prosedur yang akan dihapus
            var prosedurToDelete = null;
            var isFinalStatus = false;

            // Event listener untuk tombol Delete
            $(document).on('click', '.btn-delete', function() {
                prosedurToDelete = $(this).data('id');
                isFinalStatus = $(this).data('status') === 'final';

                // Set pesan konfirmasi berdasarkan status
                var message = isFinalStatus ?
                    'Tindakan dengan status FINAL akan dihapus. Proses ini memerlukan persetujuan khusus. Lanjutkan?' :
                    'Apakah Anda yakin ingin menghapus tindakan ini?';

                $('#deleteConfirmationMessage').text(message);
                $('#deleteConfirmationModal').modal('show');
            });

            // Event listener untuk tombol konfirmasi hapus
            $('#confirmDelete').on('click', function() {
                if (!prosedurToDelete) return;

                $('#deleteConfirmationModal').modal('hide');

                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    html: 'Sedang memproses permintaan Anda',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Buat URL delete
                var deleteUrl = "{{ route('ok.prosedur.delete', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', prosedurToDelete);

                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message ||
                                'Gagal menghapus tindakan operasi.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        let errorMessage =
                            'Terjadi kesalahan saat menghapus data.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire(
                            'Error!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            });

            // Event listener untuk tombol Detail


            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <style>
        .swal-wide {
            width: 600px !important;
        }

        .swal2-html-container ul {
            text-align: left !important;
            padding-left: 20px;
        }

        .swal2-html-container .text-left {
            text-align: left !important;
        }

        .badge-draft {
            background-color: #f39c12;
            color: white;
        }

        .badge-final {
            background-color: #00a65a;
            color: white;
        }
    </style>
@endsection
