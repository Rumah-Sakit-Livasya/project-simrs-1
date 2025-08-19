@extends('inc.layout')
@section('title', 'Pengajuan')
@section('content')
    <style>
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

        .modal-lg {
            max-width: 800px;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        /* PENTING: Tambahkan CSS ini jika belum ada untuk memastikan toggle berfungsi */
        .child-row {
            display: none;
            /* Sembunyikan secara default */
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        /* Styling tambahan untuk memperjelas batas row */
        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        /* Pastikan table di dalam child row memiliki margin dan padding yang tepat */
        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        /* Pastikan parent dan child row terhubung secara visual */
        tr.parent-row.active {
            border-bottom: none !important;
        }

        /* Tambahkan di bagian style */
        .control-details {
            cursor: pointer;
            text-align: center;
            width: 30px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
            /* Warna biru */
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
            /* Warna merah saat terbuka */
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
            /* Warna biru lebih gelap saat hover */
        }

        /* Sembunyikan ikon sort bawaan DataTables */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        /* Styling untuk child row */
        /* Pastikan content di child row tidak overflow */
        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

        /* Styling untuk tabel di dalam child row */
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

        /* Animasi untuk transisi smooth */
        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Warna berbeda untuk child row */
        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Proses Pengajuan <span class="fw-300"><i>{{ $pengajuan->kode_pengajuan }}</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- ... Form data pengajuan yang disabled ... --}}
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d F Y') }}">
                            </div>
                            <div class="form-group">
                                <label>Nama Pengaju</label>
                                <input type="text" class="form-control" value="{{ $pengajuan->pengaju->name ?? 'N/A' }}">
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" rows="3">{{ $pengajuan->keterangan }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Nominal Diajukan</label>
                                <input type="text" class="form-control"
                                    value="{{ 'Rp ' . number_format($pengajuan->total_nominal_pengajuan, 0, ',', '.') }}">
                            </div>
                        </div>

                        {{-- Panel Aksi --}}
                        <div
                            class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                            <a href="{{ route('keuangan.cash-advance.pengajuan') }}" class="btn btn-secondary">Kembali</a>

                            <div class="ml-auto">
                                {{-- Form Hapus (tidak berubah) --}}
                                {{-- Form Hapus --}}
                                <form action="{{ route('keuangan.cash-advance.pengajuan.destroy', $pengajuan->id) }}"
                                    method="POST" class="d-inline" id="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-delete">
                                        <i class="fal fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>

                                {{-- Form Reject --}}
                                <button type="button" class="btn btn-warning" id="btn-reject-detail">
                                    <i class="fal fa-times-circle mr-1"></i> Reject
                                </button>
                                {{-- =============================================== --}}
                                {{-- UBAH TOMBOL APPROVE --}}
                                {{-- =============================================== --}}
                                <button type="button" class="btn btn-success" id="btn-approve-detail">
                                    <i class="fal fa-check-circle mr-1"></i> Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ================================================================= --}}
    {{-- TAMBAHKAN KEMBALI HTML MODAL OTORISASI --}}
    {{-- ================================================================= --}}
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
                        {{-- ID pengajuan akan diisi oleh JS --}}
                        <input type="hidden" name="ids" id="approval_ids">

                        <div class="form-group">
                            <label for="otorisasi_id">User Otorisasi <span class="text-danger">*</span></label>
                            <select class="form-control select2-modal" id="otorisasi_id" name="otorisasi_id" required
                                style="width: 100%;">
                                @if (isset($userOtorisasi))
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
                            <label for="catatan">Catatan</label>
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

    <div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionModalLabel">Form Otorisasi Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="rejectionForm" action="{{ route('keuangan.cash-advance.pengajuan.reject', $pengajuan->id) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="modal-reject-alert" class="alert alert-danger" style="display: none;"></div>

                        <div class="form-group">
                            <label for="rejection_reason">Alasan Penolakan (Opsional)</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                placeholder="Ketik alasan penolakan di sini..."></textarea>
                        </div>
                        <hr>
                        <p class="text-muted">Masukkan kredensial Anda untuk melanjutkan.</p>
                        <div class="form-group">
                            <label for="reject_otorisasi_id">User Otorisasi <span class="text-danger">*</span></label>
                            <select class="form-control select2-modal-reject" id="reject_otorisasi_id"
                                name="otorisasi_id" required style="width: 100%;">
                                @if (isset($userOtorisasi))
                                    <option value="{{ $userOtorisasi->id }}">{{ $userOtorisasi->name }}</option>
                                @else
                                    <option value="">User Otorisasi tidak ditemukan</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reject_password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="reject_password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning" id="btn-submit-rejection">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                style="display: none;"></span>
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        // Delete button handler
        $(document).on('submit', '#form-delete', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengajuan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if confirmed
                    e.target.submit();
                }
            });
        });


        $('#btn-reject-detail').on('click', function() {
            $('#rejectionModal').modal('show');
        });

        $('.select2-modal-reject').select2({
            dropdownParent: $('#rejectionModal')
        });

        // Handler untuk submit form reject via AJAX
        $('#rejectionForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let btn = $('#btn-submit-rejection');
            let spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.show();
            $('#modal-reject-alert').hide();

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(), // Kirim data form
                success: function(response) {
                    $('#rejectionModal').modal('hide');
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href =
                            "{{ route('keuangan.cash-advance.pengajuan') }}";
                    });
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                        .message : 'Terjadi kesalahan.';
                    $('#modal-reject-alert').text(errorMsg).show();
                },
                complete: function() {
                    btn.prop('disabled', false);
                    spinner.hide();
                }
            });
        });



        // Reject button handler
        $(document).on('submit', '#form-reject', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Penolakan',
                html: `
            <p>Apakah Anda yakin ingin menolak pengajuan ini?</p>
            <div class="form-group">
                <label for="reject-reason">Alasan Penolakan</label>
                <textarea id="reject-reason" class="form-control" rows="3" required></textarea>
            </div>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return {
                        reason: $('#reject-reason').val()
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add the reason to the form and submit
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'reason',
                        value: result.value.reason
                    }).appendTo('#form-reject');

                    e.target.submit();
                }
            });
        });
        $(document).ready(function() {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2 untuk modal
            $('.select2-modal').select2({
                dropdownParent: $('#approvalModal')
            });

            // Logika untuk tombol Hapus dan Tolak (tidak berubah)


            // ===============================================
            // || LOGIKA BARU UNTUK TOMBOL APPROVE DI HALAMAN DETAIL ||
            // ===============================================
            $('#btn-approve-detail').on('click', function() {
                // Ambil ID pengajuan dari data model yang dikirim controller
                let pengajuanId = {{ $pengajuan->id }};

                // Set ID ini ke input hidden di dalam modal
                // Kita bungkus dalam array agar sesuai dengan format yang diharapkan oleh approveBulk
                $('#approval_ids').val(JSON.stringify([pengajuanId]));

                // Tampilkan modal
                $('#approvalModal').modal('show');
            });

            // Event handler untuk form approval di modal (SAMA SEPERTI DI INDEX)
            $('#approvalForm').on('submit', function(e) {
                e.preventDefault();
                var btn = $('#btn-submit-approval');
                var spinner = btn.find('.spinner-border');

                btn.prop('disabled', true);
                spinner.show();
                $('#modal-alert').hide();

                $.ajax({
                    // Menggunakan route yang sama dengan di halaman index
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
                        // Redirect ke halaman index dengan pesan sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href =
                                "{{ route('keuangan.cash-advance.pengajuan') }}";
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

            // Reset form ketika modal ditutup
            $('#approvalModal').on('hidden.bs.modal', function() {
                $('#approvalForm')[0].reset();
                $('#modal-alert').hide();
            });

        });
    </script>
@endsection
