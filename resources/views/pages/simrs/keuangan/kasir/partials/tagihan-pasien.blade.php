<div class="tab-pane fade show active" id="tagihan-pasien" role="tabpanel">
    <div class="row mb-2">
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
    </div>
    <div class="row mb-3">
        <div class="col">
            <label>RM:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->patient->medical_record_number ?? 'N/A' }}" readonly>
        </div>
        <div class="col">
            <label>Penjamin:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->penjamin->nama_perusahaan ?? ($bilingan->registration->penjamin ?? 'N/A') }}"
                readonly>
        </div>
        <div class="col">
            <label>Nama Dokter:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->doctor->name ?? ($bilingan->registration->nama_dokter ?? 'N/A') }}"
                readonly>
        </div>
        <div class="col">
            <label>Rujukan:</label>
            <input type="text" class="form-control"
                value="{{ ucwords(strtolower($bilingan->registration->rujukan ?? 'N/A')) }}" readonly>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-success" id="save-final">Save Final</button>
        @if ($bilingan->status !== 'final')
            <button class="btn btn-warning" id="save-draft">Save Draft</button>
        @endif
        {{-- <button class="btn btn-info" id="save-partial">Save Partial</button> --}}
        <button class="btn btn-secondary" id="reload-tagihan">Reload Tagihan</button>
        <button class="btn btn-primary" id="add-tagihan">Tambah Tagihan</button>
        <button class="btn btn-info position-relative ml-2" id="order-notification-btn">
            <i class="fa fa-bell"></i>
            <span class="badge badge-danger position-absolute top-0 start-100 translate-middle"
                id="order-notification-badge" style="font-size: 0.8em;">
                {{ $belum_ditagihkan ?? 0 }}
            </span>
        </button>

        <div id="order-notification-popup" class="card shadow border position-fixed"
            style="display: none; top: 80px; right: 30px; z-index: 1050; width: 350px; max-width: 90vw;">
            <div class="card-header d-flex justify-content-between align-items-center py-2 px-3">
                <span class="font-weight-bold">Notifikasi Order</span>
                <button type="button" class="close" id="close-order-notification-popup" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body p-2" id="order-notification-list" style="max-height: 350px; overflow-y: auto;">
                {{-- Daftar notifikasi order akan dimuat di sini --}}
                <div class="text-center text-muted py-3" id="order-notification-empty">Tidak ada notifikasi order.</div>
            </div>
        </div>
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
        <tfoot>
            <tr class="bg-gray-200">
                {{-- Kosongkan 5 kolom pertama untuk mendorong konten ke kanan --}}
                <th colspan="5"></th>

                {{-- Gabungkan 2 kolom untuk setiap total agar rapi --}}
                <th colspan="2" class="text-right">
                    <strong>Total DP:</strong>
                    <span id="totalDp" class="d-block"></span>
                </th>
                <th colspan="2" class="text-right">
                    <strong>Total Jaminan:</strong>
                    <span id="totalJaminan" class="d-block"></span>
                </th>
                <th colspan="2" class="text-right">
                    <strong>Total Tagihan:</strong>
                    <span id="totalTagihan" class="d-block"></span>
                </th>
            </tr>
        </tfoot>
    </table>
</div>

