@extends('inc.layout')
@section('title', 'Revenue & Cost Center')
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

        /* Hilangkan scroll pada wrapper table */
        .table-responsive {
            overflow: visible;
        }

        /* Pastikan header tetap saat discroll */
        #rnc-table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Styling untuk tombol aksi */
        .action-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        /* Perbaikan tampilan checkbox */
        .row-checkbox {
            cursor: pointer;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Revenue & Cost Center</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive"> <!-- Tambahkan div wrapper untuk scroll -->
                                <table id="rnc-table" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th style="width: 15px;" class="text-center"><input type="checkbox"
                                                    id="select_all">
                                            </th>
                                            <th>No</th>
                                            <th>Kode RNC Center</th>
                                            <th>Nama RNC Center</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rncCenters as $center)
                                            <tr data-id="{{ $center->id }}">
                                                <td class="text-center"><input type="checkbox" class="row-checkbox"
                                                        value="{{ $center->id }}"></td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $center->kode_rnc }}</td>
                                                <td>{{ $center->nama_rnc }}</td>
                                                <td class="text-center">
                                                    @if ($center->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="action-buttons">
                                <button class="btn btn-primary" id="btn-tambah">
                                    <i class="fal fa-plus mr-1"></i> Tambah
                                </button>
                                <button class="btn btn-info" id="btn-edit" disabled>
                                    <i class="fal fa-pencil mr-1"></i> Edit
                                </button>
                                <button class="btn btn-danger" id="btn-hapus" disabled>
                                    <i class="fal fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </div>
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
                    <h5 class="modal-title" id="formModalLabel">Form RNC Center</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="rncForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="rnc_id">
                        <div class="form-group">
                            <label for="kode_rnc">Kode RNC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_rnc" name="kode_rnc" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_rnc">Nama RNC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_rnc" name="nama_rnc" required>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Status <span class="text-danger">*</span></label>
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
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <script src="/js/formplugins/select2/select2.bundle.js"></script>


    <script>
        $(document).ready(function() {
            // -----------------------------------------------------------------
            // 1. SETUP & INISIALISASI
            // -----------------------------------------------------------------
            $('.select2').select2({
                dropdownCssClass: "move-up",
                placeholder: "Pilih opsi",
                allowClear: true
            });
            // Setup CSRF Token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Inisialisasi DataTable dengan scroll
            var table = $('#rnc-table').DataTable({
                responsive: true,
                pageLength: -1,
                lengthMenu: [
                    [-1],
                    ["All"]
                ],
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                scrollY: '400px',
                scrollCollapse: true,
                paging: false
            });

            // Definisikan URL dasar yang akan digunakan untuk semua operasi AJAX
            var baseUrl = "{{ url('/keuangan/setup/revenue-costcenter') }}";

            // -----------------------------------------------------------------
            // 2. LOGIKA CHECKBOX DAN TOMBOL
            // -----------------------------------------------------------------

            function checkCheckboxes() {
                var checkedCount = $('.row-checkbox:checked').length;
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

            $('#rnc-table tbody').on('change', '.row-checkbox', function() {
                if (!this.checked) {
                    $('#select_all').prop('checked', false);
                }
                checkCheckboxes();
            });

            // -----------------------------------------------------------------
            // 3. LOGIKA CRUD (TAMBAH, EDIT, HAPUS) VIA MODAL & AJAX
            // -----------------------------------------------------------------

            var form = $('#rncForm');
            var modal = $('#formModal');

            // Tombol TAMBAH: Buka modal dalam mode 'tambah'
            $('#btn-tambah').on('click', function() {
                form[0].reset(); // Bersihkan form
                $('#rnc_id').val(''); // Pastikan ID kosong
                modal.find('.modal-title').text('Tambah RNC Center');
                modal.modal('show');
            });

            // Tombol EDIT: Isi form dengan data dari baris yang dipilih, lalu buka modal
            $('#btn-edit').on('click', function() {
                var id = $('.row-checkbox:checked').val();
                var tr = $(`tr[data-id="${id}"]`);

                // Ambil data dari sel tabel
                var kode = tr.find('td:eq(2)').text().trim();
                var nama = tr.find('td:eq(3)').text().trim();
                var statusText = tr.find('td:eq(4) .badge').text().trim();

                // Isi form di modal
                $('#rnc_id').val(id);
                $('#kode_rnc').val(kode);
                $('#nama_rnc').val(nama);
                $('#is_active').val(statusText === 'Aktif' ? '1' : '0');

                modal.find('.modal-title').text('Edit RNC Center');
                modal.modal('show');
            });

            // SUBMIT FORM: Menangani 'tambah' dan 'edit'
            form.on('submit', function(e) {
                e.preventDefault();
                var id = $('#rnc_id').val();

                // Tentukan URL dan Method berdasarkan ada/tidaknya ID
                var url = id ? `${baseUrl}/${id}` : baseUrl;
                var method = id ? 'PUT' : 'POST';
                var btn = $('#btn-simpan');

                btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        modal.modal('hide');
                        toastr.success(response.message, 'Sukses');
                        // Reload halaman setelah 1.5 detik untuk melihat perubahan
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        var errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Gabungkan semua pesan error validasi
                            errorMsg = Object.values(xhr.responseJSON.errors).map(e => e[0])
                                .join('<br>');
                        }
                        toastr.error(errorMsg, 'Error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Simpan');
                    }
                });
            });

            // Tombol HAPUS: Menangani penghapusan data (bisa massal)
            $('#btn-hapus').on('click', function() {
                var ids = [];
                $('.row-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Anda yakin ingin menghapus ${ids.length} data yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: baseUrl,
                            method: 'DELETE',
                            data: {
                                ids: ids
                            },
                            beforeSend: function() {
                                $('#btn-hapus').prop('disabled', true).html(
                                    '<i class="fal fa-spinner fa-spin mr-1"></i> Menghapus...'
                                );
                            },
                            success: function(response) {
                                toastr.success(response.message, 'Sukses');
                                setTimeout(() => location.reload(), 1500);
                            },
                            error: function(xhr) {
                                var errorMsg = xhr.responseJSON && xhr.responseJSON
                                    .message ?
                                    xhr.responseJSON.message :
                                    'Gagal menghapus data.';
                                toastr.error(errorMsg, 'Error');
                            },
                            complete: function() {
                                $('#btn-hapus').prop('disabled', false).html(
                                    '<i class="fal fa-trash-alt mr-1"></i> Hapus');
                            }
                        });
                    }
                });
            });

            // Double click pada row untuk edit
            $('#rnc-table tbody').on('dblclick', 'tr', function() {
                var checkbox = $(this).find('.row-checkbox');
                checkbox.prop('checked', !checkbox.prop('checked'));
                checkCheckboxes();

                if (checkbox.is(':checked')) {
                    $('#btn-edit').click();
                }
            });
        });
    </script>
@endsection
