<div class="tab-pane fade show active" id="tagihan-pasien" role="tabpanel">
    <div class="row mb-3">
        <div class="col">
            <label>No Registrasi:</label>
            <input type="text" class="form-control" value="{{ $bilingan->registration->registration_number ?? 'N/A' }}"
                readonly>
        </div>
        <div class="col">
            <label>Tgl:</label>
            <input type="text" class="form-control" value="{{ $bilingan->created_at ?? 'N/A' }}" readonly>
        </div>
        <div class="col">
            <label>Tipe Kunjungan:</label>
            <input type="text" class="form-control"
                value="{{ ucwords(str_replace('-', ' ', $bilingan->registration->registration_type ?? 'N/A')) }}"
                readonly>
        </div>
        <div class="col">
            <label>Nama Pasien:</label>
            <input type="text" class="form-control" value="{{ $bilingan->registration->patient->name ?? 'N/A' }}"
                readonly>
        </div>
        <div class="col">
            <label>RM:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->patient->medical_record_number ?? 'N/A' }}" readonly>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-success" id="save-final">Save Final</button>
        <button class="btn btn-warning" id="save-draft">Save Draft</button>
        <button class="btn btn-info" id="save-partial">Save Partial</button>
        <button class="btn btn-secondary" id="reload-tagihan">Reload Tagihan</button>
        <button class="btn btn-primary" id="add-tagihan">Tambah Tagihan</button>
    </div>

    {{-- Table --}}
    <table class="table table-striped table-bordered table-sm" id="tagihanTable">
        <thead>
            <tr>
                <th>Del</th>
                <th>Tanggal</th>
                <th>Detail Tagihan</th>
                <th>Quantity</th>
                <th style="white-space: nowrap">Nominal</th>
                <th style="white-space: nowrap">Tipe Diskon</th>
                <th style="white-space: nowrap">Disc (%)</th>
                <th style="white-space: nowrap">Diskon (Rp)</th>
                <th style="white-space: nowrap">Jamin (%)</th>
                <th style="white-space: nowrap">Jaminan (Rp)</th>
                <th style="white-space: nowrap">Wajib Bayar</th>
            </tr>
        </thead>
        <tbody>
            {{-- Data will be populated here using DataTable --}}
        </tbody>
    </table>
</div>

