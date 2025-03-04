@extends('inc.layout')
@section('title', ' Checklist Harian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Checklist
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">

                <div id="form-container" style="display: none;" class="panel form-container">
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Checklist
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="javascript:void(0)" id="store-form" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="checklist_harian_category_id">Kategori</label>
                                    <select
                                        class="form-control select2 @error('checklist_harian_category_id') is-invalid @enderror"
                                        id="checklist_harian_category_id" name="checklist_harian_category_id">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($checklistKategori as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('checklist_harian_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('checklist_harian_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="kegiatan">Kegiatan </label>
                                    <input type="text" value="{{ old('kegiatan') }}"
                                        class="form-control @error('kegiatan') is-invalid @enderror" id="kegiatan"
                                        name="kegiatan" placeholder="Kegiatan">
                                    @error('kegiatan')
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
                            Table <span class="fw-300"><i> Checklist</i></span>
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
                                        <th style="white-space: nowrap">Kategori</th>
                                        <th style="white-space: nowrap">Kegiatan</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checklistHarian as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ $row->checklist_harian_category->name }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->kegiatan }}</td>
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
                                        <th style="white-space: nowrap">Kategori</th>
                                        <th style="white-space: nowrap">Kegiatan</th>
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

    @include('pages.checklist-harian.admin.partials.edit')
@endsection
@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- <script src="/js/datatable/jszip.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            $("form").on("submit", function(e) {
                // Disable tombol submit setelah form dikirim
                $(this).find("button[type='submit']").prop("disabled", true);
            });

            $('#store-form .select2').select2({
                placeholder: "Pilih Status", // Teks placeholder
                allowClear: true, // Memungkinkan pengguna untuk menghapus pilihan
                // dropdownCssClass: "select2-dropdown", // Menyesuaikan class CSS dropdown
            });

            let checklistId = null;

            $('.btn-edit').click(function() {
                $('#modal-edit').modal('show');
                checklistId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/dashboard/checklist-harian/' + checklistId,
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data yang diterima
                        $('#modal-edit #kegiatan').val(response.kegiatan);
                        $('#modal-edit #checklist_harian_category_id').val(response
                            .checklist_harian_category.name).trigger(
                            'change'); // Set value Select2
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('.btn-delete').click(function() {
                var checklistId = $(this).attr('data-id');

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
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        // If the user confirms deletion, proceed with the AJAX request
                        $.ajax({
                            url: '/api/dashboard/checklist-harian/category/' + checklistId +
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
                    url: '/api/dashboard/checklist-harian/' + checklistId + '/update',
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
                    url: '/api/dashboard/checklist-harian/store',
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

            $('#dt-basic-example').DataTable({
                // responsive: true,
                // scrollY: 400,
                // scrollX: true,
                // scrollCollapse: true,
                // paging: true,
                pageLength: 200,
                //fixedColumns: true,
                fixedColumns: {
                    leftColumns: 2,
                },
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="fas fa-eye"></i> Visibility',
                        titleAttr: 'Col visibility',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        titleAttr: 'Print Table',
                        className: 'btn-primary',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '12px'); // Menambahkan kelas dan menyesuaikan ukuran font
                            $(win.document.body).find('thead').addClass(
                                'thead-light'); // Menambahkan kelas untuk style header
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        titleAttr: 'Export to Excel',
                        className: 'btn-primary',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        }
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
                toggleButton.innerText = 'Tambah  Checklist';
            } else {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah  Checklist';
            }
        }
    </script>
@endsection
