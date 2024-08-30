@php
    use App\Models\Inventaris\RoomMaintenance;
@endphp

<table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
    <thead>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama Barang</th>
            {{-- <th style="white-space: nowrap">Merk</th>
            <th style="white-space: nowrap">Kategori Barang</th>
            <th style="white-space: nowrap">Urutan Barang</th> --}}
            <th style="white-space: nowrap">Kode Barang</th>
            <th style="white-space: nowrap">Ruangan</th>
            <th style="white-space: nowrap">Tanggal Input</th>
            <th style="white-space: nowrap" class="no-export">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barang as $row)
            <tr>
                <td style="white-space: normal">{{ $loop->iteration }}</td>
                @if ($row->custom_name === null)
                    <td style="white-space: normal">
                        <a
                            href="{{ route('inventaris.maintenance.index', $row->id) }}">{{ strtoupper($row->template_barang->name) }}</a>
                    </td>
                @else
                    <td style="white-space: normal"><a
                            href="{{ route('inventaris.maintenance.index', $row->id) }}">{{ strtoupper($row->custom_name) }}</a>
                    </td>
                @endif
                {{-- <td style="white-space: normal">
                    {{ $row->merk === null ? '*tidak diketahui' : strtoupper($row->merk) }}
                </td>
                <td style="white-space: normal">
                    {{ strtoupper($row->template_barang->category->name) }}
                </td>
                <td style="white-space: normal">{{ $row->urutan_barang }}</td> --}}
                <td style="white-space: normal">
                    {{ strtoupper($row->item_code . ' ' . $row->merk) }}
                </td>
                @if ($row->room_id === 0)
                    <td style="white-space: normal">*Barang belum di Ruangan</td>
                @else
                    @if ($row->pinjam == true)
                        <td style="white-space: normal">Barang dipinjam ke ruang
                            {{ RoomMaintenance::where('id', $row->ruang_pinjam)->first()->name }}
                        </td>
                    @else
                        <td style="white-space: normal"><a
                                href="{{ $row->room ? route('inventaris.rooms.show', $row->room->id) : 'javascript:void(0)' }}"
                                class="">{{ $row->room ? strtoupper($row->room->name) : '*Ruangan tidak ada atau sudah dihapus' }}</a>
                        </td>
                    @endif
                @endif
                <td style="white-space: normal">{{ $row->created_at }}</td>
                <td style="white-space: nowrap" class="no-export">
                    <button class="badge mx-1 badge-primary p-2 border-0 text-white btn-edit"
                        data-id="{{ $row->id }}">
                        <i class="fal fa-pencil"></i>
                    </button>

                    <button class="badge mx-1 badge-secondary p-2 border-0 text-white btn-move"
                        data-id="{{ $row->id }}">
                        <i class="fal fa-sign-in"></i>
                    </button>

                    @if ($row->pinjam == false && $row->room != null)
                        <button class="badge mx-1 badge-success p-2 border-0 text-white btn-pinjam"
                            data-id="{{ $row->id }}">
                            <i class="fal fa-arrow-circle-right"></i>
                        </button>
                    @endif

                    @if ($row->pinjam == true && $row->room != null)
                        <button class="badge mx-1 badge-success p-2 border-0 text-white btn-back"
                            data-id="{{ $row->id }}">
                            <i class="fal fa-arrow-circle-left"></i>
                        </button>
                    @endif

                    <button class="badge mx-1 badge-danger p-2 border-0 text-white btn-delete"
                        data-id="{{ $row->id }}">
                        <i class="fal fa-trash"></i>
                    </button>
                </td>
            </tr>

            {{-- FORM --}}
            {{-- @include('pages.barang.formUpdateBarang')
            @include('pages.barang.formPindahkanBarang')
            @include('pages.barang.formPinjamBarang')
            @include('pages.barang.formKembalikanBarang') --}}
            {{-- ./ FORM --}}
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            {{-- <th>Merk</th>
            <th>Kategori Barang</th>
            <th>Urutan Barang</th> --}}
            <th>Kode Barang</th>
            <th>Ruangan</th>
            <th>Tanggal Input</th>
            <th class="no-export" style="white-space: nowrap">Aksi</th>
        </tr>
    </tfoot>
</table>

