@extends('inc.layout')
@section('title', 'Manajemen Form Template')

@section('extended-css')
    <style>
        hr {
            border: 1px dashed #fd3995 !important;
        }

        div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child {
            padding: 0px;
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollFootInner {
            width: 100% !important;
        }

        #filter-wrapper .form-group {
            display: flex;
            align-items: center;
        }

        #filter-wrapper .form-label {
            margin-bottom: 0;
            width: 100px;
        }

        #filter-wrapper .form-control {
            flex: 1;
        }

        @media (max-width: 767.98px) {
            .custom-margin {
                margin-top: 15px;
            }

            #filter-wrapper .form-group {
                flex-direction: column;
                align-items: flex-start !important;
            }

            #filter-wrapper .form-label {
                width: auto;
                margin-bottom: 0.5rem;
            }

            #filter-wrapper .form-control {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Panel Pencarian --}}
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Pencarian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" id="filter-wrapper">
                            <form action="{{ route('master-data.setup.form-builder') }}" method="get">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_form_search" class="form-label">Nama Form</label>
                                            <input type="text" width="100%" name="nama_form" id="nama_form_search"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0"
                                                value="{{ request('nama_form') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-sm float-right mt-2 btn-primary">
                                            <i class="fas fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Tabel Data --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Form Template
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Nama Form</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($form as $row)
                                            <tr>
                                                <td style="vertical-align: middle">
                                                    {{ $row->nama_form }}
                                                </td>
                                                <td style="vertical-align: middle">
                                                    {{ $row->kategori?->nama_kategori ?? 'N/A' }}
                                                </td>
                                                <td style="vertical-align: middle">
                                                    @if ($row->is_active == 1)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('master-data.setup.form-builder.edit', $row->id) }}"
                                                        class="btn btn-sm btn-warning px-2 py-1" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    {{-- Tombol Baru untuk Preview Cetak --}}
                                                    <button class="btn btn-sm btn-primary px-2 py-1 btn-print-preview"
                                                        data-id="{{ $row->id }}" title="Preview Cetak">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger px-2 py-1 btn-delete"
                                                        data-id="{{ $row->id }}" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-center">
                                                <a href="{{ route('master-data.setup.form-builder.tambah') }}"
                                                    class="btn btn-outline-primary waves-effect waves-themed">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Form Template
                                                </a>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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

    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Inisialisasi DataTable
            const table = $('#dt-basic-example').DataTable({
                responsive: false,
                scrollX: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    // Tombol 'print' bawaan dihapus
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    }
                ]
            });

            // Event handler untuk tombol Hapus (menggunakan delegasi)
            $('#dt-basic-example tbody').on('click', '.btn-delete', function() {
                var formId = $(this).data('id');
                var button = $(this);
                var row = table.row(button.closest('tr'));

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/simrs/master-data/setup/form-builder/' + formId +
                                '/delete',
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            beforeSend: function() {
                                button.prop('disabled', true).html(
                                    '<i class="fas fa-spinner fa-spin"></i>');
                            },
                            success: function(response) {
                                Swal.fire('Dihapus!', response.message, 'success');
                                // Hapus baris dari DataTable
                                row.remove().draw(false);
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON.message ||
                                    'Terjadi kesalahan.', 'error');
                                button.prop('disabled', false).html(
                                    '<i class="fas fa-trash"></i>');
                            }
                        });
                    }
                });
            });

            // Event handler untuk tombol Preview Cetak (menggunakan delegasi)
            $('#dt-basic-example tbody').on('click', '.btn-print-preview', function() {
                var formId = $(this).data('id');
                var url = `/simrs/master-data/setup/form-builder/${formId}/print-preview`;
                var button = $(this);

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.content) {
                            var printWindow = window.open('', '_blank');
                            printWindow.document.write(
                                '<html><head><title>Print Preview</title>');
                            // Tautkan CSS yang relevan. Ganti path jika perlu.
                            printWindow.document.write(
                                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">'
                            );
                            printWindow.document.write(
                                '<style>body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }</style>'
                            );
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(response.content);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();

                            $(printWindow).on('load', function() {
                                printWindow.print();
                            });

                        } else {
                            Swal.fire('Gagal!', response.message || 'Konten template kosong.',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message ||
                            'Gagal mengambil data template.', 'error');
                    },
                    complete: function() {
                        button.prop('disabled', false).html('<i class="fas fa-print"></i>');
                    }
                });
            });
        });
    </script>
@endsection
