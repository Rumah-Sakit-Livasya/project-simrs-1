@extends('inc.layout')

@section('title', 'Tipe Transaksi')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-tags'></i> Master Tipe Transaksi
                <small>
                    Pengelolaan data untuk tipe-tipe transaksi tagihan.
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Input</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Form untuk Create dan Update --}}
                            <form id="tipeTransaksiForm">
                                @csrf
                                <input type="hidden" name="id" id="tipeTransaksiId">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="form-label">Nama Tipe Transaksi</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Contoh: Tindakan Medis" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="urutan" class="form-label">Urutan Tampil</label>
                                        <input type="number" class="form-control" id="urutan" name="urutan"
                                            placeholder="0" required>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary" id="btn-reset">Reset</button>
                                    <button type="button" class="btn btn-primary" id="btn-save">
                                        <i class="fal fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Tipe Transaksi</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Tabel DataTables --}}
                            <table id="tipeTransaksiTable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th width="30%">Nama</th>
                                        <th width="30%">Urutan</th>
                                        <th width="20%">Dibuat Pada</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan dimuat oleh DataTables --}}
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    {{-- JavaScript untuk DataTables dan logika CRUD --}}
    <script>
        $(document).ready(function() {
            // Ambil CSRF token dari meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';

            // Inisialisasi DataTables
            const table = $('#tipeTransaksiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tipe-transaksi.data') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'urutan',
                        name: 'urutan'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return new Date(data).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                responsive: true
            });

            // Fungsi untuk mereset form
            function resetForm() {
                $('#tipeTransaksiForm')[0].reset();
                $('#tipeTransaksiId').val('');
                $('#name').removeClass('is-invalid');
                $('#urutan').removeClass('is-invalid');
            }

            // Event handler untuk tombol reset
            $('#btn-reset').on('click', function() {
                resetForm();
            });

            // Event handler untuk submit form (Create & Update)
            $('#tipeTransaksiForm').on('click', '#btn-save', function(e) {
                e.preventDefault();

                const id = $('#tipeTransaksiId').val();
                let url = "{{ route('tipe-transaksi.store') }}";
                let method = "POST";

                if (id) {
                    url = `/simrs/master-data/setup/tipe-transaksi/${id}`;
                    method = "PUT";
                }

                // Ambil data form dan tambahkan CSRF token
                let formData = $('#tipeTransaksiForm').serializeArray();
                formData.push({
                    name: '_token',
                    value: csrfToken
                });

                $.ajax({
                    url: url,
                    type: method,
                    data: $.param(formData),
                    success: function(response) {
                        showSuccessAlert(response.success);
                        resetForm();
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let errors = null;
                        let message = 'Terjadi kesalahan.';
                        if (xhr.responseJSON) {
                            errors = xhr.responseJSON.errors || null;
                            message = xhr.responseJSON.message || message;
                        }
                        if (errors) {
                            if (errors.name) {
                                $('#name').addClass('is-invalid');
                                // Tampilkan pesan error jika perlu
                            }
                            if (errors.urutan) {
                                $('#urutan').addClass('is-invalid');
                            }
                        }
                        showErrorAlertNoRefresh(message);
                    }
                });
            });

            // Event handler untuk tombol Edit
            $('#tipeTransaksiTable').on('click', '.btn-edit', function() {
                const id = $(this).data('id');

                $.get(`/simrs/master-data/setup/tipe-transaksi/${id}/edit`, function(data) {
                    $('#tipeTransaksiId').val(data.id);
                    $('#name').val(data.name);
                    $('#urutan').val(data.urutan);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow'); // Scroll ke atas
                });
            });

            // Event handler untuk tombol Hapus
            $('#tipeTransaksiTable').on('click', '.btn-delete', function() {
                const id = $(this).data('id');

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: `/simrs/master-data/setup/tipe-transaksi/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            showSuccessAlert(response.success);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh('Gagal menghapus data.');
                        }
                    });
                });
            });

        });
    </script>
@endsection
