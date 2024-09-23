@extends('inc.layout')
@section('title', 'Jadwal Dokter')
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
            /* Atur lebar label agar semua label sejajar */
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
                /* Biarkan lebar label mengikuti konten */
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

                            <form action="/daftar-rekam-medis" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_tindakan_1" class="form-label">Nama</label>
                                            <input type="text" name="nama_tindakan" id="nama_tindakan_1"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0">
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

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Jadwal Dokter
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Departement</th>
                                            <th>Senin</th>
                                            <th>Selasa</th>
                                            <th>Rabu</th>
                                            <th>Kamis</th>
                                            <th>Jumat</th>
                                            <th>Sabtu</th>
                                            <th>Minggu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($departements as $row)
                                            <tr>
                                                <td>
                                                    <strong>{{ $row->name }}</strong>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                            @if ($row->employees->count() > 0)
                                                @foreach ($row->employees as $employee)
                                                    <tr>
                                                        <td>
                                                            {{ $employee->fullname ?? '' }}
                                                        </td>

                                                        {{-- Definisikan kolom untuk tiap hari --}}
                                                        @php
                                                            $days = [
                                                                'Senin',
                                                                'Selasa',
                                                                'Rabu',
                                                                'Kamis',
                                                                'Jumat',
                                                                'Sabtu',
                                                                'Minggu',
                                                            ];

                                                            // Buat array untuk setiap hari
                                                            $scheduleByDay = array_fill_keys($days, []);

                                                            // Cek jika employee memiliki doctor, lalu ambil jadwal
                                                            if ($employee->doctor && $employee->doctor->schedules) {
                                                                foreach ($employee->doctor->schedules as $schedule) {
                                                                    if (in_array($schedule->hari, $days)) {
                                                                        $scheduleByDay[$schedule->hari][] = $schedule;
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        {{-- Tampilkan jadwal per hari --}}
                                                        @foreach ($days as $day)
                                                            <td>
                                                                @if (isset($scheduleByDay[$day]) && count($scheduleByDay[$day]) > 0)
                                                                    @foreach ($scheduleByDay[$day] as $schedule)
                                                                        <span class="text-info d-block">
                                                                            {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('G:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($schedule->jam_selesai)->format('G:i') }}
                                                                        </span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" class="text-center">
                                                <button type="button"
                                                    class="btn btn-outline-primary waves-effect waves-themed"
                                                    id="btn-tambah-jadwal-dokter">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Jadwal Dokter
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- datatable end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.simrs.master-data.jadwal-dokter.partials.create')
    @include('pages.simrs.master-data.jadwal-dokter.partials.edit')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let jadwalId = null;
            $('#loading-spinner').show();

            $('#modal-tambah-jadwal-dokter .select2').select2({
                dropdownParent: $('#modal-tambah-jadwal-dokter')
            });

            $('#btn-tambah-jadwal-dokter').click(function() {
                $('#modal-tambah-jadwal-dokter').modal('show');
            });

            $('.btn-edit').click(function() {
                console.log('clicked');
                $('#modal-edit-jadwal-dokter').modal('show');
                jadwalId = $(this).attr('data-id');
                $('#modal-edit-jadwal-dokter form').attr('data-id', jadwalId);

                $.ajax({
                    url: '/api/simrs/master-data/jadwal-dokter/daftar-jadwal-dokter/' +
                        jadwalId,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-edit-jadwal-dokter #tipe_edit').val(response.tipe).select2({
                            dropdownParent: $('#modal-edit-jadwal-dokter')
                        });
                        $('#modal-edit-jadwal-dokter input[name="kode"]').val(response.kode);
                        $('#modal-edit-jadwal-dokter input[name="nama_jadwal-dokter"]').val(
                            response
                            .nama_jadwal - dokter);
                        $('#modal-edit-jadwal-dokter input[name="nama_billing"]').val(response
                            .nama_billing);
                    },
                    error: function(xhr, status, error) {
                        $('#modal-edit-jadwal-dokter').modal('hide');
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-delete').click(function() {
                var jadwalId = $(this).attr('data-id');

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/simrs/master-data/jadwal-dokter/daftar-jadwal-dokter/' +
                            jadwalId +
                            '/delete',
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

                var formData = $(this).serialize();
                jadwalId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/simrs/master-data/jadwal-dokter/daftar-jadwal-dokter/' +
                        jadwalId +
                        '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit-jadwal-dokter').modal('hide');
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

                            $('#modal-edit-jadwal-dokter').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit-jadwal-dokter').modal('hide');
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
                    url: '/api/simrs/master-data/jadwal-dokter/daftar-jadwal-dokter',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-jadwal-dokter').modal('hide');
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

                            $('#modal-tambah-jadwal-dokter').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-jadwal-dokter').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            // initialize datatable
            $('#dt-basic-example').DataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: false, // Responsif diaktifkan
                scrollX: true, // Tambahkan scroll horizontal
                ordering: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end buttons-container'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm custom-margin'
                    }
                ]
            });

        });
    </script>
@endsection
