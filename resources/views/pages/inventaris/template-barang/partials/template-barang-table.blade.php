@php
    use App\Models\Inventaris\Barang;
@endphp

<table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
    <thead>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama Barang</th>
            <th style="white-space: nowrap">Jumlah Barang</th>
            <th style="white-space: nowrap">Kategori</th>
            <th style="white-space: nowrap">Kode Barang</th>
            <th style="white-space: nowrap">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($templateBarang as $row)
            <tr>
                <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                <td style="white-space: nowrap">
                    <a href="{{ route('inventaris.template.show', $row->id) }}">{{ strtoupper($row->name) }}</a>
                </td>
                <td style="white-space: nowrap">
                    {{ count(Barang::where('template_barang_id', $row->id)->get()) }}
                </td>
                <td style="white-space: nowrap">{{ strtoupper($row->category->name) }}</td>
                <td style="white-space: nowrap">{{ strtoupper($row->barang_code) }}</td>
                <td style="white-space: nowrap">
                    <button class="btn btn-sm btn-success px-2 py-1 btn-edit" data-id="{{ $row->id }}">
                        <i class="fas fa-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger px-2 py-1 btn-delete" data-id="{{ $row->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama Barang</th>
            <th style="white-space: nowrap">Jumlah Barang</th>
            <th style="white-space: nowrap">Kategori</th>
            <th style="white-space: nowrap">Kode Barang</th>
            <th style="white-space: nowrap">Aksi</th>
        </tr>
    </tfoot>
</table>

@include('pages.inventaris.template-barang.partials.edit')

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let templateId = null;

            $('.btn-edit').click(function() {
                $('#modal-edit').modal('show');
                templateId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/inventaris/template-barang/' + templateId,
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data yang diterima
                        $('#modal-edit #category_id').val(response.category_id);
                        $('#modal-edit #name').val(response.name);
                        $('#modal-edit #barang_code').val(response.barang_code);
                        $('#modal-edit #merk').val(response.merk);
                        $('#modal-edit #foto').val(response.foto);
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('.btn-delete').click(function() {
                var templateId = $(this).attr('data-id');

                // Using SweetAlert2 for confirmation
                Swal.fire({
                    title: 'Anda Yakin ingin menghapus ini?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user confirms deletion, proceed with the AJAX request
                        $.ajax({
                            url: '/api/inventaris/template-barang/' + templateId +
                                '/delete',
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Dihapus!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                setTimeout(() => {
                                    console.log('Reloading the page now.');
                                    window.location.reload();
                                }, 800);
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan: ' + error,
                                    'error'
                                );
                            }
                        });
                    } else {
                        console.log('Penghapusan dibatalkan oleh pengguna.');
                    }
                });
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/template-barang/' + templateId + '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            $('#modal-edit').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/template-barang/',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        // $('#modal-tambah-grup-tindakan').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            // $('#modal-tambah-grup-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            // $('#modal-tambah-grup-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $(function() {
                $('#category_id').select2({
                    placeholder: 'Pilih Kategori Barang',
                    dropdownParent: $('#modal-edit')
                });
            });

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

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });
        });
    </script>
@endsection
