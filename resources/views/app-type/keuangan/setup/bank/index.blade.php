@extends('inc.layout')
@section('title', 'Bank')
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
        <div class="row mb-3">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-bank" title="Tambah Bank">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Bank
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tabel <span class="fw-300"><i>Bank</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                {{-- ============================================= --}}
                                {{--            PERBAIKAN UTAMA DI SINI            --}}
                                {{-- ============================================= --}}
                                <table class="table table-striped table-bordered" id="dt-basic-example">
                                    {{-- ID DITAMBAHKAN --}}
                                    <thead>
                                        <tr>
                                            <th style="width: 15px">No</th>
                                            <th>Nama Bank</th>
                                            <th>Pemilik</th>
                                            <th>Nomor Rekening</th>
                                            <th>Saldo</th>
                                            <th>Status</th>
                                            <th class="no-export text-center" style="width: 80px">Aksi</th>
                                            {{-- class no-export & text-center --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($banks as $bank)
                                            <tr id="bank-row-{{ $bank->id }}"> {{-- Tambah ID untuk update via AJAX --}}
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $bank->nama }}</td>
                                                <td>{{ $bank->pemilik }}</td>
                                                <td>{{ $bank->nomor }}</td>
                                                <td>{{ number_format($bank->saldo, 2, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $bank->is_aktivasi ? 'success' : 'secondary' }}">
                                                        {{ $bank->is_aktivasi ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{-- Tombol diubah untuk menggunakan data-* attributes --}}
                                                    <button type="button" class="btn btn-xs btn-primary btn-edit"
                                                        data-id="{{ $bank->id }}" title="Edit">
                                                        <i class="fal fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-danger btn-delete"
                                                        data-id="{{ $bank->id }}" data-name="{{ $bank->nama }}"
                                                        title="Hapus">
                                                        <i class="fal fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data bank yang ditemukan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{--     MODAL SEKARANG DITARUH DI LUAR LOOP, HANYA SATU KALI    --}}
        {{-- ========================================================== --}}
        @include('app-type.keuangan.setup.bank.partials.create-bank')
        @include('app-type.keuangan.setup.bank.partials.edit') {{-- Ini modal edit generik --}}
        @include('app-type.keuangan.setup.bank.partials.delete-bank') {{-- Ini modal hapus generik --}}
    </main>
@endsection

@section('plugin')
    {{-- JavaScript section with major updates --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF Token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2 untuk modal TAMBAH
            $('#tambah-bank .select2').select2({
                placeholder: 'Pilih Opsi',
                dropdownParent: $('#tambah-bank')
            });

            // Inisialisasi Select2 untuk modal EDIT
            $('#edit-bank-modal .select2').select2({
                placeholder: 'Pilih Opsi',
                dropdownParent: $('#edit-bank-modal')
            });

            // Inisialisasi DataTable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm btn-default'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm btn-default'
                    }
                ]
            });

            // EVENT: Klik tombol EDIT
            $('#dt-basic-example tbody').on('click', '.btn-edit', function() {
                var bankId = $(this).data('id');
                var url = "{{ url('keuangan/setup/bank') }}/" + bankId;

                // Ambil data dari server
                $.get(url, function(data) {
                    // Isi form di modal edit
                    $('#edit-bank-form').attr('action', url); // Set action form
                    $('#edit-bank-modal #nama').val(data.nama);
                    $('#edit-bank-modal #pemilik').val(data.pemilik);
                    $('#edit-bank-modal #nomor').val(data.nomor);
                    $('#edit-bank-modal #saldo').val(parseFloat(data.saldo));
                    $('#edit-bank-modal #akun_kas_bank').val(data.akun_kas_bank).trigger('change');
                    $('#edit-bank-modal #akun_kliring').val(data.akun_kliring).trigger('change');
                    $('#edit-bank-modal #is_aktivasi').prop('checked', data.is_aktivasi);
                    $('#edit-bank-modal #is_bank').prop('checked', data.is_bank);

                    // Tampilkan modal
                    $('#edit-bank-modal').modal('show');
                }).fail(function() {
                    alert('Gagal mengambil data bank.');
                });
            });

            // EVENT: Submit form EDIT
            $('#edit-bank-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: "PUT", // Gunakan method PUT/PATCH untuk update
                    url: url,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#edit-bank-modal').modal('hide');
                        alert(response.message);
                        // Reload halaman untuk melihat perubahan
                        // Opsi lebih canggih: update baris tabel dengan DataTables API tanpa reload
                        window.location.reload();
                    },
                    error: function(xhr) {
                        // Tampilkan error validasi
                        var errors = xhr.responseJSON.errors;
                        var errorString = 'Terdapat kesalahan:\n';
                        $.each(errors, function(key, value) {
                            errorString += '- ' + value[0] + '\n';
                        });
                        alert(errorString);
                    }
                });
            });

            // EVENT: Klik tombol DELETE
            $('#dt-basic-example tbody').on('click', '.btn-delete', function() {
                var bankId = $(this).data('id');
                var bankName = $(this).data('name');
                var url = "{{ url('keuangan/setup/bank') }}/" + bankId;

                // Set content modal delete
                $('#delete-bank-modal #bank-name').text(bankName);
                $('#delete-bank-form').attr('action', url);

                // Tampilkan modal
                $('#delete-bank-modal').modal('show');
            });
        });
    </script>
@endsection
