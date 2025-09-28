{{-- ==================== DP Pasien ==================== --}}
<div class="tab-pane fade" id="dp_pasien" role="tabpanel">
    <div class="row mb-3">
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
        <div class="col">
            <label>No Registrasi:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->registration_number ?? 'N/A' }}" readonly>
        </div>
        <div class="col">
            <label>Tipe Kunjungan:</label>
            <input type="text" class="form-control"
                value="{{ ucwords(str_replace('-', ' ', $bilingan->registration->registration_type ?? 'N/A')) }}"
                readonly>
        </div>
    </div>
    <form id="downPaymentForm">
        <div class="row mb-3">
            <div class="col">
                <label>Metode Pembayaran:</label>
                <select class="form-control select2" name="metode_pembayaran">
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </div>
            <div class="col">
                <label>Nominal:</label>
                <input type="text" class="form-control format-rupiah" name="nominal" placeholder="Masukkan Nominal">
            </div>
            <div class="col">
                <label>Keterangan:</label>
                <input type="text" class="form-control" name="keterangan" placeholder="Masukkan Keterangan"
                    value="DP Pasien">
            </div>
            <div class="col">
                <label>Total DP:</label>
                {{-- GANTI name="total_dp" MENJADI id="totalDpDisplay" --}}
                <input type="text" class="form-control" id="totalDpDisplay" readonly>
                <div class="row my-3">
                    <div class="col text-left">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fal fa-undo"></i> Reset
                        </button>
                    </div>
                    <div class="col text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fal fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- DataTable for List DP Pasien --}}
    <div class="row mt-3">
        <div class="col">
            <h4>List DP Pasien</h4>
            <table class="table table-striped table-bordered" id="DownPaymentTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode Pembayaran</th>
                        <th>Nominal</th>
                        <th>Tipe</th>
                        <th>User Input</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data will be populated here using DataTable --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- ==================== DP Pasien ==================== --}}
@section('plugin-down-payment')
    <script>
        $(document).ready(function() {

            // ================================================================
            // FUNGSI BANTUAN (Bisa di-share jika file JS terpisah, atau duplikat di sini)
            // ================================================================

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

            function unformatRupiah(rupiah) {
                return parseFloat(String(rupiah).replace(/\./g, '')) || 0;
            }

            // ================================================================
            // FUNGSI KALKULASI TOTAL DP
            // ================================================================

            /**
             * Menghitung total DP bersih (DP Masuk - DP Refund) dari data DataTable
             * dan mengupdate tampilan di field Total DP.
             */
            function updateTotalDpDisplay() {
                const data = $('#DownPaymentTable').DataTable().rows().data();
                let totalDp = 0;

                data.each(function(d) {
                    const nominal = parseFloat(d.nominal_raw) || 0; // Gunakan kolom mentah untuk kalkulasi
                    if (d.tipe === 'Down Payment') {
                        totalDp += nominal;
                    } else if (d.tipe === 'DP Refund') {
                        totalDp -= nominal;
                    }
                });

                // Update field display dengan format Rupiah
                $('#totalDpDisplay').val(formatRupiah(totalDp));

                // Juga update total DP di tab Tagihan Pasien secara real-time
                $('#totalDp').text('Rp ' + formatRupiah(totalDp));

                // Panggil fungsi kalkulasi total di tab tagihan agar ikut terupdate
                if (typeof recalculateTableTotals === "function") {
                    recalculateTableTotals();
                }
            }


            // ================================================================
            // INISIALISASI DATATABLE
            // ================================================================
            const dpTable = $('#DownPaymentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/down-payment/data/{{ $bilingan->id }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    }, // Kolom nominal yang sudah diformat
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'user_input',
                        name: 'user_input'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-danger btn-sm btn-delete-dp" data-id="${row.id}"><i class="fal fa-trash"></i></button>`;
                        }
                    }
                ],
                "drawCallback": function(settings) {
                    // Panggil fungsi update total setiap kali tabel selesai digambar
                    updateTotalDpDisplay();
                },
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5
            });


            // ================================================================
            // EVENT HANDLERS
            // ================================================================

            // 1. Event handler untuk auto-format Rupiah pada input Nominal
            $('#downPaymentForm input[name="nominal"]').on('input', function(e) {
                let cursorPosition = this.selectionStart,
                    originalLength = this.value.length;
                let value = $(this).val(),
                    unformattedValue = unformatRupiah(value),
                    formattedValue = formatRupiah(unformattedValue);
                $(this).val(formattedValue);
                let newLength = this.value.length;
                cursorPosition = cursorPosition + (newLength - originalLength);
                this.setSelectionRange(cursorPosition, cursorPosition);
            });

            // 2. Event handler untuk submit form DP
            $('#downPaymentForm').on('submit', function(e) {
                e.preventDefault();

                const nominalInput = $(this).find('input[name="nominal"]');
                const nominalValue = unformatRupiah(nominalInput.val()); // Unformat sebelum kirim

                // Kumpulkan data form lainnya
                const formData = {
                    _token: '{{ csrf_token() }}',
                    metode_pembayaran: $(this).find('select[name="metode_pembayaran"]').val(),
                    keterangan: $(this).find('input[name="keterangan"]').val(),
                    nominal: nominalValue, // Kirim nilai numerik murni
                    bilingan_id: '{{ $bilingan->id }}',
                    user_id: '{{ auth()->user()->id }}'
                };

                $.ajax({
                    url: '/simrs/kasir/down-payment',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'DP berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        dpTable.ajax
                            .reload(); // DataTable akan otomatis memanggil updateTotalDpDisplay()
                        $('#downPaymentForm')[0].reset();
                        $('.select2').trigger('change');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan DP.'
                        });
                    }
                });
            });

            // 3. Event handler untuk tombol Hapus DP
            $('#DownPaymentTable tbody').on('click', '.btn-delete-dp', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data DP ini akan dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/simrs/kasir/down-payment/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Terhapus!', 'Data DP berhasil dihapus.',
                                    'success');
                                dpTable.ajax
                                    .reload(); // DataTable akan otomatis memanggil updateTotalDpDisplay()
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

        });
    </script>
@endsection
