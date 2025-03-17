@php
    use App\Models\Inventaris\Barang;
@endphp

@extends('inc.layout')
@section('title', 'Ruangan')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Ruang
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="form-container" style="display: none;" class="panel form-container">
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Ruang
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nama Ruang</label>
                                    <input type="text" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" placeholder="Nama Ruang">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @include('components.notification.error')
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="room_code">Kode Ruang</label>
                                    <input type="text" value="{{ old('room_code') }}"
                                        class="form-control @error('room_code') is-invalid @enderror" id="room_code"
                                        name="room_code" placeholder="Kode Ruang"
                                        onkeyup="this.value = this.value.toUpperCase()">
                                    @error('room_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @include('components.notification.error')
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="floor">Lantai</label>
                                    <input type="number" value="{{ old('floor') }}"
                                        class="form-control @error('floor') is-invalid @enderror" id="floor"
                                        name="floor" placeholder="Lantai">
                                    @error('floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @include('components.notification.error')
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="create-organization_id">Unit Penanggungjawab Ruangan <i
                                            class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip"
                                            title="Unit yang bertanggungjawab atas ruangan ini"></i></label>
                                    <!-- Mengubah input menjadi select2 -->
                                    <select class="select2 form-control @error('organization_id') is-invalid @enderror"
                                        name="organization_id[]" id="create-organization_id" multiple>
                                        @foreach ($organizations as $organization)
                                            <option value="{{ $organization->id }}">
                                                {{ old('organization_id', $organization->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('organization_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @include('components.notification.error')
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
                            Rooms <span class="fw-300"><i>Table</i></span>
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
                                        <th style="white-space: nowrap">Nama Ruang</th>
                                        <th style="white-space: nowrap">Kode Ruang</th>
                                        <th style="white-space: nowrap">Jumlah Barang</th>
                                        <th style="white-space: nowrap">Lantai</th>
                                        <th style="white-space: nowrap">Status</th>
                                        <th style="white-space: nowrap">Penanggung Jawab</th>
                                        <!-- Added column for responsible unit -->
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                <a href="/inventaris/room-maintenance/{{ $row->id }}"
                                                    class="">{{ strtoupper($row->name) }}
                                                </a>
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->room_code }}</td>
                                            <td style="white-space: nowrap">
                                                {{ count(Barang::where('room_id', $row->id)->get()) }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->floor }}</td>
                                            <td style="white-space: nowrap">{{ $row->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                @foreach ($row->organizations as $organization)
                                                    <!-- Displaying responsible organizations -->
                                                    <span class="badge badge-info">{{ $organization->name }}</span>
                                                @endforeach
                                            </td>
                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $row->id }}" title="Ubah" data-toggle="tooltip"
                                                    data-placement="top" onclick="ubahRuangan(event)">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
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
                                        <th style="white-space: nowrap">Nama Ruang</th>
                                        <th style="white-space: nowrap">Kode Ruang</th>
                                        <th style="white-space: nowrap">Jumlah Barang</th>
                                        <th style="white-space: nowrap">Lantai</th>
                                        <th style="white-space: nowrap">Status</th>
                                        <th style="white-space: nowrap">Penanggung Jawab</th>
                                        <!-- Added footer for responsible unit -->
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

    @include('app-type.logistik.rooms.partials.edit')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $("form").on("submit", function(e) {
                // Disable tombol submit setelah form dikirim
                $(this).find("button[type='submit']").prop("disabled", true);
            });

            $(function() {
                $('#store-form #create-organization_id').select2({
                    placeholder: 'Pilih data berikut',
                    allowClear: true
                });

                $('#update-form #update-organization_id').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#modal-edit'),
                    allowClear: true
                });
            });

            let roomId = null;

            $('.btn-edit').click(function() {

            });

            $('.btn-delete').click(function() {
                var roomId = $(this).attr('data-id');

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
                        // If user clicks "Yes, delete it!", proceed with the AJAX request
                        $.ajax({
                            url: '/api/inventaris/room-maintenance/' + roomId + '/delete',
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

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/room-maintenance',
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

        function ubahRuangan(event) {
            event.preventDefault(); // Prevent default form submission
            let button = event.currentTarget;
            let roomId = button.getAttribute('data-id');
            idRoom = roomId;
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');

            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');

            // Show the modal
            $('#modal-edit').modal('show');

            $.ajax({
                url: '/api/inventaris/room-maintenance/' + idRoom,
                type: 'GET',
                success: function(response) {
                    // Populate the form with the received data
                    $('#modal-edit #name').val(response.name);
                    $('#modal-edit #room_code').val(response.room_code);
                    $('#modal-edit #floor').val(response.floor);
                    $('#modal-edit #status[value="' + response.status + '"]').prop('checked', true);

                    // Populate the Select2 for organizations
                    $('#modal-edit #update-organization_id').val(response.organization_ids).trigger(
                        'change'); // Assuming response.organization_ids is an array of IDs

                    // Reset the icon and spinner after loading data
                    ikonEdit.classList.remove('d-none');
                    spinnerText.classList.add('d-none');
                },
                error: function(xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan: ' + error);
                    // Reset the icon and spinner in case of error
                    ikonEdit.classList.remove('d-none');
                    spinnerText.classList.add('d-none');
                }
            });
        }


        $('#update-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: '/api/inventaris/room-maintenance/' + idRoom + '/update',
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
