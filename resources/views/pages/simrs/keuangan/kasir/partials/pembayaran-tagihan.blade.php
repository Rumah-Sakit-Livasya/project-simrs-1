<div class="tab-pane fade" id="pembayaran-tagihan" role="tabpanel">
    {{-- Bagian Header Info Pasien (Tidak Berubah) --}}
    <div class="row mb-3">
        <div class="col"><label>No Registrasi:</label><input type="text" class="form-control"
                value="{{ $bilingan->registration->registration_number ?? 'N/A' }}" readonly></div>
        <div class="col"><label>Tgl:</label><input type="text" class="form-control"
                value="{{ $bilingan->created_at ?? 'N/A' }}" readonly></div>
        <div class="col"><label>Tipe Kunjungan:</label><input type="text" class="form-control"
                value="{{ ucwords(str_replace('-', ' ', $bilingan->registration->registration_type ?? 'N/A')) }}"
                readonly></div>
        <div class="col"><label>Nama Pasien:</label><input type="text" class="form-control"
                value="{{ $bilingan->registration->patient->name ?? 'N/A' }}" readonly></div>
        <div class="col"><label>RM:</label><input type="text" class="form-control"
                value="{{ $bilingan->registration->patient->medical_record_number ?? 'N/A' }}" readonly></div>
    </div>

    {{-- Form Pembayaran akan muncul hanya jika status final dan belum lunas --}}
    @if ($bilingan->status == 'final' && !$bilingan->status_lunas)
        <form id="pembayaranTagihan">
            {{-- Baris 1: Informasi Tagihan --}}
            <div class="row">
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-trans-gradient py-2">
                            <div class="card-title text-white">Wajib Bayar</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control format-rupiah" id="wajibBayar" name="wajib_bayar"
                                value="{{ $bilingan->wajib_bayar ?? 0 }}" readonly />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-trans-gradient py-2">
                            <div class="card-title text-white">DP Pasien</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control format-rupiah" id="dpPasien" name="dp_pasien"
                                value="{{ $bilingan->total_dp ?? 0 }}" readonly />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-trans-gradient py-2">
                            <div class="card-title text-white">Sisa Tagihan</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control format-rupiah" id="sisaTagihan"
                                name="sisa_tagihan" readonly />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baris 2: Informasi Pembayaran --}}
            <div class="row mt-3">
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-success py-2">
                            <div class="card-title text-white">Tunai</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control format-rupiah payment-input" id="tunai"
                                name="tunai" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-success py-2">
                            <div class="card-title text-white">Total Bayar</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control format-rupiah" id="totalBayar" name="total_bayar"
                                readonly />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-success py-2">
                            <div class="card-title text-white">Kembalian</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control format-rupiah" id="kembalian" name="kembalian"
                                readonly />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baris 3: Metode Pembayaran Lainnya (Accordion) --}}
            <div class="row mt-3">
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-warning py-2" data-toggle="collapse" data-target="#paymentMethods"
                            style="cursor: pointer;">
                            <div class="card-title text-white text-center">Pembayaran Metode Lainnya <i
                                    class="fal fa-chevron-down ml-2"></i></div>
                        </div>
                        <div class="collapse" id="paymentMethods">
                            <div class="card-body">
                                {{-- Credit/Debit Card --}}
                                <div class="card mb-3">
                                    <div class="card-header">Credit / Debit Card</div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Mesin EDC</th>
                                                    <th>Tipe</th>
                                                    <th>Card Number</th>
                                                    <th>Auth Number</th>
                                                    <th>Batch</th>
                                                    <th>Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < 3; $i++)
                                                    <tr>
                                                        <td>
                                                            <select class="form-control select2 mesin-edc"
                                                                name="bank_perusahaan_id_cc[]">
                                                                <option value=""></option>
                                                                @foreach (\App\Models\BankPerusahaan::all() as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control select2" name="tipe_cc[]"
                                                                disabled>
                                                                <option></option>
                                                                <option>Debit Card</option>
                                                                <option>Credit Card</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control"
                                                                name="cc_number_cc[]" disabled></td>
                                                        <td><input type="text" class="form-control"
                                                                name="auth_number_cc[]" disabled></td>
                                                        <td><input type="text" class="form-control"
                                                                name="batch_cc[]" disabled></td>
                                                        <td><input type="text"
                                                                class="form-control nominal-input payment-input format-rupiah"
                                                                name="nominal_cc[]" disabled></td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Via Transfer Bank --}}
                                <div class="card mb-3">
                                    <div class="card-header">Via Transfer Bank</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6"><label class="form-label">Bank RS</label><select
                                                    class="form-control select2" name="bank_perusahaan_id_tf">
                                                    <option value=""></option>
                                                    @foreach (\App\Models\BankPerusahaan::all() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}
                                                        </option>
                                                    @endforeach
                                                </select></div>
                                            <div class="col-md-6"><label class="form-label">Bank
                                                    Pengirim</label><input type="text" class="form-control"
                                                    name="bank_pengirim_tf"></div>
                                            <div class="col-md-6 mt-2"><label class="form-label">Nominal
                                                    Transfer</label><input type="text" name="nominal_tf"
                                                    class="form-control nominal-input payment-input format-rupiah">
                                            </div>
                                            <div class="col-md-6 mt-2"><label class="form-label">No. Rek
                                                    Pengirim</label><input type="text" class="form-control"
                                                    name="norek_pengirim_tf"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baris 4: Keterangan & Tombol Aksi --}}
            <div class="row">
                <div class="col mt-3">
                    <label class="form-label">Keterangan / Agunan</label>
                    <textarea class="form-control" rows="4" name="keterangan"></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <a href="{{ route('tagihan.pasien.index') }}" class="btn btn-secondary"><i
                            class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                <div class="col text-right">
                    <button type="submit" class="btn btn-primary" id="btn-bayar">
                        <i class="fas fa-money-bill-alt me-1"></i> Bayar
                    </button>
                </div>
            </div>
        </form>
    @else
        {{-- Tampilan jika sudah lunas atau status bukan 'final' --}}
        <div class="row">
            <div class="col">
                <h4>List Pembayaran Pasien</h4>
                <table class="table table-striped table-bordered table-sm" id="bilinganTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Total Tagihan</th>
                            <th>Jaminan</th>
                            <th>Tagihan Pasien</th>
                            <th>Jumlah Terbayar</th>
                            <th>Sisa Tagihan</th>
                            <th>Kembalian</th>
                            <th>Print</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@section('plugin-pembayaran-tagihan')
    <script>
        // ================================================================
        // FUNGSI BANTUAN (HELPERS)
        // ================================================================

        /**
         * Mengubah angka menjadi format Rupiah (misal: 150000 -> "150.000")
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
         */
        function unformatRupiah(rupiah) {
            return parseFloat(String(rupiah).replace(/\./g, '')) || 0;
        }


        $(document).ready(function() {

            // Jalankan kode ini HANYA jika form pembayaran ada di halaman
            if ($('#pembayaranTagihan').length) {
                // ================================================================
                // FUNGSI KALKULASI UTAMA PEMBAYARAN
                // ================================================================
                function recalculatePembayaran() {
                    const wajibBayar = unformatRupiah($('#wajibBayar').val());
                    const dpPasien = unformatRupiah($('#dpPasien').val());

                    let totalPembayaran = 0;
                    $('.payment-input').each(function() {
                        totalPembayaran += unformatRupiah($(this).val());
                    });

                    const totalBayar = totalPembayaran;
                    const sisaTagihan = Math.max(0, wajibBayar - dpPasien - totalBayar);
                    const kembalian = Math.max(0, totalBayar + dpPasien - wajibBayar);

                    $('#sisaTagihan').val(formatRupiah(sisaTagihan));
                    $('#totalBayar').val(formatRupiah(totalBayar));
                    $('#kembalian').val(formatRupiah(kembalian));
                }


                // ================================================================
                // EVENT HANDLERS
                // ================================================================

                // 1. Event handler untuk AUTO-FORMAT RUPIAH saat mengetik
                $('#pembayaranTagihan').on('input', '.format-rupiah', function(e) {
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

                // 2. Event handler utama untuk kalkulasi real-time
                $('#pembayaranTagihan').on('input', '.payment-input', function() {
                    recalculatePembayaran();
                });

                // 3. FUNGSI LAMA ANDA (DOUBLE-CLICK) DIINTEGRASIKAN DI SINI
                $('#pembayaranTagihan').on('dblclick', '.payment-input', function() {
                    const sisaTagihan = unformatRupiah($('#sisaTagihan').val());
                    const currentValue = unformatRupiah($(this).val());

                    if (sisaTagihan > 0) {
                        // Logikanya diubah sedikit agar lebih baik:
                        // Menambahkan sisa tagihan ke nilai yang sudah ada di input.
                        $(this).val(formatRupiah(currentValue + sisaTagihan));
                        // Picu kalkulasi ulang agar semua field terupdate
                        recalculatePembayaran();
                    }
                });

                // 4. FUNGSI LAMA ANDA (TOGGLE INPUT CC) DIINTEGRASIKAN DI SINI
                $('.mesin-edc').on('change', function() {
                    const row = $(this).closest('tr');
                    const isSelected = $(this).val() && $(this).val().trim() !== '';

                    row.find('input, select').not(this).prop('disabled', !isSelected);

                    if (!isSelected) {
                        row.find('input').val('');
                        // Reset select2
                        row.find('select').not(this).val(null).trigger('change');
                    }
                    // Selalu hitung ulang jika ada perubahan
                    recalculatePembayaran();
                }).trigger('change'); // Jalankan saat load untuk disable by default

                // 5. FUNGSI LAMA ANDA (SUBMIT FORM) DIINTEGRASIKAN DI SINI
                $('#pembayaranTagihan').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const bayarBtn = form.find('#btn-bayar');

                    bayarBtn.prop('disabled', true).html(
                        `<span class="spinner-border spinner-border-sm"></span> Memproses...`);

                    let formData = form.serializeArray();
                    let dataToSend = {};

                    // Proses dan unformat data sebelum dikirim ke server
                    $.each(formData, function(i, field) {
                        let value = field.value;
                        // Cek elemen berdasarkan nama atau class untuk un-formatting
                        if (form.find(`[name="${field.name}"]`).hasClass('format-rupiah')) {
                            value = unformatRupiah(value);
                        }

                        // Handle array fields (untuk CC)
                        if (field.name.endsWith('[]')) {
                            let name = field.name.slice(0, -2);
                            if (!dataToSend[name]) dataToSend[name] = [];
                            dataToSend[name].push(value);
                        } else {
                            dataToSend[field.name] = value;
                        }
                    });

                    // Tambahkan data yang tidak ada di form secara eksplisit
                    dataToSend.bilingan_id = '{{ $bilingan->id }}';
                    dataToSend.user_id = '{{ auth()->user()->id }}';
                    dataToSend.jumlah_terbayar = unformatRupiah($('#totalBayar').val());
                    dataToSend.sisa_tagihan = unformatRupiah($('#sisaTagihan').val());
                    dataToSend.kembalian = unformatRupiah($('#kembalian').val());

                    let now = new Date();
                    let formattedDate =
                        `${now.getDate()} ${now.toLocaleString('default', { month: 'short' })} ${now.getFullYear()} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
                    dataToSend.bill_notes = `Lunas, Tgl Bayar: ${formattedDate}`;

                    $.ajax({
                        url: '/simrs/kasir/pembayaran-tagihan',
                        type: 'POST',
                        data: dataToSend,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pembayaran berhasil disimpan.',
                                    showConfirmButton: false,
                                    timer: 2000
                                })
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON.message ||
                                    'Terjadi kesalahan saat menyimpan pembayaran.'
                            });
                            bayarBtn.prop('disabled', false).html(
                                '<i class="fas fa-money-bill-alt me-1"></i> Bayar');
                        }
                    });
                });

                // ================================================================
                // INISIALISASI SAAT HALAMAN DIMUAT
                // ================================================================

                // 1. Ambil elemen input
                const wajibBayarInput = $('#wajibBayar');
                const dpPasienInput = $('#dpPasien');

                // 2. Ambil nilai mentahnya (yang mungkin tidak terformat)
                const nilaiWajibBayar = wajibBayarInput.val();
                const nilaiDpPasien = dpPasienInput.val();

                // 3. Format nilainya dan set kembali ke input
                wajibBayarInput.val(formatRupiah(nilaiWajibBayar));
                dpPasienInput.val(formatRupiah(nilaiDpPasien));

                // 4. Panggil fungsi kalkulasi utama untuk menghitung 'Sisa Tagihan' awal
                recalculatePembayaran();
            }

            // ================================================================
            // DATATABLE UNTUK TAMPILAN SETELAH BAYAR (JIKA ADA)
            // ================================================================
            if ($('#bilinganTable').length) {
                $('#bilinganTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/simrs/kasir/bilingan/data/{{ $bilingan->id }}',
                        type: 'GET'
                    },
                    columns: [{
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'total_tagihan',
                            name: 'total_tagihan',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'jaminan',
                            name: 'jaminan',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'tagihan_pasien',
                            name: 'tagihan_pasien',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'jumlah_terbayar',
                            name: 'jumlah_terbayar',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'sisa_tagihan',
                            name: 'sisa_tagihan',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'kembalian',
                            name: 'kembalian',
                            render: function(data) {
                                return formatRupiah(data);
                            }
                        },
                        {
                            data: 'print',
                            name: 'print'
                        }
                    ],
                    // ... (sisa opsi datatable)
                });

                // Event handler untuk tombol print
                $(document).on('click', '.btn-print-bill, .btn-print-kwitansi', function(e) {
                    e.preventDefault();
                    let billingId = $(this).data('billing-id');
                    let url = $(this).hasClass('btn-print-bill') ?
                        `/simrs/kasir/print-bill/${billingId}` :
                        `/simrs/kasir/print-kwitansi/${billingId}`;
                    window.open(url, 'popupWindow',
                        'toolbar=yes,scrollbars=yes,resizable=yes,fullscreen=yes');
                });
            }
        });
    </script>
@endsection
