@extends('inc.layout')
{{-- Ganti Judul --}}
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
                            Form Pencarian</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" id="filter-wrapper">
                            {{-- Ganti action ke route index saat ini --}}
                            <form action="{{ route('master-data.setup.form-builder') }}" method="get">
                                {{-- Tidak perlu @csrf untuk GET request --}}
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_form_search" class="form-label">Nama Form</label>
                                            {{-- Ganti id dan name agar sesuai --}}
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
                                            <th>Aksi</th>
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
                                                    {{-- Ganti button menjadi anchor (link) untuk edit --}}
                                                    <a href="{{ route('master-data.setup.form-builder.edit', $row->id) }}"
                                                        class="btn btn-sm btn-warning px-2 py-1" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
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

    {{-- =================================================================== --}}
    {{-- JAVASCRIPT YANG SUDAH DIPERBAIKI --}}
    {{-- =================================================================== --}}
    <script>
        $(document).ready(function() {
            // Mengambil CSRF token dari meta tag untuk keamanan AJAX
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Hapus semua kode JS yang tidak relevan (AJAX untuk 'tipe operasi')

            // Event handler untuk tombol Hapus
            $('.btn-delete').click(function() {
                var formId = $(this).data('id');
                var button = $(this); // Simpan referensi tombol

                // Menggunakan SweetAlert untuk konfirmasi yang lebih baik (opsional, ganti dengan confirm() jika tidak ada)
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengklik "Ya", lakukan AJAX request
                        $.ajax({
                            url: '/api/simrs/master-data/setup/form-builder/' + formId +
                                '/delete', // URL API yang benar
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken // Kirim CSRF token di header
                            },
                            beforeSend: function() {
                                // Menonaktifkan tombol untuk mencegah klik ganda
                                button.prop('disabled', true);
                            },
                            success: function(response) {
                                Swal.fire('Dihapus!', response.message, 'success');

                                // Hapus baris dari tabel tanpa perlu reload halaman
                                button.closest('tr').remove();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Gagal!', 'Terjadi kesalahan: ' + xhr
                                    .responseJSON.message, 'error');
                            },
                            complete: function() {
                                // Mengaktifkan kembali tombol
                                button.prop('disabled', false);
                            }
                        });
                    }
                })
            });

            // Inisialisasi DataTable
            $('#dt-basic-example').DataTable({
                responsive: false,
                scrollX: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
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
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });
        });
    </script>
@endsection
