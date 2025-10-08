@extends('inc.layout')
@section('title', 'Master Supplier')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-truck'></i> Master <span class='fw-300'>Supplier</span>
                <small>
                    Manajemen data master untuk supplier.
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Supplier</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-supplier">
                                <i class="fal fa-plus"></i> Tambah Supplier
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" style="overflow-x: auto;">
                            <!-- datatable start -->
                            <table id="dt-supplier" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Supplier</th>
                                        <th>Kategori</th>
                                        <th>Kontak</th>
                                        <th>TOP</th>
                                        <th>PPN (%)</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh DataTables -->
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @php
        $topOptions = ['COD', '7HARI', '14HARI', '21HARI', '24HARI', '30HARI', '37HARI', '40HARI', '45HARI'];
        $tipeTopOptions = ['SETELAH_TUKAR_FAKTUR', 'SETELAH_TERIMA_BARANG'];
    @endphp

    <!-- Unified Supplier Modal (Add/Edit) -->
    <div class="modal fade" id="supplier-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form id="supplier-form" method="POST">
                    @csrf
                    <input type="hidden" id="supplier-id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supplier-modal-title">Tambah Supplier Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body" style="overflow-y: auto; max-height: 70vh;">
                        @include('pages.simrs.warehouse.master-data.partials.supplier-form-fields', [
                            'prefix' => 'supplier',
                            'topOptions' => $topOptions,
                            'tipeTopOptions' => $tipeTopOptions,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="supplier-modal-submit">
                            <i class="fal fa-save"></i> Simpan
                        </button>
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
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Helper: clear validation errors
            function clearValidationErrors(formId) {
                $(`#${formId} .form-control`).removeClass('is-invalid');
                $(`#${formId} .invalid-feedback`).text('');
            }

            // Helper: show validation errors
            function showValidationErrors(prefix, errors) {
                for (const key in errors) {
                    if (Object.hasOwnProperty.call(errors, key)) {
                        const message = errors[key][0];
                        let fieldKey = key;
                        if (key === 'aktif') fieldKey = 'status';
                        const field = $(`#${prefix}-${fieldKey}`);
                        field.addClass('is-invalid');
                        field.next('.invalid-feedback').text(message);
                    }
                }
            }

            // Select2 init
            $('#supplier-top, #supplier-tipe_top').select2({
                placeholder: function() {
                    return $(this).attr('id').includes('tipe_top') ? "Pilih Tipe TOP..." :
                        "Pilih TOP...";
                },
                dropdownParent: $('#supplier-modal')
            });

            // DataTables init
            var table = $('#dt-supplier').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.master-data.supplier.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'top',
                        name: 'top'
                    },
                    {
                        data: 'ppn',
                        name: 'ppn'
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
                        extend: 'copy',
                        text: '<i class="fal fa-copy"></i> Salin',
                        className: 'btn btn-secondary btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fal fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fal fa-file-csv"></i> CSV',
                        className: 'btn btn-info btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fal fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print"></i> Cetak',
                        className: 'btn btn-primary btn-sm'
                    }
                ]
            });

            // Open modal for Add
            $('#btn-tambah-supplier').on('click', function() {
                // Reset seluruh field form supplier (termasuk select2, radio, dan nilai default) di partial supplier-form-fields.blade.php
                $('#supplier-form')[0].reset();
                clearValidationErrors('supplier-form');

                // Reset Select2 ke placeholder
                $('#supplier-top, #supplier-tipe_top').val(null).trigger('change');

                // Reset radio button kategori & status ke default (harus sesuai default di partial)
                $('#supplier-kategori-farmasi').prop('checked', true);
                $('#supplier-aktif-true').prop('checked', true);

                // Set nilai default PPN (harus sesuai default di partial)
                $('#supplier-ppn').val('11');

                // Reset semua field input di partial supplier-form-fields.blade.php
                $('#supplier-nama').val('');
                $('#supplier-alamat').val('');
                $('#supplier-phone').val('');
                $('#supplier-fax').val('');
                $('#supplier-email').val('');
                $('#supplier-contact_person').val('');
                $('#supplier-contact_person_phone').val('');
                $('#supplier-contact_person_email').val('');
                $('#supplier-no_rek').val('');
                $('#supplier-bank').val('');

                // Hapus method spoofing jika ada dari edit sebelumnya
                $('#supplier-form').find('input[name="_method"]').remove();
                $('#supplier-id').val('');

                // Set judul dan tombol untuk mode 'Tambah'
                $('#supplier-modal-title').text('Tambah Supplier Baru');
                $('#supplier-modal-submit').removeClass('btn-warning').addClass('btn-primary').html(
                    '<i class="fal fa-save"></i> Simpan');

                // Set action form untuk 'store'
                $('#supplier-form').attr('action', "{{ route('warehouse.master-data.supplier.store') }}");

                $('#supplier-modal').modal('show');
            });

            // Prevent double submit
            let supplierFormSubmitting = false;

            // Submit (add/edit) - refactor: use form.serialize()
            $('#supplier-form').on('submit', function(e) {
                e.preventDefault();

                // Cegah submit ganda
                if (supplierFormSubmitting) {
                    return false;
                }
                supplierFormSubmitting = true;

                var form = $(this);
                var url = form.attr('action');
                var isEdit = $('#supplier-id').val() !== '';

                // Disable submit button, show spinner
                $('#supplier-modal-submit').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                clearValidationErrors('supplier-form');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#supplier-modal').modal('hide');
                            showSuccessAlert(response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            showValidationErrors('supplier', errors);
                        } else {
                            showErrorAlertNoRefresh(isEdit ? 'Gagal memperbarui data.' :
                                'Gagal menyimpan data.');
                        }
                    },
                    complete: function() {
                        var buttonText = isEdit ? '<i class="fal fa-save"></i> Update' :
                            '<i class="fal fa-save"></i> Simpan';
                        $('#supplier-modal-submit').prop('disabled', false).html(buttonText);
                        supplierFormSubmitting = false;
                    }
                });
            });

            // Edit button click - refactor
            $('#dt-supplier').on('click', '.edit-btn', function() {
                var url = $(this).data('url');

                // Reset form sebelum diisi
                $('#supplier-form')[0].reset();
                clearValidationErrors('supplier-form');
                $('#supplier-top, #supplier-tipe_top').val(null).trigger('change');

                $.get(url, function(response) {
                    if (response.success) {
                        var data = response.data;

                        // Set judul dan tombol untuk mode 'Edit'
                        $('#supplier-modal-title').text('Edit Data Supplier');
                        $('#supplier-modal-submit').removeClass('btn-primary').addClass(
                            'btn-warning').html('<i class="fal fa-save"></i> Update');

                        // Set ID dan method spoofing untuk PUT
                        $('#supplier-id').val(data.id);
                        if ($('#supplier-form').find('input[name="_method"]').length === 0) {
                            $('#supplier-form').append(
                                '<input type="hidden" name="_method" value="PUT">');
                        }

                        // Set action form untuk 'update'
                        var updateUrl =
                            "{{ route('warehouse.master-data.supplier.update', ':id') }}".replace(
                                ':id', data.id);
                        $('#supplier-form').attr('action', updateUrl);

                        // Isi semua field dari data
                        $('#supplier-nama').val(data.nama);
                        $(`input[name="kategori"][value="${data.kategori}"]`).prop('checked', true);
                        $('#supplier-alamat').val(data.alamat);

                        $('#supplier-phone').val(data.phone);
                        $('#supplier-fax').val(data.fax);
                        $('#supplier-email').val(data.email);

                        $('#supplier-contact_person').val(data.contact_person);
                        $('#supplier-contact_person_phone').val(data.contact_person_phone);
                        $('#supplier-contact_person_email').val(data.contact_person_email);

                        $('#supplier-no_rek').val(data.no_rek);
                        $('#supplier-bank').val(data.bank);

                        $('#supplier-top').val(data.top).trigger('change');
                        $('#supplier-tipe_top').val(data.tipe_top).trigger('change');

                        $('#supplier-ppn').val(data.ppn);

                        if (data.aktif == 1) {
                            $('#supplier-aktif-true').prop('checked', true);
                        } else {
                            $('#supplier-aktif-false').prop('checked', true);
                        }

                        $('#supplier-modal').modal('show');
                    }
                });
            });

            // Delete button click (tidak diubah)
            $('#dt-supplier').on('click', '.delete-btn', function() {
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

            // Clear modal on hidden - Disederhanakan
            $('#supplier-modal').on('hidden.bs.modal', function() {
                $('#supplier-form')[0].reset();
                clearValidationErrors('supplier-form');
                $('#supplier-top, #supplier-tipe_top').val(null).trigger('change');
                $('#supplier-id').val('');
                $('#supplier-form').find('input[name="_method"]').remove();
                supplierFormSubmitting = false; // Reset flag ketika modal ditutup
                $('#supplier-modal-submit').prop('disabled', false); // Pastikan tombol aktif lagi
            });
        });
    </script>
@endsection