@section('plugin-tagihan-pasien')
    <script>
        // ================================================================
        // FUNGSI BANTUAN (HELPERS)
        // ================================================================

        /**
         * Mengubah angka menjadi format Rupiah (misal: 150000 -> "150.000")
         * @param {number|string} angka - Nilai yang akan diformat.
         * @returns {string} String yang sudah diformat.
         */
        function formatRupiah(angka) {
            let number_string = String(angka).replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah || '0';
        }

        /**
         * Mengubah format Rupiah kembali menjadi angka (misal: "150.000" -> 150000)
         * @param {string} rupiah - String Rupiah yang akan di-unformat.
         * @returns {number} Nilai numerik murni.
         */
        function unformatRupiah(rupiah) {
            return parseFloat(String(rupiah).replace(/\./g, '')) || 0;
        }


        $(document).ready(function() {
            // Variabel global untuk menyimpan total wajib bayar
            let totalWajibBayarGlobal = 0;

            // ================================================================
            // FUNGSI KALKULASI UTAMA
            // ================================================================

            /**
             * Menghitung ulang semua nilai dalam satu baris (<tr>).
             * @param {jQuery} $row - Objek jQuery dari elemen <tr> yang akan dihitung.
             */
            function recalculateRow($row) {
                // 1. Baca semua nilai, PASTIKAN di-unformat terlebih dahulu
                const quantity = parseFloat($row.find('.input-quantity').val()) || 0;
                const nominal = unformatRupiah($row.find('.input-nominal-awal').val());
                const discPercent = parseFloat($row.find('.input-disc').val()) || 0;
                const diskonRp = unformatRupiah($row.find('.input-diskon-rp').val());
                const jaminPercent = parseFloat($row.find('.input-jamin').val()) || 0;
                const jaminanRp = unformatRupiah($row.find('.input-jaminan-rp').val());

                // 2. Lakukan perhitungan dengan angka murni
                const totalNominal = nominal * quantity;
                const totalDiskon = (totalNominal * (discPercent / 100)) + diskonRp;
                const totalJaminan = (totalNominal * (jaminPercent / 100)) + jaminanRp;
                const wajibBayar = Math.max(0, totalNominal - totalDiskon - totalJaminan);

                // 3. Update input 'wajib_bayar', dan format ke Rupiah
                $row.find('.input-wajib-bayar').val(formatRupiah(wajibBayar.toFixed(0)));
            }

            /**
             * Menghitung ulang semua total di footer (tfoot) dan total global.
             */
            function recalculateTableTotals() {
                let grandTotalTagihan = 0;
                let grandTotalJaminan = 0;
                let grandTotalWajibBayar = 0;

                $('#tagihanTable tbody tr').each(function() {
                    const $row = $(this);
                    const quantity = parseFloat($row.find('.input-quantity').val()) || 0;
                    const nominal = unformatRupiah($row.find('.input-nominal-awal').val());
                    const jaminPercent = parseFloat($row.find('.input-jamin').val()) || 0;
                    const jaminanRp = unformatRupiah($row.find('.input-jaminan-rp').val());
                    const wajibBayar = unformatRupiah($row.find('.input-wajib-bayar').val());

                    const totalNominal = wajibBayar * quantity;
                    const totalJaminan = (totalNominal * (jaminPercent / 100)) + jaminanRp;

                    grandTotalTagihan += totalNominal;
                    grandTotalJaminan += totalJaminan;
                    grandTotalWajibBayar += wajibBayar;
                });

                const totalDp = {{ $bilingan->total_dp ?? 0 }};

                // Update footer dengan format Rupiah
                $('#totalDp').text('Rp ' + formatRupiah(totalDp));
                $('#totalJaminan').text('Rp ' + formatRupiah(grandTotalJaminan));
                $('#totalTagihan').text('Rp ' + formatRupiah(grandTotalTagihan));

                // Update variabel global
                totalWajibBayarGlobal = grandTotalWajibBayar;
            }


            // ================================================================
            // INISIALISASI DATATABLE
            // ================================================================
            const tagihanTable = $('#tagihanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/tagihan-pasien/data/{{ $bilingan->id }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'del',
                        name: 'del',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}"><i class="fa fa-trash"></i></button>`;
                        }
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        render: function(data, type, row) {
                            return `<input type="text" class="form-control edit-input" value="${data}" data-column="tanggal" data-id="${row.id}" style="width: auto; max-width: 100%; white-space: nowrap;">`;
                        }
                    },
                    {
                        data: 'detail_tagihan',
                        name: 'detail_tagihan',
                        render: function(data, type, row) {
                            return `<input type="text" class="form-control edit-input" style="width: 300px;" value="${data}" data-column="detail_tagihan" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        render: function(data, type, row) {
                            return `<input type="number" class="form-control edit-input input-quantity" value="${data}" data-column="quantity" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'nominal_awal',
                        name: 'nominal_awal',
                        render: function(data, type, row) {
                            return `<input type="text" readonly class="form-control edit-input input-nominal-awal format-rupiah" value="${formatRupiah(data)}" data-column="nominal" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'tipe_diskon',
                        name: 'tipe_diskon',
                        render: function(data, type, row) {
                            return `<select class="form-control edit-input select-tipe-diskon" data-column="tipe_diskon" data-id="${row.id}"><option value="None"${data === 'None' ? ' selected' : ''}>None</option><option value="All"${data === 'All' ? ' selected' : ''}>All</option><option value="Dokter"${data === 'Dokter' ? ' selected' : ''}>Dokter</option><option value="Rumah Sakit"${data === 'Rumah Sakit' ? ' selected' : ''}>Rumah Sakit</option></select>`;
                        }
                    },
                    {
                        data: 'disc',
                        name: 'disc',
                        render: function(data, type, row) {
                            return `<input type="number" class="form-control edit-input input-disc" value="${parseFloat(data) || 0}" data-column="disc" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'diskon_rp',
                        name: 'diskon_rp',
                        render: function(data, type, row) {
                            return `<input type="text" class="form-control edit-input input-diskon-rp format-rupiah" value="${formatRupiah(data)}" data-column="diskon_rp" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'jamin',
                        name: 'jamin',
                        render: function(data, type, row) {
                            return `<input type="number" class="form-control edit-input input-jamin" value="${parseFloat(data) || 0}" data-column="jamin" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'jaminan_rp',
                        name: 'jaminan_rp',
                        render: function(data, type, row) {
                            return `<input type="text" class="form-control edit-input input-jaminan-rp format-rupiah" value="${formatRupiah(data)}" data-column="jaminan_rp" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'wajib_bayar',
                        name: 'wajib_bayar',
                        render: function(data, type, row) {
                            return `<input type="text" readonly class="form-control edit-input input-wajib-bayar format-rupiah" value="${formatRupiah(data)}" data-column="wajib_bayar" data-id="${row.id}">`;
                        }
                    },
                ],
                "drawCallback": function(settings) {
                    $('.select-tipe-diskon').select2({
                        width: '100%'
                    });
                    $('#tagihanTable tbody tr').each(function() {
                        recalculateRow($(this));
                    });
                    recalculateTableTotals();
                },
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
            });


            // ================================================================
            // EVENT HANDLERS
            // ================================================================

            // 1. Event handler untuk AUTO-FORMAT RUPIAH saat mengetik
            $('#tagihanTable tbody').on('input', '.format-rupiah', function(e) {
                let cursorPosition = this.selectionStart;
                let originalLength = this.value.length;

                let value = $(this).val();
                let unformattedValue = unformatRupiah(value);
                let formattedValue = formatRupiah(unformattedValue);

                $(this).val(formattedValue);

                let newLength = this.value.length;
                cursorPosition = cursorPosition + (newLength - originalLength);
                this.setSelectionRange(cursorPosition, cursorPosition);
            });

            // 2. Event handler utama untuk kalkulasi real-time
            $('#tagihanTable tbody').on('input',
                '.input-quantity, .input-disc, .input-diskon-rp, .input-jamin, .input-jaminan-rp',
                function() {
                    const $currentRow = $(this).closest('tr');
                    recalculateRow($currentRow);
                    recalculateTableTotals();
                });

            // 3. Event handler KHUSUS untuk perubahan Tipe Diskon
            $('#tagihanTable tbody').on('change', '.select-tipe-diskon', function() {
                const $currentRow = $(this).closest('tr');
                const tagihanId = $(this).data('id');
                const tipeDiskon = $(this).val();

                $.ajax({
                    url: `/simrs/kasir/tagihan-pasien/update-disc/${tagihanId}`,
                    type: 'PUT',
                    data: {
                        tipe_diskon: tipeDiskon,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $currentRow.find('.input-diskon-rp').val(formatRupiah(response
                                .diskon));
                            recalculateRow($currentRow);
                            recalculateTableTotals();
                        }
                    }
                });
            });

            // 4. Event handler untuk simpan data dengan "Enter"
            $('#tagihanTable tbody').on('keydown', '.edit-input', function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    var $row = $(this).closest('tr');
                    var id = $(this).data('id');
                    var rowData = {
                        _token: '{{ csrf_token() }}'
                    };

                    $row.find('.edit-input').each(function() {
                        let value = $(this).val();
                        if ($(this).hasClass('format-rupiah')) {
                            value = unformatRupiah(value);
                        }
                        rowData[$(this).data('column')] = value;
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
                                timer: 1500
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Gagal memperbarui data',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            });

            // 5. Event handler untuk tombol Hapus
            $('#tagihanTable tbody').on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const tagihanId = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data tagihan ini akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/simrs/kasir/tagihan-pasien/${tagihanId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Terhapus!', 'Data tagihan telah dihapus.',
                                    'success');
                                tagihanTable.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error');
                            }
                        });
                    }
                });
            });

            // 6. Event handler untuk tombol Save Final/Draft
            $('#save-final, #save-draft').on('click', function() {
                const status = $(this).attr('id') === 'save-final' ? 'final' : 'draft';
                $.ajax({
                    url: '/simrs/kasir/bilingan/update-status/{{ $bilingan->id }}',
                    type: 'PUT',
                    data: {
                        status: status,
                        wajib_bayar: totalWajibBayarGlobal,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: `Status berhasil diubah ke ${status}`,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => location.reload());
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

            // 7. Handler untuk tombol lainnya
            $('#reload-tagihan').on('click', function() {
                tagihanTable.ajax.reload();
            });
            $('#add-tagihan').on('click', function() {
                $('#add-tagihan-modal').modal('show');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // ================================================================
            // NEW NOTIFICATION LOGIC
            // ================================================================
            const notifBtn = $('#order-notification-btn');
            const notifPopup = $('#order-notification-popup');
            const notifClose = $('#close-order-notification-popup');
            const notifList = $('#order-notification-list');
            const notifEmpty = $('#order-notification-empty').detach(); // Detach the "empty" message to reuse it
            const notifBadge = $('#order-notification-badge');
            const registrationId = "{{ $bilingan->registration->id }}";
            const bilinganId = "{{ $bilingan->id }}";
            const tagihanTable = $('#tagihanTable').DataTable();

            function fetchNotifications() {
                $.ajax({
                    url: `/simrs/kasir/order-notifications/${registrationId}`,
                    type: 'GET',
                    success: function(data) {
                        notifList.empty(); // Clear previous list
                        notifBadge.text(data.length); // Update badge count

                        if (data.length === 0) {
                            notifList.append(notifEmpty);
                        } else {
                            data.forEach(function(order) {
                                const itemHtml = `
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2 px-1">
                                <div>
                                    <strong>${order.title}</strong>
                                    <br>
                                    <small class="text-muted">${order.time} - Rp ${formatRupiah(order.nominal)}</small>
                                </div>
                                <button class="btn btn-primary btn-xs btn-process-order" data-order-id="${order.id}" data-order-type="${order.type}" title="Tambahkan ke Tagihan">
                                    <i class="fal fa-plus"></i>
                                </button>
                            </div>
                        `;
                                notifList.append(itemHtml);
                            });
                        }
                    }
                });
            }

            notifBtn.on('click', function() {
                if (notifPopup.is(':visible')) {
                    notifPopup.hide();
                } else {
                    fetchNotifications();
                    notifPopup.show();
                }
            });

            notifClose.on('click', function() {
                notifPopup.hide();
            });

            // Event handler for the dynamically created "process order" buttons
            notifList.on('click', '.btn-process-order', function() {
                const btn = $(this);
                const orderId = btn.data('order-id');
                const orderType = btn.data('order-type');

                // Add a loading state to the button
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: "{{ route('kasir.process-order') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId,
                        order_type: orderType,
                        bilingan_id: bilinganId
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        tagihanTable.ajax.reload(); // Reload the main billing table
                        fetchNotifications(); // Refresh the notification list
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh(xhr.responseJSON.message ||
                            'Gagal memproses order.');
                        // Re-enable the button on error
                        btn.prop('disabled', false).html('<i class="fal fa-plus"></i>');
                    }
                });
            });

            // Optional: Close popup when clicking outside
            $(document).on('mousedown', function(event) {
                if (notifPopup.is(':visible') && !notifPopup.is(event.target) && notifPopup.has(event
                        .target).length === 0 && !notifBtn.is(event.target) && notifBtn.has(event.target)
                    .length === 0) {
                    notifPopup.hide();
                }
            });
        });
    </script>
@endsection
