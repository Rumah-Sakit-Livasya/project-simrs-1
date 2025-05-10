@extends('inc.layout')
@section('title', 'Tambah Konfirmasi Asuransi')
@section('content')
    <style>
        .form-control {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #eaeaea;
        }

        .select2-selection {
            border: 0 !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
        }

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

        /* Loading overlay style */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            color: white;
            font-size: 2rem;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian Konfirmasi Asuransi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="search-form" action="{{ route('keuangan.konfirmasi-asuransi.index') }}"
                                method="get">
                                @csrf

                                <!-- Baris Pertama: Periode, Status, Penjamin -->
                                <div class="row mb-4">
                                    <!-- Tanggal Periode (Dari - Sampai) -->
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="form-group row">
                                            <label for="tanggal_awal" class="col-md-3 col-form-label text-md-right">Periode
                                                Awal</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="tanggal_awal"
                                                        name="tanggal_awal" placeholder="Pilih tanggal awal"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="form-group row">
                                            <label for="tanggal_akhir" class="col-md-3 col-form-label text-md-right">Periode
                                                Akhir</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="tanggal_akhir"
                                                        name="tanggal_akhir" placeholder="Pilih tanggal akhir"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Tagihan -->
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="form-group row align-items-center">
                                            <label for="status" class="col-md-5 col-form-label text-md-right">
                                                Status Tagihan
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control select2" id="status" name="status">
                                                    <option value="">Semua</option>
                                                    <option value="Belum Di Buat Tagihan"
                                                        {{ request('status') == 'Belum Di Buat Tagihan' ? 'selected' : '' }}>
                                                        Belum Di buat Tagihan
                                                    </option>
                                                    <option value="Sudah Di Buat Tagihan"
                                                        {{ request('status') == 'Sudah Di Buat Tagihan' ? 'selected' : '' }}>
                                                        Sudah Di Buat Tagihan
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris Kedua: Tagihan Ke, Penjamin, Tipe Pasien -->
                                <div class="row mb-4">
                                    <!-- Tagihan Ke -->
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="form-group row align-items-center">
                                            <label for="tagihan_ke" class="col-md-4 col-form-label text-md-right">
                                                Tagihan Ke
                                            </label>
                                            <div class="col-md-8">
                                                <select class="form-control w-100 select2" id="tagihan_ke"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="tagihan_ke">
                                                    <option value="">Semua</option>
                                                    @foreach ($penjamins as $penjamin)
                                                        <option value="{{ $penjamin->id }}"
                                                            {{ request('tagihan_ke') == $penjamin->id ? 'selected' : '' }}>
                                                            {{ $penjamin->nama_perusahaan }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tagihan_ke')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Penjamin -->
                                    <div class="col-md-4">
                                        <div class="form-group row align-items-center">
                                            <label for="penjamin_id" class="col-md-5 col-form-label text-md-right">
                                                Penjamin <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control select2" id="penjamin_id" name="penjamin_id"
                                                    required>
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
                                    </div>

                                    <!-- Tipe Pasien -->
                                    <div class="col-md-4">
                                        <div class="form-group row align-items-center">
                                            <label for="tipe_pasien" class="col-md-5 col-form-label text-md-right">
                                                Tipe Pasien
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control select2" id="tipe_pasien" name="tipe_pasien">
                                                    <option value="">Semua</option>
                                                    <option value="rawat inap"
                                                        {{ request('tipe_pasien') == 'rawat inap' ? 'selected' : '' }}>
                                                        Rawat Inap
                                                    </option>
                                                    <option value="rawat jalan"
                                                        {{ request('tipe_pasien') == 'rawat jalan' ? 'selected' : '' }}>
                                                        Rawat Jalan
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Cari dan Reset -->
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" id="btn-reset"
                                            class="btn btn-secondary waves-effect waves-themed mr-2">
                                            <span class="fal fa-undo mr-2"></span>
                                            Reset
                                        </button>
                                        <button type="button" id="btn-cari"
                                            class="btn btn-primary waves-effect waves-themed">
                                            <span class="fal fa-search mr-2"></span>
                                            Cari Data
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
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Konfirmasi Asuransi</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Datatable Start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Bill</th>
                                        <th>No Reg</th>
                                        <th>Tgl Keluar</th>
                                        <th>Total Tagihan</th>
                                        <th>Diskon</th>
                                        <th>Aksi</th>
                                        <th><input type="checkbox" id="select-all"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($query as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->no_rm }}</td>
                                            <td>{{ $item->patient->nama ?? '-' }}</td>
                                            <td>{{ $item->bill }}</td>
                                            <td>{{ $item->no_reg }}</td>
                                            <td>{{ $item->tanggal_keluar ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="text-right">Rp
                                                {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                                            <td class="text-right">Rp {{ number_format($item->diskon, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('keuangan.konfirmasi-asuransi.edit', $item->id) }}"
                                                    class="btn btn-icon btn-sm btn-info" title="Edit">
                                                    <i class="fal fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-icon btn-sm btn-danger del-btn"
                                                    data-id="{{ $item->id }}" title="Hapus">
                                                    <i class="fal fa-trash"></i>
                                                </button>
                                            </td>
                                            <td><input type="checkbox" class="row-checkbox" name="selected[]"
                                                    value="{{ $item->id }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data tersedia</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-center">
                                            <strong>Total Keseluruhan:</strong>
                                        </th>
                                        <th colspan="4" class="text-right">Rp <span id="total-tagihan">0</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- Datatable End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-3" class="panel">
                    <div class="panel-hdr">
                        <h2>Pengaturan <span class="fw-300"><i>Tagihan Asuransi</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="tagihan-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group row align-items-center">
                                            <label for="jatuh_tempo" class="col-md-5 col-form-label text-md-right">
                                                Jatuh Tempo (Hari)
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control select2" id="jatuh_tempo" name="jatuh_tempo">
                                                    <option value="7">7 hari</option>
                                                    <option value="14">14 hari</option>
                                                    <option value="21">21 hari</option>
                                                    <option value="30" selected>30 hari</option>
                                                    <option value="45">45 hari</option>
                                                    <option value="60">60 hari</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group row align-items-center">
                                            <label for="keterangan" class="col-md-3 col-form-label text-md-right">
                                                Keterangan
                                            </label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="1"
                                                    placeholder="Masukkan keterangan tagihan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10 d-flex justify-content-start">
                                        <button type="button" id="buat-tagihan"
                                            class="btn btn-success waves-effect waves-themed mr-2">
                                            <span class="fal fa-file-invoice mr-2"></span>
                                            Buat Tagihan
                                        </button>
                                        <button type="button" id="cetak-rekap"
                                            class="btn btn-primary waves-effect waves-themed">
                                            <span class="fal fa-print mr-2"></span>
                                            Cetak Rekap
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading overlay div -->
    <div class="loading-overlay">
        <div class="loading-spinner">
            <i class="fal fa-spinner fa-spin"></i> Memproses...
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <link rel="stylesheet" href="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">

@section('plugin')
    <link rel="stylesheet" href="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[Init] DOM Loaded');

            // === DATEPICKER ===
            if ($().datepicker) {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    clearBtn: true,
                    language: 'id',
                    orientation: 'bottom auto',
                    todayBtn: 'linked'
                });

                // Set default tanggal
                if (!$('#tanggal_awal').val()) {
                    $('#tanggal_awal').datepicker('setDate', moment().subtract(7, 'days').toDate());
                }
                if (!$('#tanggal_akhir').val()) {
                    $('#tanggal_akhir').datepicker('setDate', new Date());
                }
            } else {
                console.warn('[datepicker] Plugin not loaded');
            }

            // === SELECT2 ===
            if ($().select2) {
                $('.select2').select2({
                    dropdownCssClass: "move-up",
                    width: '100%'
                });
            } else {
                console.warn('[select2] Plugin not loaded');
            }

            // === DATATABLE ===
            if ($.fn.DataTable) {
                $('#dt-basic-example').DataTable({
                    responsive: true,
                    lengthChange: false,
                    dom: "<'row mb-3'<'col-md-6'f><'col-md-6 text-right'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-md-5'i><'col-md-7'p>>",
                    buttons: [{
                            extend: 'pdfHtml5',
                            className: 'btn-outline-danger btn-sm mr-1',
                            title: 'Daftar Konfirmasi Asuransi',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            className: 'btn-outline-success btn-sm mr-1',
                            title: 'Daftar Konfirmasi Asuransi',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-outline-primary btn-sm',
                            title: 'Daftar Konfirmasi Asuransi',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        }
                    ],
                    language: {
                        emptyTable: "Tidak ada data tersedia"
                    },
                    columnDefs: [{
                        orderable: false,
                        targets: [8, 9]
                    }],
                    drawCallback: function() {
                        $('.loading-overlay').hide();
                        calculateTotals();
                    }
                });
            }

            // === TOTAL TAGIHAN ===
            function calculateTotals() {
                let total = 0;
                $('#dt-basic-example tbody tr').each(function() {
                    const nilai = parseFloat($(this).find('td:eq(6)').text().replace(/[^\d]/g, '')) || 0;
                    total += nilai;
                });
                $('#total-tagihan').text(total.toLocaleString('id-ID'));
            }

            // === RESET FORM ===
            $('#btn-reset').on('click', function(e) {
                e.preventDefault();
                $('#tanggal_awal').datepicker('setDate', moment().subtract(7, 'days').toDate());
                $('#tanggal_akhir').datepicker('setDate', new Date());
                $('#tagihan_ke, #penjamin_id, #status, #tipe_pasien').val('').trigger('change');
                console.log('[Reset] Filter direset');
            });

            // === FILTER DATA ===
            $('#btn-cari').on('click', function(e) {
                e.preventDefault();

                const params = {
                    tanggal_awal: $('#tanggal_awal').val(),
                    tanggal_akhir: $('#tanggal_akhir').val(),
                    penjamin_id: $('#penjamin_id').val(),
                    tagihan_ke: $('#tagihan_ke').val(),
                    status: $('#status').val(),
                    tipe_pasien: $('#tipe_pasien').val()
                };

                if (!params.penjamin_id) {
                    toastr.error('Penjamin wajib dipilih');
                    return;
                }

                $('.loading-overlay').show();
                $.get('{{ route('keuangan.konfirmasi-asuransi.search-tambah') }}', params, function(data) {
                    updateTable(data);
                    $('.loading-overlay').hide();
                }).fail(function() {
                    $('.loading-overlay').hide();
                    toastr.error('Gagal mengambil data');
                });
            });

            // === UPDATE TABEL SETELAH FILTER ===
            function updateTable(data) {
                const tbody = $('#dt-basic-example tbody');
                tbody.empty();

                if (data.length > 0) {
                    $.each(data, function(i, d) {
                        const tgl = d.tanggal_keluar ? moment(d.tanggal_keluar).format('DD/MM/YYYY') : '-';
                        const total = d.total_tagihan ? parseFloat(d.total_tagihan).toLocaleString(
                            'id-ID') : '0';
                        const diskon = d.diskon ? parseFloat(d.diskon).toLocaleString('id-ID') : '0';

                        tbody.append(`
                            <tr>
                                <td>${i+1}</td>
                                <td>${d.no_rm || '-'}</td>
                                <td>${d.patient?.nama || '-'}</td>
                                <td>${d.bill || '-'}</td>
                                <td>${d.no_reg || '-'}</td>
                                <td>${tgl}</td>
                                <td class="text-right">Rp ${total}</td>
                                <td class="text-right">Rp ${diskon}</td>
                                <td>
                                    <a href="/keuangan/konfirmasi-asuransi/${d.id}/edit" class="btn btn-sm btn-info"><i class="fal fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger del-btn" data-id="${d.id}"><i class="fal fa-trash"></i></button>
                                </td>
                                <td><input type="checkbox" class="row-checkbox" value="${d.id}"></td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`<tr><td colspan="10" class="text-center">Tidak ada data ditemukan</td></tr>`);
                }

                calculateTotals();
            }

            // === BUAT TAGIHAN ===
            $('#buat-tagihan').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    toastr.error('Pilih data terlebih dahulu');
                    return;
                }

                $.ajax({
                    url: '{{ route('keuangan.konfirmasi-asuransi.create-invoice') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        selected_ids: ids,
                        jatuh_tempo: $('#jatuh_tempo').val(),
                        keterangan: $('#keterangan').val()
                    },
                    beforeSend: function() {
                        $('.loading-overlay').show();
                    },
                    success: function(res) {
                        $('.loading-overlay').hide();
                        if (res.success) {
                            toastr.success('Tagihan berhasil dibuat');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            toastr.error(res.message || 'Gagal membuat tagihan');
                        }
                    },
                    error: function() {
                        $('.loading-overlay').hide();
                        toastr.error('Terjadi kesalahan');
                    }
                });
            });

            // === CETAK REKAP ===
            $('#cetak-rekap').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    toastr.error('Pilih data terlebih dahulu');
                    return;
                }

                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route('keuangan.konfirmasi-asuransi.print-recap') }}',
                    target: '_blank'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                }));
                ids.forEach(id => {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'selected_ids[]',
                        value: id
                    }));
                });

                $('body').append(form);
                form.submit();
                form.remove();
            });

            // === SELECT ALL CHECKBOX ===
            $('#select-all').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                highlightRows();
            });

            $(document).on('change', '.row-checkbox', highlightRows);

            function highlightRows() {
                $('tbody tr').each(function() {
                    $(this).toggleClass('table-active', $(this).find('.row-checkbox').is(':checked'));
                });
            }

            // === DELETE BUTTON ===
            $(document).on('click', '.del-btn', function() {
                const id = $(this).data('id');
                if (!confirm('Yakin ingin menghapus data ini?')) return;

                $('.loading-overlay').show();
                $.ajax({
                    url: `/keuangan/konfirmasi-asuransi/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('.loading-overlay').hide();
                        if (res.success) location.reload();
                        else toastr.error(res.message || 'Gagal menghapus data');
                    },
                    error: function() {
                        $('.loading-overlay').hide();
                        toastr.error('Gagal menghapus data');
                    }
                });
            });

        });
    </script>
@endsection
