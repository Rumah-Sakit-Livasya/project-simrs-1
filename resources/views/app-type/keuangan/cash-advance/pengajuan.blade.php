@extends('inc.layout')
@section('title', 'Daftar Pengajuan')
@section('content')
    <style>
        /* ... Semua CSS Anda, tidak ada perubahan ... */
        table {
            font-size: 8pt !important;
        }

        .badge-waiting {
            background-color: #f39c12;
            color: white;
        }

        .badge-approved {
            background-color: #00a65a;
            color: white;
        }

        .badge-rejected {
            background-color: #dd4b39;
            color: white;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian Pengajuan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form">
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="tanggal_awal"
                                                name="tanggal_awal" placeholder="Pilih Tanggal Awal">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="tanggal_akhir"
                                                name="tanggal_akhir" placeholder="Pilih Tanggal Akhir">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Kode Pengajuan</label>
                                        <input type="text" class="form-control" id="kode_pengaju" name="kode_pengaju"
                                            placeholder="Masukkan kode pengajuan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Status</label>
                                        <select class="form-control select2" id="status" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="pending">Menunggu</option>
                                            <option value="approved">Disetujui</option>
                                            <option value="rejected">Ditolak</option>
                                            <option value="partial">Dicairkan Sebagian</option>
                                            <option value="closed">Selesai</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Nama Pengaju</label>
                                        <input type="text" class="form-control" id="nama_pengaju" name="nama_pengaju"
                                            placeholder="Masukkan Nama Pengaju">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" id="btn-search" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <a href="{{ route('keuangan.cash-advance.pengajuan.create') }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fal fa-plus mr-1"></i> Tambah Baru
                                    </a>
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
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Pengajuan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif --}}

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th style="width: 10px;" class="text-center"><input type="checkbox" id="select_all">
                                        </th>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pengaju</th>
                                        <th>Pengajuan</th>
                                        <th>Disetujui</th>
                                        <th>User Entry</th>
                                        <th>Status</th>
                                        <th style="width: 50px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="row-checkbox" value="{{ $item->id }}">
                                            </td>
                                            <td>{{ $item->kode_pengajuan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                            <td>{{ $item->pengaju->name ?? 'N/A' }}</td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($item->total_nominal_pengajuan, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ 'Rp ' . number_format($item->total_nominal_disetujui, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $item->userEntry->name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                @if ($item->status == 'pending')
                                                    <span class="badge badge-waiting">Menunggu</span>
                                                @elseif ($item->status == 'approved' || $item->status == 'partial' || $item->status == 'closed')
                                                    <span class="badge badge-approved">Disetujui</span>
                                                @elseif ($item->status == 'rejected')
                                                    <span class="badge badge-rejected">Ditolak</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($item->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('keuangan.cash-advance.pengajuan.proses', $item->id) }}"
                                                    class="btn btn-primary btn-xs" title="Lihat Detail & Proses">
                                                    <i class="fal fa-search-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3">

                                <button class="btn btn-success" id="btn-approve" disabled>
                                    <i class="fal fa-check-circle mr-1"></i>
                                    Approve Pilihan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Otorisasi --}}
    {{-- file: pengajuan.blade.php --}}

    {{-- Modal Otorisasi --}}
    <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvalModalLabel">Form Otorisasi Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="approvalForm">
                    <div class="modal-body">
                        <div id="modal-alert" class="alert alert-danger" style="display: none;"></div>
                        <input type="hidden" name="ids" id="approval_ids">
                        <div class="form-group">
                            <label for="otorisasi_id">User Otorisasi <span class="text-danger">*</span></label>
                            <select class="form-control select2-modal" id="otorisasi_id" name="otorisasi_id" required
                                style="width: 100%;">
                                @if (isset($userOtorisasi))
                                    {{-- PERBAIKAN DI SINI --}}
                                    <option value="{{ $userOtorisasi->id }}">{{ $userOtorisasi->name }}</option>
                                @else
                                    <option value="">User Otorisasi tidak ditemukan</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="catatan">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-approval">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                style="display: none;"></span>
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. SETUP & INISIALISASI PLUGIN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id'
            });

            $('.select2').select2({
                placeholder: 'Pilih Status'
            });
            $('.select2-modal').select2({
                dropdownParent: $('#approvalModal')
            });

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
                        title: 'Daftar Pengajuan',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1',
                        title: 'Daftar Pengajuan',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print mr-1"></i> Print',
                        className: 'btn-outline-primary btn-sm',
                        title: 'Daftar Pengajuan',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
                order: [
                    [2, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 8]
                }],
                language: {
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                $('.loading-overlay').css('display', 'flex'); // Tampilkan loading

                $.ajax({
                    url: "{{ route('keuangan.ap-supplier.index') }}",
                    type: "GET",
                    data: $(this).serialize(), // Kirim semua data form
                    success: function(response) {
                        // Panggil fungsi untuk membangun ulang tabel
                        updateTable(response);
                        $('.loading-overlay').hide(); // Sembunyikan loading
                    },
                    error: function(xhr) {
                        console.error("Error fetching data: ", xhr);
                        toastr.error('Gagal mengambil data dari server.');
                        $('.loading-overlay').hide(); // Sembunyikan loading
                    }
                });
            });

            // 2. LOGIKA UNTUK TOMBOL & CHECKBOX
            function checkCheckboxes() {
                var checkedRows = $('.row-checkbox:checked');
                var checkedCount = checkedRows.length;

                $('#btn-approve').prop('disabled', checkedCount === 0);

                if (checkedCount === 1) {
                    var pengajuanId = checkedRows.val();
                    var baseUrl = "{{ url('keuangan/cash-advance/pengajuan') }}";
                    var url = `${baseUrl}/${pengajuanId}/proses`;
                    $('#btn-proses').attr('href', url).show();
                } else {
                    $('#btn-proses').hide();
                }
            }

            $('#select_all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input.row-checkbox', rows).prop('checked', this.checked);
                checkCheckboxes();
            });

            $('#dt-basic-example tbody').on('change', 'input.row-checkbox', function() {
                if (!this.checked) {
                    $('#select_all').prop('checked', false);
                }
                checkCheckboxes();
            });

            // 3. LOGIKA UNTUK MODAL APPROVAL
            $('#btn-approve').on('click', function() {
                var ids = [];
                $('.row-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    $('#approval_ids').val(JSON.stringify(ids));
                    $('#approvalModal').modal('show');
                } else {
                    toastr.warning('Silakan pilih data yang akan disetujui terlebih dahulu.');
                }
            });

            $('#approvalForm').on('submit', function(e) {
                e.preventDefault();
                var btn = $('#btn-submit-approval');
                var spinner = btn.find('.spinner-border');
                btn.prop('disabled', true);
                spinner.show();
                $('#modal-alert').hide();

                $.ajax({
                    url: "{{ route('keuangan.cash-advance.pengajuan.approveBulk') }}",
                    method: 'POST',
                    data: {
                        ids: JSON.parse($('#approval_ids').val()),
                        otorisasi_id: $('#otorisasi_id').val(),
                        password: $('#password').val(),
                        catatan: $('#catatan').val(),
                    },
                    success: function(response) {
                        $('#approvalModal').modal('hide');
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                        $('#modal-alert').text(errorMsg).show();
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        spinner.hide();
                    }
                });
            });

            $('#approvalModal').on('hidden.bs.modal', function() {
                $('#approvalForm')[0].reset();
                $('#modal-alert').hide();
                $('.select2-modal').trigger('change');
            });
        });
    </script>
@endsection
