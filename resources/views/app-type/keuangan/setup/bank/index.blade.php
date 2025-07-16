@extends('inc.layout')
@section('title', 'Bank')
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
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-bank" title="Tambah">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Bank
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Table <span class="fw-300"><i>Bank</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bank</th>
                                            <th>Pemilik</th>
                                            <th>Nomor Rekening</th>
                                            <th>Saldo</th>
                                            <th>Status</th>
                                            <th style="width: 25px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banks as $bank)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $bank->nama }}</td>
                                                <td>{{ $bank->pemilik }}</td>
                                                <td>{{ $bank->nomor }}</td>
                                                <td>{{ number_format($bank->saldo, 0, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $bank->is_aktivasi ? 'success' : 'secondary' }}">
                                                        {{ $bank->is_aktivasi ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <!-- Tombol Edit -->
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-toggle="modal"
                                                            data-target="#edit-bank-{{ $bank->id }}">
                                                            <i class="fal fa-edit"></i> Edit
                                                        </button>

                                                        <!-- Tombol Hapus -->
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-toggle="modal"
                                                            data-target="#delete-bank-{{ $bank->id }}">
                                                            <i class="fal fa-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal Delete untuk setiap bank -->
                            @foreach ($banks as $bank)
                                <div class="modal fade" id="delete-bank-{{ $bank->id }}" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fal fa-exclamation-triangle mr-2"></i>
                                                    Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <i class="fal fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                    <h4>Apakah Anda yakin?</h4>
                                                    <p class="text-muted">
                                                        Anda akan menghapus bank <strong>"{{ $bank->nama }}"</strong><br>
                                                        Tindakan ini tidak dapat dibatalkan!
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fal fa-times mr-1"></i> Batal
                                                </button>
                                                <form action="{{ route('bank.destroy', $bank->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fal fa-trash mr-1"></i> Ya, Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('app-type.keuangan.setup.bank.partials.create-bank')

        @foreach ($banks as $bank)
            @include('app-type.keuangan.setup.bank.partials.edit', ['bank' => $bank])
        @endforeach
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize select2 for create modal
            $('.select2').select2({
                placeholder: 'Pilih Opsi',
                dropdownParent: $('#tambah-bank'),
            });

            // Initialize DataTable
            $('#dt-basic-example').dataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'float-right btn btn-primary',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Download as Excel',
                        className: 'float-right btn btn-success',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Column Visibility',
                        titleAttr: 'Col visibility',
                        className: 'float-right mb-3 btn btn-warning',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        },
                        postfixButtons: [{
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Download as Excel',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            }
                        ]
                    }
                ]
            });

            // Initialize select2 for edit modals when they are shown
            $(document).on('shown.bs.modal', '.edit-bank-modal', function() {
                $(this).find('.select2').select2({
                    placeholder: 'Pilih Opsi',
                    dropdownParent: $(this),
                });
            });

            // Handle form submission for edit modals
            $(document).on('submit', '.edit-bank-form', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var modalId = form.closest('.modal').attr('id');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                form.find('[name="' + key + '"]').addClass(
                                    'is-invalid');
                                form.find('[name="' + key + '"]').next(
                                    '.invalid-feedback').text(value[0]);
                            });
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