@section('plugin-tagihan-pasien')
    <script>
        function formatRupiah(angka) {
            return angka < 0 ? '-' + Math.abs(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").replace(/[^0-9.]/g,
                '') : angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").replace(/[^0-9.]/g, '');
        }

        function updateTotalBayarAndKembalian(nominal) {
            const tunai = parseFloat(nominal.value.replace(/\./g, '').replace(/[^0-9]/g, ''));
            document.getElementById('totalBayar').value = formatRupiah(tunai);
            updateKembalian();
        }

        function updateKembalian() {
            const totalBayar = parseFloat(document.getElementById('totalBayar').value.replace(/\./g, '').replace(/[^0-9]/g,
                ''));
            const sisaTagihan = parseFloat(document.querySelector('input[placeholder="Masukkan Sisa Tagihan"]').value
                .replace(/\./g, '').replace(/[^0-9]/g, ''));
            const kembalian = totalBayar - sisaTagihan;
            document.getElementById('kembalian').value = formatRupiah(kembalian);
        }

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

        // Fungsi formatRupiah yang konsisten
        function reverseFormat(angka) {
            if (!angka) return '0';
            return parseInt(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateWajibBayar(id, row) {
            const wajibBayar = calculateWajibBayar(row);

            $.ajax({
                url: '/simrs/kasir/tagihan-pasien/update/' + id,
                type: 'PUT',
                data: {
                    column: column,
                    value: value, // Nilai sudah dalam format angka (10000)
                    quantity: quantity,
                    nominal: reverseFormat(nominal),
                    disc: discountPercent,
                    diskon_rp: reverseFormat(discountRp),
                    jamin: jaminPercent,
                    jaminan_rp: reverseFormat(jaminRp),
                    wajib_bayar: reverseFormat(wajibBayar),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Wajib Bayar berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Terjadi kesalahan: ' + xhr.responseJSON.error,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        var runDatePicker = function() {
            $('#date_of_birth').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls
            });
        }

        $(document).ready(function() {

            // Set up initial unit price for nominal and calculate wajib bayar using:
            // wajib bayar = (nominal * quantity) - ((disc(%) / 100 * (nominal * quantity)) + diskon_rp) - ((jamin(%) / 100 * (nominal * quantity)) + jamin_rp)
            $('#tagihanTable').on('draw.dt', function() {
                $('#tagihanTable tbody tr').each(function() {
                    var $row = $(this);

                    // Nominal Field
                    var $nominal = $row.find('.input-nominal');
                    var nominal = parseFloat($nominal.val().replace(/\./g, ''));
                    if (!$nominal.attr('data-unit')) {
                        $nominal.attr('data-unit', nominal);
                    }

                    // Set up unit values for discount and jamin inputs
                    var $disc = $row.find('.input-disc');
                    var discValue = parseFloat($disc.val());
                    if (!$disc.attr('data-unit')) {
                        $disc.attr('data-unit', discValue);
                    }

                    var $diskonRp = $row.find('.input-diskon-rp');
                    var diskonRpValue = parseFloat($diskonRp.val().replace(/\./g, ''));
                    if (!$diskonRp.attr('data-unit')) {
                        $diskonRp.attr('data-unit', diskonRpValue);
                    }

                    var $jamin = $row.find('.input-jamin');
                    var jaminValue = parseFloat($jamin.val());
                    if (!$jamin.attr('data-unit')) {
                        $jamin.attr('data-unit', jaminValue);
                    }

                    var $jaminanRp = $row.find('.input-jaminan-rp');
                    var jaminanRpValue = parseFloat($jaminanRp.val().replace(/\./g, ''));
                    if (!$jaminanRp.attr('data-unit')) {
                        $jaminanRp.attr('data-unit', jaminanRpValue);
                    }

                    // Quantity (default to 0 if not entered)
                    var quantity = parseFloat($row.find('.input-quantity').val());

                    // Retrieve discount and jamin values for calculation
                    var discPercent = parseFloat($disc.val());
                    var diskonRp = parseFloat($diskonRp.val().replace(/\./g, ''));
                    var jaminPercent = parseFloat($jamin.val());
                    var jaminRp = parseFloat($jaminanRp.val().replace(/\./g, ''));

                    // Calculate total nominal and then discount and jaminan
                    var totalNominal = nominal * quantity;
                    var totalDiscount = (discPercent / 100) * totalNominal + diskonRp;
                    var totalJamin = (jaminPercent / 100) * totalNominal + jaminRp;
                    var wajibBayar = totalNominal - totalDiscount - totalJamin;
                    if (wajibBayar <= 0) wajibBayar = 0;

                    // Set the formatted value to the wajib bayar input
                    var $wajib = $row.find('.input-wajib-bayar');
                    $wajib.val(formatRupiah(wajibBayar));
                    if (!$wajib.attr('data-unit')) {
                        $wajib.attr('data-unit', wajibBayar);
                    }
                });
            });

            // Update nominal, wajib bayar, discount and jamin preview when quantity or related inputs are changed
            $(document).on('input',
                '.input-quantity, .input-disc, .input-diskon-rp, .input-jamin, .input-jaminan-rp',
                function() {
                    var $row = $(this).closest('tr');
                    var quantity = parseFloat($row.find('.input-quantity').val());

                    // Retrieve data nominal_awal from database if not available in the DOM attribute
                    var id = $row.find('.edit-input').first().data('id');
                    var nominalAwalAttr = $row.find('.input-nominal').attr('data-nominal_awal');
                    if (nominalAwalAttr == null || nominalAwalAttr === '') {
                        $.ajax({
                            url: '/simrs/kasir/get-nominal-awal/' + id,
                            type: 'GET',
                            async: false, // using synchronous call for immediate result
                            success: function(response) {
                                nominalAwalAttr = response.nominal_awal;
                                $row.find('.input-nominal').attr('data-nominal_awal',
                                    nominalAwalAttr);
                            },
                            error: function() {
                                nominalAwalAttr = '';
                            }
                        });
                    }

                    // Determine unit price: if nominal_awal exists, use it; otherwise fall back to data-unit
                    var unitPrice = nominalAwalAttr != null && nominalAwalAttr !== '' ?
                        parseFloat(nominalAwalAttr) :
                        parseFloat($row.find('.input-nominal').attr('data-unit'));

                    // Update total nominal based on unit price (quantity * unitPrice)
                    var totalNominal = quantity * (nominalAwalAttr !== null && nominalAwalAttr !== '' ?
                        parseFloat(nominalAwalAttr) : unitPrice);
                    $row.find('.input-nominal').val(totalNominal ? totalNominal.toLocaleString('id-ID') : '');

                    // Format discount and jamin inputs
                    var discVal = $row.find('.input-disc').val().replace(/[^0-9]/g, '');
                    var disc = discVal ? parseFloat(discVal) : 0;
                    if (discVal) {
                        $row.find('.input-disc').val(disc.toLocaleString('id-ID'));
                    }

                    var diskonRpVal = $row.find('.input-diskon-rp').val().replace(/[^0-9]/g, '');
                    var diskonRp = diskonRpVal ? parseFloat(diskonRpVal) : 0;
                    if (diskonRpVal) {
                        $row.find('.input-diskon-rp').val(diskonRp.toLocaleString('id-ID'));
                    }

                    var jaminVal = $row.find('.input-jamin').val().replace(/[^0-9]/g, '');
                    var jamin = jaminVal ? parseFloat(jaminVal) : 0;
                    if (jaminVal) {
                        $row.find('.input-jamin').val(jamin.toLocaleString('id-ID'));
                    }

                    var jaminanRpVal = $row.find('.input-jaminan-rp').val().replace(/[^0-9]/g, '');
                    var jaminanRp = jaminanRpVal ? parseFloat(jaminanRpVal) : 0;
                    if (jaminanRpVal) {
                        $row.find('.input-jaminan-rp').val(jaminanRp.toLocaleString('id-ID'));
                    }

                    // Calculate wajib bayar.
                    var wajibBayar = 0;
                    if (disc === 0 && diskonRp === 0 && jamin === 0 && jaminanRp === 0) {
                        wajibBayar = totalNominal;
                    } else {
                        wajibBayar = totalNominal - ((totalNominal * (disc / 100)) + diskonRp) - ((
                            totalNominal * (jamin / 100)) + jaminanRp);
                        if (wajibBayar <= 0) wajibBayar = 0;
                    }

                    $row.find('.input-wajib-bayar').val(wajibBayar ? wajibBayar.toLocaleString('id-ID') : '0');
                });

            // Bind keydown event on .edit-input only once to handle Enter key press
            $(document).off('keydown', '.edit-input').on('keydown', '.edit-input', function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    var $row = $(this).closest('tr');
                    var id = $(this).data('id');
                    var rowData = {
                        _token: '{{ csrf_token() }}'
                    };
                    $row.find('.edit-input').each(function() {
                        var column = $(this).data('column');
                        var value = $(this).val();
                        if ($(this).hasClass('format-currency')) {
                            value = value.replace(/\./g, '').replace(/[^0-9]/g, '');
                        }
                        rowData[column] = value;
                    });

                    $.ajax({
                        url: '/simrs/kasir/tagihan-pasien/update/' + id,
                        type: 'PUT',
                        data: rowData,
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Data berhasil diperbarui',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Terjadi kesalahan: ' + xhr.responseJSON.error,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                }
            });

            // Trigger save on Enter key press

            $('#tagihanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/tagihan-pasien/data/{{ $bilingan->id }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json && json.data) {
                            if ('{{ $bilingan->status }}' === 'final') {
                                return [];
                            }
                            return json.data;
                        } else {
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
                        render: function(data, type, row) {
                            return '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' +
                                row.id + '"><i class="fa fa-trash"></i></button>';
                        }
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
                            return '<input type="text" class="form-control edit-input input-quantity number-input" value="' +
                                data + '" data-column="quantity" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        className: 'nominal',
                        render: function(data, type, row) {
                            return '<input type="text" readonly class="form-control edit-input input-nominal format-currency" value="' +
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
                            return '<input type="text" class="form-control edit-input input-disc format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="disc" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'diskon_rp',
                        name: 'diskon_rp',
                        className: 'diskon-rp',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input input-diskon-rp format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="diskon_rp" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'jamin',
                        name: 'jamin',
                        className: 'jamin',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input input-jamin" value="' +
                                data + '" data-column="jamin" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'jaminan_rp',
                        name: 'jaminan_rp',
                        className: 'jaminan-rp',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input input-jaminan-rp format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="jaminan_rp" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'wajib_bayar',
                        name: 'wajib_bayar',
                        className: 'wajib-bayar',
                        render: function(data, type, row) {
                            return '<input type="text" readonly class="form-control edit-input input-wajib-bayar format-currency" value="' +
                                (data ? parseFloat(data).toLocaleString('id-ID') : '') +
                                '" data-column="wajib_bayar" data-id="' + row.id + '">';
                        }
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                className: 'smaller-table'
            });

            $('#bilinganTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/bilingan/data/{{ $bilingan->id }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json && json.data ? json.data : [];
                    }
                },
                columns: [{
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
                        data: 'total_tagihan',
                        name: 'total_tagihan',
                        className: 'total-tagihan',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data !== null ? parseFloat(data).toLocaleString('id-ID') : '0') +
                                '" data-column="total_tagihan" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'jaminan',
                        name: 'jaminan',
                        className: 'jaminan',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data !== null ? parseFloat(data).toLocaleString('id-ID') : '0') +
                                '" data-column="jaminan" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'tagihan_pasien',
                        name: 'tagihan_pasien',
                        className: 'tagihan-pasien',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data !== null ? parseFloat(data).toLocaleString('id-ID') : '0') +
                                '" data-column="tagihan_pasien" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'jumlah_terbayar',
                        name: 'jumlah_terbayar',
                        className: 'jumlah-terbayar',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data !== null ? parseFloat(data).toLocaleString('id-ID') : '0') +
                                '" data-column="jumlah_terbayar" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'sisa_tagihan',
                        name: 'sisa_tagihan',
                        className: 'sisa-tagihan',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data !== null ? parseFloat(data).toLocaleString('id-ID') : '0') +
                                '" data-column="sisa_tagihan" data-id="' + row.id + '">';
                        }
                    },
                    {
                        data: 'kembalian',
                        name: 'kembalian',
                        className: 'kembalian',
                        render: function(data, type, row) {
                            return '<input type="text" class="form-control edit-input format-currency" value="' +
                                (data !== null ? parseFloat(data).toLocaleString('id-ID') : '0') +
                                '" data-column="kembalian" data-id="' + row.id + '">';
                        }
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                className: 'smaller-table'
            });

            runDatePicker();

            $(document).on('input', '.number-input', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
            });

            $(document).on('input', '.format-currency', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                if (value) {
                    $(this).val(parseInt(value).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
            });

            $(function() {
                $('#tipe-diskon').select2();
                $('.select2').select2({
                    dropdownCssClass: "move-up"
                });
                $(".select2").on("select2:open", function() {
                    var searchField = $(".select2-search__field");
                    searchField.insertBefore(searchField.prev());
                });
            });

            var today = new Date();
            var formattedToday = today.getFullYear() + '-' +
                ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
                ('0' + today.getDate()).slice(-2) + ' ' +
                ('0' + today.getHours()).slice(-2) + ':' +
                ('0' + today.getMinutes()).slice(-2) + ':' +
                ('0' + today.getSeconds()).slice(-2);

            $('#datepicker-1').daterangepicker({
                opens: 'left',
                startDate: moment(today).format('YYYY-MM-DD'),
                endDate: moment(today).format('YYYY-MM-DD'),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            let wajibBayar = 0;
            $('#tagihanTable').on('draw.dt', function() {
                let totalNominal = 0;
                $('#tagihanTable .input-wajib-bayar').each(function() {
                    const value = parseFloat($(this).val().replace(/\./g, '').replace(/[^0-9]/g,
                        '')) || 0;
                    totalNominal += value;
                });
                wajibBayar = totalNominal;
            });

            $('#save-final').on('click', function() {
                $.ajax({
                    url: '/simrs/kasir/bilingan/update-status/{{ $bilingan->id }}',
                    type: 'PUT',
                    data: {
                        status: 'final',
                        wajib_bayar: wajibBayar,
                        _token: '{{ csrf_token() }}'
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
                    url: '/simrs/kasir/bilingan/update-status/{{ $bilingan->id }}',
                    type: 'PUT',
                    data: {
                        status: 'draft',
                        wajib_bayar: wajibBayar,
                        _token: '{{ csrf_token() }}'
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
                        // Refresh the DataTable and switch to the #pembayaran-tagihan tab
                        $('#tagihanTable').DataTable().ajax.reload();
                        $('.nav-tabs a[href="#pembayaran-tagihan"]').tab('show');
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
                    url: '/simrs/kasir/bilingan/update-status/{{ $bilingan->id }}',
                    type: 'PUT',
                    data: {
                        status: 'partial',
                        wajib_bayar: wajibBayar,
                        _token: '{{ csrf_token() }}'
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
                $('#add-tagihan-modal').modal('show');
            });

            $('#tagihanTable').on('init.dt', function() {
                $('.select2').select2();
            });
        });
    </script>
@endsection
