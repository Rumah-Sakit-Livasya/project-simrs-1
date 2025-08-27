@extends('inc.layout')
@section('title', 'Daftar Order Operasi')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        /* CSS untuk Details Control */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            background-color: #021d39;
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12px;
            background-color: white;
        }

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }

        .details-control {
            cursor: pointer;
        }

        .dt-hasChild {
            background-color: #f5f5f5 !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Order Operasi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('ok.daftar-pasien') }}" method="get">
                                @csrf
                                <div class="row mb-3">
                                    <!-- Tanggal Periode (Dari - Sampai) -->
                                    <div class="col-md-6 mb-3">
                                        <label>Tanggal OK Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ request('tanggal_awal') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Tanggal OK Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 ">
                                        <label>No.RM</label>
                                        <input type="text" class="form-control" id="medical_record_number"
                                            name="medical_record_number" placeholder="Masukkan No.RM"
                                            value="{{ request('medical_record_number') }}">
                                    </div>
                                    <div class="col-md-6 ">
                                        <label>Nama Pasien</label>
                                        <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                            placeholder="Masukkan Nama Pasien" value="{{ request('nama_pasien') }}">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>Ruang Operasi</label>
                                        <select class="form-control select2" id="ruangan_id" name="ruangan_id">
                                            <option value="">Pilih Ruang Operasi</option>
                                            {{-- @foreach ($ruangans as $ruangan)
                                                <option value="{{ $ruangan->id }}"
                                                    {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                                    {{ $ruangan->ruangan }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>Status Registrasi</label>
                                        <select class="form-control select2" id="status_registrasi"
                                            name="status_registrasi">
                                            <option value="">Pilih Status</option>
                                            <option value="aktif"
                                                {{ request('status_registrasi') == 'aktif' ? 'selected' : '' }}>Registrasi
                                                Aktif</option>
                                            <option value="tutup_kunjungan"
                                                {{ request('status_registrasi') == 'tutup_kunjungan' ? 'selected' : '' }}>
                                                Tutup Kunjungan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label>Penjamin</label>
                                        <select class="form-control select2" id="penjamin_id" name="penjamin_id">
                                            <option value="">Pilih Penjamin</option>
                                            @foreach ($penjamins as $penjamin)
                                                <option value="{{ $penjamin->id }}"
                                                    {{ request('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                    {{ $penjamin->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col">
                                        <button type="button" class="btn btn-danger mb-3" id="btn-plasma-view">
                                            <i class="fal fa-desktop-alt mr-1"></i> Tampilan Plasma
                                        </button>
                                    </div>

                                    <!-- Tombol kanan -->
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Order Operasi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                    <strong>Sukses!</strong> {{ session('success') }}
                                </div>
                            @endif

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tgl. Order</th>
                                        <th>No. Reg</th>
                                        <th>Pasien</th>
                                        <th>Penjamin</th>
                                        <th>Asal Pasien</th>
                                        <th>Ruang Operasi</th>
                                        <th>Dokter</th>
                                        <th>User Entry</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $order)
                                        {{-- @dd($order) --}}
                                        <tr data-id="{{ $order->id }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                            <td>{{ $order->registration->registration_number ?? '-' }}</td>
                                            <td>
                                                {{ $order->registration->patient->name ?? '-' }}<br>
                                                <small>{{ $order->registration->patient->medical_record_number ?? '-' }}</small>
                                            </td>
                                            <td>{{ $order->registration->penjamin->nama_perusahaan ?? '-' }}</td>
                                            <td>{{ $order->registration->registration_type ?? '-' }}</td>
                                            <td>{{ $order->ruangan->ruangan ?? '-' }}</td>
                                            <td>{{ $order->doctorOperator?->employee?->fullname ?? '-' }}</td>
                                            <td>{{ $order->user->name ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href=""
                                                        class="btn btn-sm btn-icon btn-danger rounded-circle mr-1 treatment-list-btn"
                                                        data-order-id="{{ $order->id }}" data-toggle="tooltip"
                                                        title="Lihat/Tambah Tindakan">
                                                        <i class="fal fa-heart"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="btn btn-sm btn-icon btn-warning rounded-circle mr-1"
                                                        data-toggle="tooltip" title="Edit">
                                                        <i class="fal fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-danger rounded-circle delete-order-btn"
                                                        data-id="{{ $order->id }}" title="Hapus"> <i
                                                            class="fal fa-trash-alt"></i> </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {

            $('#dt-basic-example tbody').on('click', '.treatment-list-btn', function(e) {
                e.preventDefault(); // Mencegah link default berjalan

                var orderId = $(this).data('order-id');
                // Ganti 'ok.treatment.list' dengan nama rute yang benar untuk halaman treatment list Anda
                // Pastikan rute tersebut menerima parameter {order}
                var url = "{{ route('ok.prosedure', ['orderId' => ':orderId']) }}".replace(':orderId',
                    orderId);

                // Ukuran pop-up window
                var width = 1200;
                var height = 700;
                var left = (screen.width / 2) - (width / 2);
                var top = (screen.height / 2) - (height / 2);

                // Buka pop-up window
                window.open(url, 'TreatmentList', 'width=' + width + ',height=' + height + ',top=' + top +
                    ',left=' + left + ',resizable=yes,scrollbars=yes');
            });
            // Inisialisasi plugin dasar
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2({
                dropdownCssClass: "move-up"
            });
            $('[data-toggle="tooltip"]').tooltip();

            // Format untuk child row
            function formatChildRow(d) {
                return `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">No. Registrasi</th>
                                    <td>${d.registration_number}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pasien</th>
                                    <td>${d.patient_name}</td>
                                </tr>
                                <tr>
                                    <th>No. RM</th>
                                    <td>${d.medical_record_number}</td>
                                </tr>
                                <tr>
                                    <th>Penjamin</th>
                                    <td>${d.penjamin}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Tgl. Operasi</th>
                                    <td>${d.tgl_operasi}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Operasi</th>
                                    <td>${d.jenis_operasi}</td>
                                </tr>
                                <tr>
                                    <th>Kategori Operasi</th>
                                    <td>${d.kategori_operasi}</td>
                                </tr>
                                <tr>
                                    <th>Diagnosa Awal</th>
                                    <td>${d.diagnosa_awal}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Prosedur Operasi</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tindakan</th>
                                        <th>Dokter Operator</th>
                                        <th>Asisten Dokter</th>
                                        <th>Dokter Anestesi</th>
                                        <th>Asisten Anestesi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${d.prosedur_operasi.map(prosedur => `
                                                                                                                                                                                                                                                                <tr>
                                                                                                                                                                                                                                                                    <td>${prosedur.tindakan}</td>
                                                                                                                                                                                                                                                                    <td>${prosedur.dokter_operator}</td>
                                                                                                                                                                                                                                                                    <td>${prosedur.ass_dokter_operator}</td>
                                                                                                                                                                                                                                                                    <td>${prosedur.dokter_anestesi}</td>
                                                                                                                                                                                                                                                                    <td>${prosedur.ass_dokter_anestesi}</td>
                                                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                                                            `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // Inisialisasi DataTable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 20,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                        className: 'btn-outline-danger btn-sm mr-1',
                        title: 'Daftar Order Operasi',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Order Operasi',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Order Operasi',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 9] // Kolom # dan aksi tidak bisa diurutkan
                }]
            });

            // Logika child row menggunakan API DataTables
            $('#dt-basic-example tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var orderId = tr.data('id');

                if (row.child.isShown()) {
                    // Baris ini sudah terbuka, tutup.
                    row.child.hide();
                    tr.removeClass('dt-hasChild');
                } else {
                    // Ambil data detail via AJAX
                    $.ajax({
                        url: '/api/operasi/' + orderId + '/detail',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                row.child(formatChildRow(response.data)).show();
                                tr.addClass('dt-hasChild');
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Gagal memuat detail order operasi');
                        }
                    });
                }
            });

            $('#dt-basic-example tbody').on('click', '.delete-order-btn', function(e) {
                e.preventDefault();

                var button = $(this);
                var orderId = button.data('id');
                var row = button.closest('tr'); // Dapatkan baris tabel untuk dihapus nanti

                // Tampilkan konfirmasi menggunakan SweetAlert2
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Order operasi ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user konfirmasi, buat URL dan kirim request AJAX

                        // Menggunakan template URL dari route Laravel
                        let urlTemplate =
                            "{{ route('operasi.order.delete', ['order' => ':id']) }}";
                        let deleteUrl = urlTemplate.replace(':id', orderId);

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                // CSRF Token sangat penting
                                "_token": "{{ csrf_token() }}",
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    // Tampilkan notifikasi sukses menggunakan Toastr
                                    toastr.success(response.message);

                                    // Hapus baris dari DataTable secara visual
                                    table.row(row).remove().draw(false);
                                } else {
                                    // Tampilkan pesan error dari server jika success=false
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                // Tangani error teknis (misal: 404, 500, dll)
                                let errorMessage = xhr.responseJSON ? xhr.responseJSON
                                    .message : 'Terjadi kesalahan saat menghapus data.';
                                Swal.fire(
                                    'Error!',
                                    errorMessage,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#btn-plasma-view').on('click', function() {
                var url = "{{ route('ok.plasma') }}";

                // Ukuran pop-up window, bisa disesuaikan
                var width = screen.width;
                var height = screen.height;
                var left = 0;
                var top = 0;

                // Buka pop-up window
                window.open(url, 'PlasmaJadwalOperasi', 'width=' + width + ',height=' + height + ',top=' +
                    top +
                    ',left=' + left + ',resizable=yes,scrollbars=yes');
            });


        });
    </script>
@endsection
