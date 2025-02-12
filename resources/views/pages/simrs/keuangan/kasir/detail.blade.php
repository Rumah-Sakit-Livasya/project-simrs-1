@extends('inc.layout')
@section('title', 'Kasir')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">

                <div id="panel-5" class="panel" style="height: 100vh;">
                    <div class="panel-container show" style="height: 100%;">
                        <div class="panel-content" style="height: calc(100% - 50px);">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tagihan-pasien" role="tab"><i
                                            class="fal fa-home mr-1"></i> Tagihan Pasien</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#pembayaran-tagihan" role="tab"><i
                                            class="fal fa-user mr-1"></i> Pembayaran Tagihan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#dp_pasien" role="tab"><i
                                            class="fal fa-clock mr-1"></i> DP Pasien</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" style="height: 100%; overflow-x: hidden  ;">
                                <div class="tab-pane fade show active" id="tagihan-pasien" role="tabpanel">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label>No Registrasi:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->registration->registration_number ?? 'N/A' }}"
                                                readonly>
                                        </div>
                                        <div class="col">
                                            <label>Tgl:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->date ?? 'N/A' }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label>Tipe Kunjungan:</label>
                                            <input type="text" class="form-control"
                                                value="{{ ucwords(str_replace('-', ' ', $tagihan_pasien->registration->registration_type ?? 'N/A')) }}"
                                                readonly>
                                        </div>
                                        <div class="col">
                                            <label>Nama Pasien:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->registration->patient->name ?? 'N/A' }}"
                                                readonly>
                                        </div>
                                        <div class="col">
                                            <label>RM:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->registration->patient->medical_record_number ?? 'N/A' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    {{-- Table --}}
                                    <table class="table table-striped table-bordered" id="tagihanTable">
                                        <thead>
                                            <tr>
                                                <th>Del</th>
                                                <th>Tanggal</th>
                                                <th>Detail Tagihan</th>
                                                <th>Quantity</th>
                                                <th>Nominal</th>
                                                <th>Tipe Diskon</th>
                                                <th>Disc (%)</th>
                                                <th>Diskon (Rp)</th>
                                                <th>Jamin (%)</th>
                                                <th>Jaminan (Rp)</th>
                                                <th>Wajib Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Data will be populated here using DataTable --}}
                                        </tbody>
                                    </table>
                                    <div class="mb-3">
                                        <button class="btn btn-success" id="save-final">Save Final</button>
                                        <button class="btn btn-warning" id="save-draft">Save Draft</button>
                                        <button class="btn btn-info" id="save-partial">Save Partial</button>
                                        <button class="btn btn-secondary" id="reload-tagihan">Reload Tagihan</button>
                                        <button class="btn btn-primary" id="add-tagihan">Tambah Tagihan</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pembayaran-tagihan" role="tabpanel">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label>No Registrasi:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->registration->registration_number ?? 'N/A' }}"
                                                readonly>
                                        </div>
                                        <div class="col">
                                            <label>Tgl:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->date ?? 'N/A' }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label>Tipe Kunjungan:</label>
                                            <input type="text" class="form-control"
                                                value="{{ ucwords(str_replace('-', ' ', $tagihan_pasien->registration->registration_type ?? 'N/A')) }}"
                                                readonly>
                                        </div>
                                        <div class="col">
                                            <label>Nama Pasien:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->registration->patient->name ?? 'N/A' }}"
                                                readonly>
                                        </div>
                                        <div class="col">
                                            <label>RM:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $tagihan_pasien->registration->patient->medical_record_number ?? 'N/A' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div
                                                    class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                                                    <div class="card-title text-white">Wajib Bayar</div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Wajib Bayar" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div
                                                    class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                                                    <div class="card-title text-white">DP Pasien</div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan DP Pasien" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div
                                                    class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                                                    <div class="card-title text-white">Sisa Tagihan</div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Sisa Tagihan" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div
                                                    class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                                                    <div class="card-title text-white">Tunai</div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Tunai" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div
                                                    class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                                                    <div class="card-title text-white">Total Bayar</div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Total Bayar" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div
                                                    class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                                                    <div class="card-title text-white">Kembalian</div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Kembalian" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <div class="card border mb-4 mb-xl-0">
                                                <div class="card-header bg-warning py-2 pr-2 d-flex align-items-center1 flex-wrap"
                                                    data-toggle="collapse" data-target="#paymentMethods"
                                                    aria-expanded="false" aria-controls="paymentMethods">
                                                    <div class="card-title text-white text-center">Pembayaran Metode
                                                        Lainnya</div>
                                                </div>
                                                <div class="collapse" id="paymentMethods">
                                                    <div class="card-body">
                                                        <div class="section-title mt-3">Credit Card</div>
                                                        <table class="table table-bordered">
                                                            <thead class="table-header">
                                                                <tr>
                                                                    <th>Mesin EDC</th>
                                                                    <th>Type</th>
                                                                    <th>CC Number</th>
                                                                    <th>Auth Number</th>
                                                                    <th>Batch</th>
                                                                    <th>Nominal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-select select2">
                                                                            <option>MANDIRI</option>
                                                                            <option>BCA</option>
                                                                            <option>BNI</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select select2">
                                                                            <option>Debit Card</option>
                                                                            <option>Credit Card</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><select class="form-select select2">
                                                                            <option>BCA</option>
                                                                        </select></td>
                                                                    <td><select class="form-select select2">
                                                                            <option>Credit Card</option>
                                                                        </select></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><select class="form-select select2">
                                                                            <option>BNI</option>
                                                                        </select></td>
                                                                    <td><select class="form-select select2">
                                                                            <option>Credit Card</option>
                                                                        </select></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                    <td><input type="text" class="form-control"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <div class="section-title">Via Transfer Bank</div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Bank RS</label>
                                                                <select class="form-select select2">
                                                                    <option>Bank RS A</option>
                                                                    <option>Bank RS B</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Bank Pengirim</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Nominal Transfer</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">No. Rek Pengirim</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="section-title">Ditanggung Dokter</div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Nama Dokter</label>
                                                                <select class="form-select select2">
                                                                    <option>Dr. A</option>
                                                                    <option>Dr. B</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Nominal Dijamin Dokter</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="section-title">Ditanggung Karyawan</div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Nama Karyawan</label>
                                                                <select class="form-select select2">
                                                                    <option>Karyawan A</option>
                                                                    <option>Karyawan B</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Nominal Dijamin Karyawan</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="dp_pasien" role="tabpanel">
                                    Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out
                                    mcsweeney's
                                    organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger
                                    bag gentrify
                                    pitchfork tattooed craft beer, iphone skateboard locavore carles etsy
                                    salvia banksy
                                    hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid
                                    8-bit cred
                                    pitchfork.
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- Datepicker Range --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        var runDatePicker = function() {

            // minimum setup
            $('#date_of_birth').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls
            });

        }

        $(document).ready(function() {
            $('#tagihanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/tagihan-pasien/data/{{ $tagihan_pasien->id }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json && json.data) {
                            console.log('Query Results:', json.data);
                            return json.data;
                        } else {
                            console.error('Invalid JSON response:', json);
                            return [];
                        }
                    }
                },
                columns: [{
                        data: 'del',
                        name: 'del',
                        orderable: false,
                        searchable: false,
                        className: 'del',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        className: 'tanggal',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input" value="' +
                                data + '" data-column="tanggal" data-id="' + row.id +
                                '" style="width: auto; max-width: 100%; white-space: nowrap;">';
                        }
                    },
                    {
                        data: 'detail_tagihan',
                        name: 'detail_tagihan',
                        className: 'detail-tagihan',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input" style="width: 300px;" value="' +
                                data + '" data-column="detail_tagihan" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        className: 'quantity',
                        render: function(data, type, row) {
                            return '<input type="number" class="form-control edit-input" value="' +
                                data + '" data-column="quantity" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        className: 'nominal',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="nominal" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'tipe_diskon',
                        name: 'tipe_diskon',
                        className: 'tipe-diskon',
                        render: function(data, type, row) {
                            return '<select class="form-control edit-input select2" data-column="tipe_diskon" data-id="' +
                                row.id + '">' +
                                '<option value="None"' + (data === 'None' ? ' selected' : '') +
                                '>None</option>' +
                                '<option value="All"' + (data === 'All' ? ' selected' : '') +
                                '>All</option>' +
                                '<option value="Dokter"' + (data === 'Dokter' ? ' selected' : '') +
                                '>Dokter</option>' +
                                '<option value="Rumah Sakit"' + (data === 'Rumah Sakit' ?
                                    ' selected' : '') + '>Rumah Sakit</option>' +
                                '</select>';
                        }
                    },
                    {
                        data: 'disc',
                        name: 'disc',
                        className: 'disc',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="disc" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'diskon_rp',
                        name: 'diskon_rp',
                        className: 'diskon-rp',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="diskon_rp" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'jamin',
                        name: 'jamin',
                        className: 'jamin',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input" value="' +
                                data + '" data-column="jamin" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'jaminan_rp',
                        name: 'jaminan_rp',
                        className: 'jaminan-rp',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="jaminan_rp" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'wajib_bayar',
                        name: 'wajib_bayar',
                        className: 'wajib-bayar',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="wajib_bayar" data-id="' + row.id + '">';
                        }
                    },
                ],
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                // dom: '<"top"i>rt<"bottom"flp><"clear">',
                className: 'smaller-table'
            });

            // Format currency input
            $(document).on('input', '.format-currency', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                if (value) {
                    $(this).val(parseInt(value).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
            });

            // Event listener for input changes
            $('#tagihanTable').on('change', '.edit-input', function() {
                var id = $(this).data('id');
                var column = $(this).data('column');
                var value = $(this).hasClass('format-currency') ? $(this).val().replace(/\./g, '').replace(
                        /[^0-9]/g, '') : $(this)
                    .val(); // Remove formatting for database only if it has class format-currency

                $.ajax({
                    url: '/simrs/kasir/tagihan-pasien/update/' + id,
                    type: 'PUT',
                    data: {
                        column: column,
                        value: value,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.success,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'An error occurred: ' + xhr.responseJSON.error,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });

            // Datepciker
            runDatePicker();

            // Select 2
            $(function() {
                $('#tipe-diskon').select2();
                $('.select2').select2({
                    dropdownCssClass: "move-up"
                });
                $(".select2").on("select2:open", function() {
                    // Mengambil elemen kotak pencarian
                    var searchField = $(".select2-search__field");

                    // Mengubah urutan elemen untuk memindahkannya ke atas
                    searchField.insertBefore(searchField.prev());
                });
            });

            /// Get the current date and time
            var today = new Date();

            // Format it as "YYYY-MM-DD"
            var formattedToday = today.getFullYear() + '-' +
                ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
                ('0' + today.getDate()).slice(-2) + ' ' +
                ('0' + today.getHours()).slice(-2) + ':' +
                ('0' + today.getMinutes()).slice(-2) + ':' +
                ('0' + today.getSeconds()).slice(-2);

            // Set the default date for the datepicker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                startDate: moment(today).format('YYYY-MM-DD'),
                endDate: moment(today).format('YYYY-MM-DD'),
                // timePicker: true, // Enable time selection
                // timePicker24Hour: true, // 24-hour format
                // timePickerSeconds: true, // Include seconds in time selection
                locale: {
                    format: 'YYYY-MM-DD' // Display format for the picker
                }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') +
                    ' to ' + end.format('YYYY-MM-DD'));
            });

            $('#loading-spinner').show();
            // initialize datatable
            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            $('#save-final').on('click', function() {
                $.ajax({
                    url: '/simrs/kasir/tagihan-pasien/update-status/{{ $tagihan_pasien->id }}',
                    type: 'PUT',
                    data: {
                        status: 'final',
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Status updated to Final',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        // Optionally refresh the table here
                    },
                    error: function(xhr) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error: ' + xhr.responseJSON.error,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });

            $('#save-draft').on('click', function() {
                $.ajax({
                    url: '/simrs/kasir/tagihan-pasien/update-status/{{ $tagihan_pasien->id }}',
                    type: 'PUT',
                    data: {
                        status: 'draft',
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Status updated to Draft',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        // Optionally refresh the table here
                    },
                    error: function(xhr) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error: ' + xhr.responseJSON.error,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });

            $('#save-partial').on('click', function() {
                $.ajax({
                    url: '/simrs/kasir/tagihan-pasien/update-status/{{ $tagihan_pasien->id }}',
                    type: 'PUT',
                    data: {
                        status: 'partial',
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Status updated to Partial',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        // Optionally refresh the table here
                    },
                    error: function(xhr) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error: ' + xhr.responseJSON.error,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });

            $('#reload-tagihan').on('click', function() {
                $('#tagihanTable').DataTable().ajax.reload();
            });

            $('#add-tagihan').on('click', function() {
                // Show modal for adding tagihan
                $('#addTagihanModal').modal('show');
            });

            // Inisialisasi Select2 setelah tabel diisi
            $('#tagihanTable').on('init.dt', function() {
                $('.select2').select2();
            });
        });

        // Input RM
        function formatAngka(input) {
            var value = input.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 6) {
                value = value.substr(0, 6);
            }

            if (value.length > 0) {
                formattedValue = value.match(/.{1,2}/g).join('-');
            }

            input.value = formattedValue;
        }
    </script>
@endsection
