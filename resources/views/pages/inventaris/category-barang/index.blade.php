@php
    use App\Models\Inventaris\Barang;
    use App\Models\Inventaris\TemplateBarang;
@endphp

@extends('inc.layout')
@section('title', 'Kategori Barang')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Kategori Barang
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">

                <div id="form-container" style="display: none;" class="panel form-container">
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Kategori Barang
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="javascript:void(0)" id="store-form" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nama Kategori</label>
                                    <input type="text" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" placeholder="Nama Kategori">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="category_code">Kode Kategori</label>
                                    <input type="text" value="{{ old('category_code') }}"
                                        class="form-control @error('category_code') is-invalid @enderror" id="category_code"
                                        name="category_code" placeholder="Kode Kategori"
                                        onkeyup="this.value = this.value.toUpperCase()">
                                    @error('category_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="fal fa-plus-circle mr-1"></span>
                                        Tambah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Table <span class="fw-300"><i>Kategori Barang</i></span>
                        </h2>
                        @include('pages.partials.panel-toolbar')
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Kategori</th>
                                        <th style="white-space: nowrap">Kode Kategori</th>
                                        <th style="white-space: nowrap">Jumlah Barang</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoryBarang as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap"><a
                                                    href="{{ route('inventaris.category.show', $row->id) }}">{{ $row->name }}</a>
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->category_code }}</td>
                                            <td style="white-space: nowrap">
                                                {{ count(Barang::where('category_barang_id', $row->id)->get()) }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <button class="btn btn-sm btn-success px-2 py-1 btn-edit"
                                                    data-id="{{ $row->id }}">
                                                    <i class="fas fa-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger px-2 py-1 btn-delete"
                                                    data-id="{{ $row->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Kategori</th>
                                        <th style="white-space: nowrap">Kode Kategori</th>
                                        <th style="white-space: nowrap">Jumlah Barang</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('pages.inventaris.category-barang.partials.edit')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>

    <script>
        $(document).ready(function() {
            let categoryId = null;

            $('.btn-edit').click(function() {
                $('#modal-edit').modal('show');
                categoryId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/inventaris/category-barang/' + categoryId,
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data yang diterima
                        $('#modal-edit #name').val(response.name);
                        $('#modal-edit #category_code').val(response.category_code);
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('.btn-delete').click(function() {
                var categoryId = $(this).attr('data-id');

                // Use SweetAlert2 for confirmation
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
                            url: '/api/inventaris/category-barang/' + categoryId +
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
                                }, 1500);
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
                    url: '/api/inventaris/category-barang/' + categoryId + '/update',
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
                    url: '/api/inventaris/category-barang/',
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

        function toggleForm() {
            var formContainer = document.getElementById('form-container');
            var toggleButton = document.getElementById('toggle-form-btn');
            var closeButton = document.getElementById('close-form-btn');

            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
                formContainer.style.maxHeight = formContainer.scrollHeight + 'px';
                toggleButton.innerText = 'Tutup';
            } else if (formContainer.style.display === 'block') {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah Kategori Barang';
            } else {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah Kategori Barang';
            }
        }
    </script>
@endsection
