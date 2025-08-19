        @extends('inc.layout')
        @section('title', 'Pertanggung Jawaban ')
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

            <!-- Loading overlay div -->
            <div class="loading-overlay">
                <div class="loading-spinner">
                    <i class="fa fa-spinner fa-spin"></i> Memuat...
                </div>
            </div>
            <main id="js-page-content" role="main" class="page-content">
                <form id="form-pj" method="POST" action="{{ route('keuangan.cash-advance.pertanggung-jawaban.store') }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <div class="panel">
                                <div class="panel-hdr">
                                    <h2>Form <span class="fw-300"><i>Pertanggung Jawaban</i></span></h2>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label for="tanggal_pj">Tanggal Pertanggung Jawaban<span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control datepicker"
                                                            id="tanggal_pj" name="tanggal_pj" placeholder="Pilih Tanggal"
                                                            value="{{ old('tanggal_pj', date('Y-m-d')) }}" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text fs-sm"><i
                                                                    class="fal fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label>Kode Pencairan <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="kode_pencairan_text"
                                                            placeholder="Klik ikon search...">
                                                        <input type="hidden" name="pencairan_id" id="pencairan_id"
                                                            value="{{ old('pencairan_id') }}" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text btn-search-popup"
                                                                style="cursor: pointer;">
                                                                <i class="fal fa-search"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label>Total Pertanggung Jawaban</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control text-right" id="Total_pj"
                                                            value="{{ old('total_pj', 0) }}" style="font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label>Nama Pengaju</label>
                                                    <input type="text" class="form-control" id="nama_pengaju">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label>Sisa Pertanggung Jawaban</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control text-right" id="sisa_pj"
                                                            value="{{ old('sisa_pj', 0) }}" style="font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label>Nominal Pencairan</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control text-right"
                                                            id="nominal_pencairan" value="{{ old('nominal_pencairan', 0) }}"
                                                            style="font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 mt-4">
                            <div class="panel">
                                <div class="panel-hdr">
                                    <h2>Detail <span class="fw-300"><i>Pertanggung Jawaban</i></span></h2>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        <table id="detail-table" class="table table-bordered table-sm">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="25%">Tipe Transaksi</th>
                                                    <th width="30%">Keterangan</th>
                                                    <th width="20%">Cost Center</th>
                                                    <th width="20%" class="text-right">Nominal</th>
                                                    <th width="5%" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detail-tbody"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                                                    <td class="text-right font-weight-bold" id="total-nominal">Rp 0</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="panel-toolbar">
                                            <button type="button" id="btn-add-row" class="btn btn-sm btn-primary">
                                                <i class="fal fa-plus"></i> Tambah Baris
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-hdr">
                                    <h2>Detail <span class="fw-300"><i>Reimburse</i></span></h2>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        <table id="reimburse-table" class="table table-bordered table-sm">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="25%">Tipe Transaksi</th>
                                                    <th width="30%">Keterangan</th>
                                                    <th width="20%">Cost Center</th>
                                                    <th width="20%" class="text-right">Nominal</th>
                                                    <th width="5%" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="reimburse-tbody"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                                                    <td class="text-right font-weight-bold" id="total-reimburse">Rp 0</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="panel-toolbar">
                                            <button type="button" id="btn-add-reimburse" class="btn btn-sm btn-primary">
                                                <i class="fal fa-plus"></i> Tambah Baris
                                            </button>
                                        </div>
                                    </div>
                                    <div class="panel-content d-flex justify-content-end">
                                        <a href="{{ route('keuangan.cash-advance.pertanggung-jawaban') }}"
                                            class="btn btn-secondary mr-2">Batal</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fal fa-save"></i> Simpan
                                        </button>
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
            <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>

            <script>
                $(document).ready(function() {
                    // Initialize datepicker
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        todayHighlight: true
                    });

                    // Initialize input mask for money display
                    $('.money-display').inputmask({
                        alias: 'numeric',
                        groupSeparator: '.',
                        autoGroup: true,
                        digits: 0,
                        prefix: 'Rp ',
                        rightAlign: false
                    });

                    // Open popup window
                    $('.btn-search-popup').on('click', function() {
                        window.open(
                            "{{ route('keuangan.cash-advance.pertanggung-jawaban.dataPencairanPopup') }}",
                            'PilihPencairan',
                            'width=1200,height=700,scrollbars=yes'
                        );
                    });

                    // Function to be called from popup
                    window.selectPencairan = function(id, kode, nama, nominalCair, totalPj) {
                        $('#pencairan_id').val(id);
                        $('#kode_pencairan_text').val(kode);
                        $('#nama_pengaju').val(nama);

                        // Format and display values
                        $('#nominal_pencairan').val(formatRupiah(nominalCair));
                        $('#Total_pj').val(formatRupiah(totalPj));

                        // Calculate and display remaining amount
                        const sisa = nominalCair - totalPj;
                        $('#sisa_pj').val(formatRupiah(sisa));

                        // Enable/disable sections based on remaining amount
                        if (sisa <= 0) {
                            $('#btn-add-row').prop('disabled', true);
                            alert('Pencairan ini sudah sepenuhnya dipertanggungjawabkan');
                        } else {
                            $('#btn-add-row').prop('disabled', false);
                        }
                    };

                    // Format number to Rupiah (original format)
                    function formatRupiah(number) {
                        if (!number) return 'Rp 0';
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                    }

                    // Unformat Rupiah to number
                    function unformatRupiah(rupiah) {
                        if (!rupiah) return 0;
                        return parseInt(rupiah.toString().replace(/[^0-9]/g, '')) || 0;
                    }

                    // Data options from controller
                    const tipeTransaksiOptions = {!! json_encode($tipe_transaksis) !!};
                    const costCenterOptions = {!! json_encode($cost_centers) !!};

                    // Build select options from array of objects
                    function buildOptions(optionsArray, valueField, textField) {
                        let options = '<option value="">Pilih...</option>';
                        options += optionsArray.map(opt =>
                            `<option value="${opt[valueField]}">${opt[textField]}</option>`
                        ).join('');
                        return options;
                    }

                    // Add row to table
                    function addRow(tbodyId, namePrefix) {
                        const tableBody = $('#' + tbodyId);
                        const rowCount = tableBody.find('tr').length;

                        const newRow = `
            <tr>
                <td>${rowCount + 1}</td>
                <td>
                    <select name="${namePrefix}[${rowCount}][transaksi_rutin_id]" class="form-control select2-repeater" required>
                        ${buildOptions(tipeTransaksiOptions, 'id', 'nama_transaksi')}
                    </select>
                </td>
                <td>
                    <input type="text" name="${namePrefix}[${rowCount}][keterangan]" class="form-control" required>
                </td>
                <td>
                    <select name="${namePrefix}[${rowCount}][rnc_center_id]" class="form-control select2-repeater" required>
                        ${buildOptions(costCenterOptions, 'id', 'nama_rnc')}
                    </select>
                </td>
                <td>
                    <input type="text" name="${namePrefix}[${rowCount}][nominal]" 
                           class="form-control nominal-input text-right" 
                           required
                           value="0"
                           data-inputmask="'alias': 'numeric', 'groupSeparator': '.', 'autoGroup': true, 'digits': 0, 'removeMaskOnSubmit': true">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-danger btn-remove-row">
                        <i class="fal fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

                        tableBody.append(newRow);

                        // Initialize select2 for new row
                        tableBody.find('.select2-repeater').select2({
                            width: '100%',
                            placeholder: 'Pilih...'
                        });

                        // Initialize inputmask for new row
                        tableBody.find('.nominal-input').inputmask({
                            alias: 'numeric',
                            groupSeparator: '.',
                            autoGroup: true,
                            digits: 0,
                            removeMaskOnSubmit: true,
                            placeholder: '0',
                            rightAlign: false,
                            onBeforeMask: function(value) {
                                return value.replace(/[^0-9]/g, '');
                            }
                        });
                    }

                    // Add row buttons
                    $('#btn-add-row').on('click', () => addRow('detail-tbody', 'details'));
                    $('#btn-add-reimburse').on('click', () => addRow('reimburse-tbody', 'reimburse_details'));

                    // Remove row
                    $(document).on('click', '.btn-remove-row', function() {
                        const tbody = $(this).closest('tbody');
                        $(this).closest('tr').remove();
                        calculateTotals();
                        renumberRows(tbody);
                    });

                    // Renumber rows
                    function renumberRows(tbody) {
                        tbody.find('tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    }

                    // Calculate totals
                    function calculateTotals() {
                        // Get remaining amount
                        const sisa = unformatRupiah($('#sisa_pj').val());

                        // Calculate PJ total
                        let totalDetail = 0;
                        $('#detail-tbody .nominal-input').each(function() {
                            totalDetail += unformatRupiah($(this).val());
                        });

                        // Validate PJ doesn't exceed remaining amount
                        if (totalDetail > sisa) {
                            alert('Total pertanggungjawaban tidak boleh melebihi sisa yang belum dipertanggungjawabkan');
                            $('#btn-add-row').prop('disabled', true);
                        } else {
                            $('#btn-add-row').prop('disabled', false);
                        }

                        $('#total-nominal').text(formatRupiah(totalDetail));

                        // Calculate Reimburse total
                        let totalReimburse = 0;
                        $('#reimburse-tbody .nominal-input').each(function() {
                            totalReimburse += unformatRupiah($(this).val());
                        });
                        $('#total-reimburse').text(formatRupiah(totalReimburse));
                    }

                    // Recalculate when nominal inputs change
                    $(document).on('input', '.nominal-input', function() {
                        // Format the input value
                        const value = unformatRupiah($(this).val());
                        $(this).val(formatRupiah(value));
                        calculateTotals();
                    });

                    // Form submission handling
                    $('#form-pj').on('submit', function(e) {
                        // Unmask all nominal values before submission
                        $('.nominal-input').each(function() {
                            const value = unformatRupiah($(this).val());
                            $(this).val(value);
                        });

                        return true; // Continue with form submission
                    });

                    // Add initial row if empty
                    if ($('#detail-tbody tr').length === 0) {
                        $('#btn-add-row').click();
                    }

                    // Initialize first select2 elements
                    $('.select2-repeater').select2({
                        width: '100%',
                        placeholder: 'Pilih...'
                    });
                });
            </script>
        @endsection
