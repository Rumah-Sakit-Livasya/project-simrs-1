@extends('inc.layout')
@section('title', 'Template Barang')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Template Barang
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">

                <div id="form-container" style="display: none;" class="panel form-container">
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Template Barang
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="javascript:void(0)" method="post"
                                enctype="multipart/form-data" id="store-form">
                                @csrf
                                <div class="form-group">
                                    <label for="foto2">Gambar</label>
                                    <img class="image-preview2 img-fluid mb-3 col-sm-5 d-block">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="foto2" name="foto"
                                            onchange="previewImage()">
                                        <label class="custom-file-label" for="foto">Pilih Gambar Galeri</label>
                                    </div>
                                    @error('foto')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label class="form-label" for="kategoriBarang">
                                            Kategori Barang
                                        </label>
                                        <select class="form-control w-100 @error('category_id') is-invalid @enderror"
                                            id="kategoriBarang" name="category_id">
                                            <optgroup label="Kategori Barang">
                                                @foreach ($categoryBarang as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="name">Nama Barang</label>
                                    <input type="text" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" placeholder="Nama Barang">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="barang_code">Kode Barang</label>
                                    <input type="text" value="{{ old('barang_code') }}"
                                        class="form-control @error('barang_code') is-invalid @enderror" id="barang_code"
                                        name="barang_code" placeholder="Kode Barang"
                                        onkeyup="this.value = this.value.toUpperCase()">
                                    @error('barang_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                            Table <span class="fw-300"><i>Template Barang</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            @include('pages.inventaris.template-barang.partials.template-barang-table')
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('pages.inventaris.template-barang.partials.edit')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>

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

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/inventaris/template-barang/' + templateId + '/delete',
                        type: 'DELETE',
                        success: function(response) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                console.log('Reloading the page now.');
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan: ' + error);
                        }
                    });
                } else {
                    console.log('Penghapusan dibatalkan oleh pengguna.');
                }
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
                toggleButton.innerText = 'Tambah Template Barang';
            } else {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah Template Barang';
            }
        }
    </script>
@endsection
