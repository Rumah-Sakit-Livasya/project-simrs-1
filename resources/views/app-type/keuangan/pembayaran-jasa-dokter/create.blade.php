@extends('inc.layout')
@section('title', 'Pembayaran Jasa Dokter')
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
        <!-- Form Panel -->
        <div class="row justify-content-center">
            <div class="col-xl">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pembayaran Jasa Dokter</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.pembayaran-jasa-dokter.store') }}" method="POST">
                                @csrf

                                <!-- Filter dan Data Umum -->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tgl. AP Awal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="tgl_ap_awal"
                                                        placeholder="Pilih tanggal awal"
                                                        value="{{ now()->format('Y-m-d') }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tanggal Pembayaran</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_pembayaran" placeholder="Pilih tanggal pembayaran"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Nama Dokter</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" name="dokter_id">
                                                    <option value="">Pilih Dokter</option>
                                                    @foreach ($dokters as $dokter)
                                                        <option value="{{ $dokter->id }}">
                                                            {{ $dokter->employee->fullname ?? $dokter->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">NPWP</label>
                                            <div class="col-xl-8">
                                                <input type="text" name="npwp" class="form-control"
                                                    placeholder="Masukkan NPWP">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tgl. AP Akhir</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tgl_ap_akhir" placeholder="Pilih tanggal akhir"
                                                        value="{{ now()->format('Y-m-d') }}" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tahun Pajak</label>
                                            <div class="col-xl-8">
                                                <input type="text" name="tahun_pajak" value="{{ now()->year }}"
                                                    class="form-control" placeholder="Tahun Pajak">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Metode Pembayaran</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" name="metode_pembayaran">
                                                    <option value="">Pilih Metode</option>
                                                    <option value="Transfer">Transfer</option>
                                                    <option value="Tunai">Tunai</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Bank & No. Rek</label>
                                            <div class="col-xl-8">
                                                <input type="text" name="bank_rek" class="form-control"
                                                    placeholder="Bank & No. Rekening">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6 mt-4">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Kas/Bank</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" name="kas_bank_id">
                                                    <option value="">Pilih Kas/Bank</option>
                                                    @foreach ($banks as $bank)
                                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 mt-4">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Guarantee Fee</label>
                                            <div class="col-xl-8">
                                                <input type="text" name="guarantee_fee" class="form-control money"
                                                    placeholder="Guarantee Fee">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Jasa Dokter Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Manual <span class="fw-300"><i>Jasa Dokter</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th width="25%">Keterangan</th>
                                            <th width="25%">Akun</th>
                                            <th width="20%">Cost & Revenue</th>
                                            <th width="15%">Jasa Dokter</th>
                                            <th width="10%">JKP Tambahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 5; $i++)
                                            <tr>
                                                <td>
                                                    <input type="text" name="keterangan[]"
                                                        class="form-control form-control-sm"
                                                        placeholder="Masukkan keterangan">
                                                </td>
                                                <td>
                                                    <input type="text" name="akun[]"
                                                        class="form-control form-control-sm" placeholder="Kode akun">
                                                </td>
                                                <td>
                                                    <input type="text" name="cost_revenue[]"
                                                        class="form-control form-control-sm" placeholder="Cost & Revenue">
                                                </td>
                                                <td>
                                                    <input type="number" name="jasa_dokter_manual[]"
                                                        class="form-control form-control-sm jasa-dokter-input"
                                                        value="0" min="0" step="0.01">
                                                </td>
                                                <td>
                                                    <input type="number" name="jkp_tambahan[]"
                                                        class="form-control form-control-sm jkp-tambahan-input"
                                                        value="0" min="0" step="0.01">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot class="total-row">
                                        <tr>
                                            <th colspan="3" class="text-right">Total</th>
                                            <th class="text-right">
                                                <span id="total-jasa-dokter">0</span>
                                            </th>
                                            <th class="text-right">
                                                <span id="total-jkp-tambahan">0</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Non Pajak & Potongan Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-3" class="panel">
                    <div class="panel-hdr">
                        <h2>Penambahan/Pengurangan <span class="fw-300"><i>Non Pajak</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover w-100 mb-4">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th width="30%">Keterangan</th>
                                            <th width="20%">Akun</th>
                                            <th width="25%">Cost & Revenue</th>
                                            <th width="25%">Jasa Dokter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 4; $i++)
                                            <tr>
                                                <td>
                                                    <input type="text" name="non_pajak_keterangan[]"
                                                        class="form-control form-control-sm"
                                                        placeholder="Masukkan keterangan">
                                                </td>
                                                <td>
                                                    <input type="text" name="non_pajak_akun[]"
                                                        class="form-control form-control-sm" placeholder="Kode akun">
                                                </td>
                                                <td>
                                                    <input type="text" name="non_pajak_cost[]"
                                                        class="form-control form-control-sm" placeholder="Cost & Revenue">
                                                </td>
                                                <td>
                                                    <input type="number" name="non_pajak_jasa[]"
                                                        class="form-control form-control-sm non-pajak-input"
                                                        value="0" min="0" step="0.01">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot class="total-row">
                                        <tr>
                                            <th colspan="3" class="text-right">Total</th>
                                            <th class="text-right">
                                                <span id="total-non-pajak">0</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <hr class="my-4">

                            <h6 class="mb-3">Potongan dari tagihan pasien yang dijamin dokter</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Tgl Bill</th>
                                            <th>No Registrasi</th>
                                            <th>Nama Pasien</th>
                                            <th>Tagihan</th>
                                            <th>Nominal Settle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fal fa-info-circle mr-2"></i>
                                                Tidak ada data
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fal fa-arrow-left mr-1"></i> Kembali
                                </button>
                                <div>
                                    <button type="reset" class="btn btn-outline-warning mr-2">
                                        <i class="fal fa-undo mr-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn bg-primary-600" form="payment-form">
                                        <i class="fal fa-save mr-1"></i> Simpan Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
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
            // Initialize datepickers dengan konfigurasi yang sama
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id',
                orientation: 'bottom auto',
                templates: {
                    leftArrow: '<i class="fal fa-angle-left"></i>',
                    rightArrow: '<i class="fal fa-angle-right"></i>'
                }
            });

            // Initialize select2 dengan konfigurasi yang sama
            $('.select2').select2({
                dropdownCssClass: "move-up",
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder') || 'Pilih opsi';
                }
            });

            // Initialize money format
            $('.money').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                autoGroup: true,
                digits: 0,
                digitsOptional: false,
                prefix: 'Rp ',
                placeholder: '0',
                rightAlign: false
            });

            // Function untuk menghitung total
            function calculateTotals() {
                // Total Jasa Dokter Manual
                let totalJasaDokter = 0;
                $('.jasa-dokter-input').each(function() {
                    totalJasaDokter += parseFloat($(this).val()) || 0;
                });
                $('#total-jasa-dokter').text(formatNumber(totalJasaDokter));

                // Total JKP Tambahan
                let totalJkpTambahan = 0;
                $('.jkp-tambahan-input').each(function() {
                    totalJkpTambahan += parseFloat($(this).val()) || 0;
                });
                $('#total-jkp-tambahan').text(formatNumber(totalJkpTambahan));

                // Total Non Pajak
                let totalNonPajak = 0;
                $('.non-pajak-input').each(function() {
                    totalNonPajak += parseFloat($(this).val()) || 0;
                });
                $('#total-non-pajak').text(formatNumber(totalNonPajak));
            }

            // Function untuk format angka
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).format(num);
            }

            // Event listener untuk perhitungan otomatis
            $(document).on('input change', '.jasa-dokter-input, .jkp-tambahan-input, .non-pajak-input', function() {
                calculateTotals();
            });

            // Validasi form
            $('form').on('submit', function(e) {
                var isValid = true;
                var errorMessages = [];

                // Validasi tanggal
                var startDate = $('[name="tgl_ap_awal"]').val();
                var endDate = $('[name="tgl_ap_akhir"]').val();
                var paymentDate = $('[name="tanggal_pembayaran"]').val();

                if (!paymentDate) {
                    errorMessages.push('Tanggal pembayaran harus diisi');
                    isValid = false;
                }

                if (startDate && endDate) {
                    var start = new Date(startDate);
                    var end = new Date(endDate);

                    if (start > end) {
                        errorMessages.push('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                        isValid = false;
                    }
                }

                // Validasi dokter
                if (!$('[name="dokter_id"]').val()) {
                    errorMessages.push('Nama dokter harus dipilih');
                    isValid = false;
                }

                // Validasi metode pembayaran
                if (!$('[name="metode_pembayaran"]').val()) {
                    errorMessages.push('Metode pembayaran harus dipilih');
                    isValid = false;
                }

                // Validasi kas/bank
                if (!$('[name="kas_bank_id"]').val()) {
                    errorMessages.push('Kas/Bank harus dipilih');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    toastr.error(errorMessages.join('<br>'), 'Validasi Error', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000
                    });
                    return false;
                }

                // Show loading
                $(this).find('button[type="submit"]').prop('disabled', true).html(
                    '<i class="fal fa-spinner-third fa-spin mr-1"></i> Menyimpan...'
                );

                return true;
            });

            // Reset form
            $('button[type="reset"]').on('click', function() {
                Swal.fire({
                    title: 'Reset Form?',
                    text: 'Semua data yang telah diisi akan dihapus',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('form')[0].reset();
                        $('.select2').val(null).trigger('change');
                        calculateTotals();
                        toastr.success('Form berhasil direset');
                    }
                });
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Calculate initial totals
            calculateTotals();

            // Auto-focus pertama kali
            setTimeout(function() {
                $('[name="tgl_ap_awal"]').focus();
            }, 500);
        });
    </script>
@endsection
