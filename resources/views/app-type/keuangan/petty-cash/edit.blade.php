@extends('inc.layout')

@section('title', 'Edit Petty Cash - ' . $pettycash->kode_transaksi)

@section('content')
    <style>
        .status-icon {
            cursor: pointer;
        }

        .status-icon.grey {
            color: #999;
        }

        .status-icon.green {
            color: #00a65a;
        }

        #modalValidationErrorMessagesInsideModal {
            margin-top: 15px;
        }

        #modalValidationErrorMessagesInsideModal ul {
            padding-left: 20px;
            margin-bottom: 0;
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

        .child-row {
            display: none;
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        tr.parent-row.active {
            border-bottom: none !important;
        }

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
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12px;
            background-color: white;
        }

        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }

        /* Tambahan styling untuk form */
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .table-detail th {
            color: white;
        }

        .total-section {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        /* Custom styling untuk icon tambah */
        .btn-add-icon {
            width: 38px;
            height: 38px;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .btn-add-icon:hover {
            background-color: #0056b3;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
        }

        .btn-add-icon:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .btn-add-icon i {
            font-size: 16px;
            font-weight: bold;
        }

        .select-with-icon {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .select-with-icon .form-control {
            flex: 1;
        }

        .btn-add-icon {
            position: absolute;
            right: 0;
            z-index: 10;
        }

        .form-control-plaintext {
            font-weight: bold;
            font-size: 1.1rem;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <form action="{{ route('keuangan.petty-cash.update', $pettycash->id) }}" method="POST" id="create-petty-cash-form">
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
                <!-- Panel Form Header -->
                <div class="col-lg-10">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Edit Petty Cash</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <p><strong>Oops! Terjadi kesalahan.</strong></p>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="tanggal_ap">Tanggal Transaksi <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="tanggal_ap"
                                                    value="{{ old('tanggal_ap', \Carbon\Carbon::parse($pettycash->tanggal)->format('d-m-Y')) }}"
                                                    required autocomplete="off">
                                                <div class="input-group-append"><span class="input-group-text"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="kas_id">Kas / Bank <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control select2" id="kas_id" name="kas_id" required>
                                                <option value="" disabled>Pilih Kas/Bank...</option>
                                                @foreach ($kass as $kas)
                                                    <option value="{{ $kas->id }}"
                                                        {{ old('kas_id', $pettycash->kas_id) == $kas->id ? 'selected' : '' }}>
                                                        {{ $kas->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="saldo">Saldo Saat Ini</label>
                                            <input type="text" id="saldo" name="saldo"
                                                class="form-control form-control-plaintext bg-light" value="Rp 0"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="transaksi_coa_select">
                                                Pilih Akun Biaya <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="transaksi_coa_select">
                                                    <option value="">Pilih Akun...</option>
                                                    @foreach ($coas as $coa)
                                                        <option value="{{ $coa->id }}"
                                                            data-coa-name="{{ $coa->name }}">
                                                            {{ $coa->code }} - {{ $coa->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-primary btn-add-icon" type="button"
                                                    id="btn-add-transaction" title="Tambah ke Rincian">
                                                    <i class="fal fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="keterangan">Keterangan Umum</label>
                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                                                placeholder="Masukkan keterangan umum transaksi...">{{ old('keterangan', $pettycash->keterangan) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Tabel Detail -->
                <div class="col-lg-12 mt-4">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Detail Rincian Transaksi</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-detail">
                                        <thead class="bg-primary-600">
                                            <tr class="text-center">
                                                <th style="width: 5%;">Aksi</th>
                                                <th style="width: 25%;">Nama Akun</th>
                                                <th style="width: 25%;">Keterangan</th>
                                                <th style="width: 25%;">Cost Center</th>
                                                <th style="width: 20%;">Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody id="transaction-details">
                                            @if ($details->isEmpty())
                                                <tr id="placeholder-row">
                                                    <td colspan="5" class="text-center text-muted">Belum ada rincian
                                                        ditambahkan.</td>
                                                </tr>
                                            @else
                                                @foreach ($details as $index => $detail)
                                                    <tr data-row-id="{{ $index }}">
                                                        <td class="text-center align-middle">
                                                            <button type="button"
                                                                class="btn btn-xs btn-danger btn-remove-row"
                                                                title="Hapus Baris"><i class="fal fa-times"></i></button>
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="hidden"
                                                                name="details[{{ $index }}][coa_id]"
                                                                value="{{ $detail->coa_id }}">
                                                            <input type="text"
                                                                class="form-control form-control-sm bg-light"
                                                                value="{{ $coas->firstWhere('id', $detail->coa_id)->name ?? '' }}"
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="details[{{ $index }}][keterangan]"
                                                                class="form-control form-control-sm"
                                                                value="{{ $detail->keterangan }}"
                                                                placeholder="Keterangan spesifik...">
                                                        </td>
                                                        <td>
                                                            <select class="form-control form-control-sm select2-detail"
                                                                name="details[{{ $index }}][cost_center_id]"
                                                                required>
                                                                <option value="">Pilih Cost Center</option>
                                                                @foreach ($costCenters as $cc)
                                                                    <option value="{{ $cc->id }}"
                                                                        {{ $detail->cost_center_id == $cc->id ? 'selected' : '' }}>
                                                                        {{ $cc->nama_rnc }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="details[{{ $index }}][nominal]"
                                                                class="form-control form-control-sm text-right nominal-input"
                                                                value="{{ number_format($detail->nominal, 0, ',', '.') }}"
                                                                required>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-3">
                                    <a href="{{ route('keuangan.petty-cash.index') }}"
                                        class="btn btn-secondary">Kembali</a>
                                    <div class="ml-auto">
                                        <button type="submit" class="btn btn-primary"><i class="fal fa-save"></i> Simpan
                                            Perubahan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi Plugin
            $('.select2').select2({
                placeholder: "Pilih..."
            });
            $('.select2-detail').select2();
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
            toastr.options.positionClass = 'toast-top-right';

            const costCentersData = @json(
                $costCenters->map(function ($cc) {
                    return ['id' => $cc->id, 'text' => $cc->nama_rnc];
                }));

            let transactionIndex = {{ $details->count() }};

            // 2. Fungsi Helper
            function formatCurrency(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            function parseCurrency(value) {
                return parseFloat(String(value).replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
            }

            // 3. AJAX untuk Saldo Kas/Bank
            $('#kas_id').on('change', function() {
                const kasId = $(this).val();
                const saldoInput = $('#saldo');

                if (kasId) {
                    saldoInput.val('Memuat saldo...');
                    const url = `/keuangan/petty-cash/get-kas-saldo/${kasId}`;

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                saldoInput.val(response.saldo_formatted);
                            } else {
                                saldoInput.val('Saldo tidak ditemukan');
                                toastr.error(response.message || 'Gagal mengambil data saldo.');
                            }
                        },
                        error: function() {
                            saldoInput.val('Gagal memuat saldo');
                            toastr.error('Terjadi kesalahan saat menghubungi server.');
                        }
                    });
                } else {
                    saldoInput.val('Pilih Kas/Bank untuk melihat saldo');
                }
            });

            // Trigger change untuk memuat saldo awal
            $('#kas_id').trigger('change');

            // 4. Tambah Baris Transaksi
            $('#btn-add-transaction').on('click', function() {
                const selectedOption = $('#transaksi_coa_select').find('option:selected');
                const coaId = selectedOption.val();
                if (!coaId) {
                    toastr.warning('Silakan pilih akun biaya terlebih dahulu.');
                    return;
                }
                let isDuplicate = false;
                $('#transaction-details').find('input[name$="[coa_id]"]').each(function() {
                    if ($(this).val() == coaId) {
                        isDuplicate = true;
                    }
                });
                if (isDuplicate) {
                    toastr.error('Akun ini sudah ditambahkan.');
                    return;
                }
                $('#placeholder-row').remove();
                let costCenterOptions = '';
                costCentersData.forEach(function(cc) {
                    costCenterOptions += `<option value="${cc.id}">${cc.text}</option>`;
                });
                const newRow = `
                <tr data-row-id="${transactionIndex}">
                    <td class="text-center align-middle"><button type="button" class="btn btn-xs btn-danger btn-remove-row" title="Hapus Baris"><i class="fal fa-times"></i></button></td>
                    <td class="align-middle">
                        <input type="hidden" name="details[${transactionIndex}][coa_id]" value="${coaId}">
                        <input type="text" class="form-control form-control-sm bg-light" value="${selectedOption.data('coa-name')}" readonly>
                    </td>
                    <td><input type="text" name="details[${transactionIndex}][keterangan]" class="form-control form-control-sm" placeholder="Keterangan spesifik..."></td>
                    <td>
                        <select class="form-control form-control-sm select2-detail" name="details[${transactionIndex}][cost_center_id]" required>
                            <option value="">Pilih Cost Center</option>
                            ${costCenterOptions}
                        </select>
                    </td>
                    <td><input type="text" name="details[${transactionIndex}][nominal]" class="form-control form-control-sm text-right nominal-input" value="0" required></td>
                </tr>`;
                $('#transaction-details').append(newRow);
                $(`tr[data-row-id="${transactionIndex}"] .select2-detail`).select2();
                transactionIndex++;
                $('#transaksi_coa_select').val(null).trigger('change');
            });

            // 5. Hapus Baris
            $('#transaction-details').on('click', '.btn-remove-row', function() {
                $(this).closest('tr').remove();
                if ($('#transaction-details tr').length === 0) {
                    $('#transaction-details').append(
                        '<tr id="placeholder-row"><td colspan="5" class="text-center text-muted">Belum ada rincian ditambahkan.</td></tr>'
                    );
                }
            });

            // 6. Format Input Nominal
            $('#transaction-details').on('keyup', '.nominal-input', function(event) {
                let value = parseCurrency($(this).val());
                $(this).val(formatCurrency(value));
            });

            // 7. Submit Handler
            $('#create-petty-cash-form').on('submit', function(e) {
                if ($('.nominal-input').length === 0) {
                    e.preventDefault();
                    toastr.error('Harap tambahkan minimal satu rincian transaksi.');
                    return;
                }
                $('.nominal-input').each(function() {
                    $(this).val(parseCurrency($(this).val()));
                });
            });
        });
    </script>
@endsection