@include('pages.inventaris.barang.partials.form-pindahkan-barang')
@include('pages.inventaris.barang.partials.form-pinjam-barang')
@include('pages.inventaris.barang.partials.form-kembalikan-barang')
@include('pages.inventaris.barang.partials.form-update-barang')

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let barangId = null;

            $('.btn-edit').click(function() {
                $('#modal-ubah').modal('show');
                var barangId = $(this).attr('data-id');

                $.ajax({
                    url: '/api/inventaris/barang/' + barangId,
                    type: 'GET',
                    success: function(response) {
                        var templateBarangId = response.template_barang_id;
                        var kondisi = response.condition;
                        var biddingYear = response.bidding_year;
                        // Assuming the response contains the URL of the image
                        $('#modal-ubah #custom_name').val(response.custom_name);
                        $('#modal-ubah #barang_id').val(response.id);
                        $('#modal-ubah #item_code').val(response.item_code);
                        $('#modal-ubah #merk').val(response.merk);
                        $('#modal-ubah #urutan_barang').val(response.urutan_barang);
                        // $('#modal-ubah #template_barang_id').val(templateBarangId).select2({
                        //     dropdownParent: $('#modal-ubah'),
                        // });
                        $('#modal-ubah #template_barang_id').val(templateBarangId).select2({
                            dropdownParent: $('#modal-ubah'),
                            minimumResultsForSearch: Infinity // Hide the search box if not needed
                        });

                        // Make the dropdown readonly (disable interaction)
                        $('#modal-ubah #template_barang_id').on(
                            'select2:opening select2:closing',
                            function(e) {
                                e.preventDefault();
                            });
                        $('#modal-ubah #condition').val(kondisi).select2({
                            dropdownParent: $('#modal-ubah'),
                        });
                        $('#modal-ubah #bidding_year').val(biddingYear).select2({
                            dropdownParent: $('#modal-ubah'),
                        });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/barang/' + barangId + '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#modal-ubah').find('.ikon-tambah').hide();
                        $('#modal-ubah').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-ubah').modal('hide');
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

                            $('#modal-ubah').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-ubah').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('.btn-pinjam').click(function() {
                $('#modal-pinjam').modal('show');
                var barangId = $(this).attr('data-id');

                $.ajax({
                    url: '/api/inventaris/barang/' + barangId,
                    type: 'GET',
                    success: function(response) {
                        var roomId = response.room_id;
                        // Assuming the response contains the URL of the image
                        $('#modal-pinjam #barang_id').val(response.id);
                        $('#modal-pinjam #room_id').val(roomId).select2({
                            dropdownParent: $('#modal-pinjam'),
                        });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#pinjam-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/barang/pinjam',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-pinjam').modal('hide');
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

                            $('#modal-pinjam').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-pinjam').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('.btn-move').click(function() {
                $('#modal-move').modal('show');
                var barangId = $(this).attr('data-id');

                $.ajax({
                    url: '/api/inventaris/barang/' + barangId,
                    type: 'GET',
                    success: function(response) {
                        var roomId = response.room_id;
                        // Assuming the response contains the URL of the image
                        $('#modal-move #barang_id').val(response.id);
                        $('#modal-move #item_code').val(response.item_code);
                        $('#modal-move #room_id').val(roomId).select2({
                            dropdownParent: $('#modal-move'),
                        });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#move-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/barang/move',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#modal-move').find('.ikon-tambah').hide();
                        $('#modal-move').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-move').modal('hide');
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

                            $('#modal-move').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-move').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('.btn-back').click(function() {
                $('#modal-back').modal('show');
                var barangId = $(this).attr('data-id');

                $.ajax({
                    url: '/api/inventaris/barang/' + barangId,
                    type: 'GET',
                    success: function(response) {
                        var roomId = response.room_id;
                        // Initialize the Select2 dropdown
                        $('#modal-back #barang_id').val(response.id);
                        $('#modal-back #ruang_pinjam').val(roomId).select2({
                            dropdownParent: $('#modal-back'),
                            minimumResultsForSearch: Infinity // Hide the search box if not needed
                        });

                        // Make the dropdown readonly (disable interaction)
                        $('#modal-back #ruang_pinjam').on('select2:opening select2:closing',
                            function(e) {
                                e.preventDefault();
                            });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#back-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/inventaris/barang/back',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-back').modal('hide');
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

                            $('#modal-back').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-back').modal('hide');
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
                    url: '/api/inventaris/barang/',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah').modal('hide');
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

                            $('#modal-tambah').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('.btn-delete').click(function() {
                var barangId = $(this).attr('data-id');

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
                            url: '/api/inventaris/barang/' + barangId + '/delete',
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

            $(function() {
                $('#room_id').select2({
                    placeholder: 'Pilih Ruangan',
                });
                $('#company_id').select2({
                    placeholder: 'Pilih Perusahaan',
                });
                $('#barang_category_id').select2();
                $('#template_barang_id').select2();
                $('#tambahBarang').select2({
                    placeholder: 'Pilih Barang',
                });
                $('#kondisiBarang').select2({
                    placeholder: 'Pilih Kondisi Barang',
                });
                $('#tahunPengadaan').select2({
                    placeholder: 'Pilih Tahun Pengadaan',
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
                toggleButton.innerText = 'Tambah Barang';
            } else {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah Barang';
            }
        }
    </script>
@endsection
