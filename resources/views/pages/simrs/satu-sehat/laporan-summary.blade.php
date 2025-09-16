@extends('inc.layout')
@section('title', 'Laporan Summary Satu Sehat')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Satu Sehat</a></li>
            <li class="breadcrumb-item active">Laporan Summary</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        {{-- Panel Filter --}}
        <div class="panel">
            <div class="panel-hdr">
                <h2 class="fw-bolder"><i class="fal fa-search mr-2"></i>Form Pencarian</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Periode</label>
                            <div class="input-daterange input-group" id="datepicker-5">
                                <input type="text" class="form-control" name="start_date" id="start_date"
                                    value="{{ \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}">
                                <div class="input-group-append input-group-prepend"><span class="input-group-text fs-xl"><i
                                            class="fal fa-long-arrow-right"></i></span></div>
                                <input type="text" class="form-control" name="end_date" id="end_date"
                                    value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="norm">No. RM</label>
                            <input type="text" id="norm" class="form-control" placeholder="Masukkan No. RM">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="noreg">No. Registrasi</label>
                            <input type="text" id="noreg" class="form-control" placeholder="Masukkan No. Registrasi">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="nama_pasien">Nama Pasien</label>
                            <input type="text" id="nama_pasien" class="form-control" placeholder="Cari nama pasien...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="stat_pasien">Status Pasien</label>
                            <select class="form-control select2" id="stat_pasien">
                                <option value="">Semua</option>
                                <option value="t">Pulang</option>
                                <option value="f">Belum Pulang</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="tipe_pasien">Tipe Pasien</label>
                            <select class="form-control select2" id="tipe_pasien">
                                <option value="">Semua</option>
                                <option value="igd">IGD</option>
                                <option value="rajal">Rawat Jalan</option>
                                <option value="ranap">Rawat Inap</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row">
                    <button class="btn btn-primary ml-auto" id="btn-tampil"><i class="fal fa-search mr-2"></i>
                        Tampil</button>
                </div>
            </div>
        </div>

        {{-- Panel Hasil --}}
        <div class="panel">
            <div class="panel-hdr">
                <h2 class="fw-bolder">Hasil Pencarian</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="dt-laporan" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Reg</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Encounter</th>
                                <th>Condition</th>
                                <th>Observation</th>
                                {{-- Tambahkan header lain jika perlu --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi
            $('.select2').select2({
                width: '100%'
            });
            $('#datepicker-5').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy"
            });

            const table = $('#dt-laporan').DataTable({
                responsive: true,
                pageLength: 25,
                processing: true,
                serverSide: false, // Data akan di-load via AJAX manual
                data: [], // Mulai dengan data kosong
                columns: [{
                        data: 'no_registrasi'
                    },
                    {
                        data: 'no_rm'
                    },
                    {
                        data: 'nama_pasien'
                    },
                    {
                        data: 'encounter',
                        render: function(data) {
                            const color = data === 'Berhasil' ? 'success' : (data === 'Gagal' ?
                                'danger' : 'warning');
                            return `<span class="badge badge-${color} badge-pill">${data}</span>`;
                        }
                    },
                    {
                        data: 'condition',
                        render: function(data) {
                            return `<span class="badge badge-${data === 'Berhasil' ? 'success' : 'danger'} badge-pill">${data}</span>`;
                        }
                    },
                    {
                        data: 'observation',
                        render: function(data) {
                            return `<span class="badge badge-${data === 'Berhasil' ? 'success' : 'danger'} badge-pill">${data}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data) {
                                return `<button class="btn btn-xs btn-warning btn-resend" data-id="${data}" title="Kirim Ulang"><i class="fal fa-redo"></i> Kirim Ulang</button>`;
                            }
                            return '';
                        }
                    }
                ]
            });

            $('#btn-tampil').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                );

                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.laporan.summary.data') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        norm: $('#norm').val(),
                        noreg: $('#noreg').val(),
                        nama_pasien: $('#nama_pasien').val(),
                        stat_pasien: $('#stat_pasien').val(),
                        tipe_pasien: $('#tipe_pasien').val(),
                    },
                    success: function(response) {
                        table.clear().rows.add(response.data).draw();
                    },
                    error: function() {
                        showErrorAlert('Gagal mengambil data dari server.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fal fa-search mr-2"></i> Tampil');
                    }
                });
            });

            // Event handler untuk tombol Kirim Ulang (menggunakan event delegation)
            $('#dt-laporan').on('click', '.btn-resend', function() {
                const btn = $(this);
                const regId = btn.data('id');
                const url = `{{ url('satu-sehat/laporan-summary') }}/${regId}/resend`;

                Swal.fire({
                    title: 'Kirim Ulang Data?',
                    text: `Anda akan mencoba mengirim ulang data Encounter untuk registrasi ini.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim Ulang!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm"></span>');
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                showSuccessAlert(response.message);
                                // Opsi: Reload data tabel untuk melihat status terbaru
                                $('#btn-tampil').click();
                            },
                            error: function(jqXHR) {
                                const errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON
                                    .message : 'Gagal mengirim ulang.';
                                showErrorAlert(errorMsg);
                                btn.prop('disabled', false).html(
                                    '<i class="fal fa-redo"></i> Kirim Ulang');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
