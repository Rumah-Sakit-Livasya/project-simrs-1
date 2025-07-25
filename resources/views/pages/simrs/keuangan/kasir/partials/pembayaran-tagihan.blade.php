<div class="tab-pane fade" id="pembayaran-tagihan" role="tabpanel">
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

    @if ($bilingan->status == 'final' && !$bilingan->is_paid)
        <form id="pembayaranTagihan">
            <div class="row">
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                            <div class="card-title text-white">Wajib Bayar</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control" id="wajibBayar" name="wajib_bayar"
                                value="{{ $bilingan->wajib_bayar ?? 0 }}" placeholder="Total Tagihan Pasien" readonly />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                            <div class="card-title text-white">DP Pasien</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control" id="dpPasien" name="dp_pasien"
                                value="{{ $bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') : 0) : 0 }}"
                                placeholder="Masukkan DP Pasien" readonly />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                            <div class="card-title text-white">Sisa Tagihan</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control" id="sisaTagihan" name="sisa_tagihan"
                                value="{{ ($bilingan->wajib_bayar ?? 0) - ($bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') : 0) : 0) }}"
                                placeholder="Masukkan Sisa Tagihan" disabled />

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                            <div class="card-title text-white">Tunai</div>
                        </div>
                        <div class="card-body">
                            <input type="number" class="form-control" id="tunai" name="tunai" data-payment
                                onwheel="this.blur()" onkeyup="updateTotalBayarAndKembalian(this)"
                                ondblclick="isiDenganSisaTagihan(this)">

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                            <div class="card-title text-white">Total Bayar</div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control" id="totalBayar" name="total_bayar"
                                placeholder="Masukkan Total Bayar" onkeyup="updateKembalian()" />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                            <div class="card-title text-white">Kembalian</div>
                        </div>
                        <div class="card-body">
                            <input type="number" min="0" class="form-control" id="kembalian" name="kembalian"
                                onwheel="this.blur()" placeholder="Masukkan Kembalian" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="card border mb-4 mb-xl-0">
                        <div class="card-header bg-warning py-2 pr-2 d-flex align-items-center1 flex-wrap"
                            data-toggle="collapse" data-target="#paymentMethods" aria-expanded="false"
                            aria-controls="paymentMethods">
                            <div class="card-title text-white text-center">Pembayaran Metode
                                Lainnya</div>
                        </div>
                        <div class="collapse" id="paymentMethods">
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-header">Credit Card</div>
                                    <div class="card-body">
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
                                                        <select class="form-select select2 mesin-edc"
                                                            name="bank_perusahaan_id_cc[]"
                                                            onchange="toggleInputs(this)">
                                                            <option value=""></option>
                                                            @foreach (\App\Models\BankPerusahaan::all() as $item)
                                                                <option value="{{ $item->id }}">{{ $item->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-select select2" name="tipe_cc[]"
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                            <option></option>
                                                            <option>Debit Card</option>
                                                            <option>Credit Card</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control cc-number"
                                                            name="cc_number_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control auth-number"
                                                            name="auth_number_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control batch-number"
                                                            name="batch_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control nominal-input"
                                                            name="nominal_cc[]" disabled data-payment
                                                            onwheel="this.blur()"
                                                            onkeyup="updateTotalBayarAndKembalian(this)"
                                                            ondblclick="isiDenganSisaTagihan(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="form-select select2 mesin-edc"
                                                            name="bank_perusahaan_id_cc[]"
                                                            onchange="toggleInputs(this)">
                                                            <option value=""></option>
                                                            @foreach (\App\Models\BankPerusahaan::all() as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><select class="form-select select2" name="tipe_cc[]"
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                            <option></option>
                                                            <option>Debit Card</option>
                                                            <option>Credit Card</option>
                                                        </select></td>
                                                    <td>
                                                        <input type="text" class="form-control cc-number" disabled
                                                            name="cc_number_cc[]"
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control auth-number"
                                                            name="auth_number_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control batch-number"
                                                            name="batch_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control nominal-input"
                                                            name="nominal_cc[]" disabled data-payment
                                                            onwheel="this.blur()"
                                                            onkeyup="updateTotalBayarAndKembalian(this)"
                                                            ondblclick="isiDenganSisaTagihan(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="form-select select2 mesin-edc"
                                                            name="bank_perusahaan_id_cc[]"
                                                            onchange="toggleInputs(this)">
                                                            <option value=""></option>
                                                            @foreach (\App\Models\BankPerusahaan::all() as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><select class="form-select select2" name="tipe_cc[]"
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                            <option></option>
                                                            <option>Debit Card</option>
                                                            <option>Credit Card</option>
                                                        </select></td>
                                                    <td>
                                                        <input type="text" class="form-control cc-number" disabled
                                                            name="cc_number_cc[]"
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control auth-number"
                                                            name="auth_number_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control batch-number"
                                                            name="batch_cc[]" disabled
                                                            onkeyup="updateTotalBayarAndKembalian(this)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control nominal-input"
                                                            name="nominal_cc[]" disabled data-payment
                                                            onwheel="this.blur()"
                                                            onkeyup="updateTotalBayarAndKembalian(this)"
                                                            ondblclick="isiDenganSisaTagihan(this)">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header">Via Transfer Bank</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Bank RS</label>
                                                <select class="form-select select2" name="bank_perusahaan_id_tf">
                                                    <option value=""></option>
                                                    @foreach (\App\Models\BankPerusahaan::all() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Bank Pengirim</label>
                                                <input type="text" class="form-control" name="bank_pengirim_tf">
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <label class="form-label">Nominal
                                                    Transfer</label>
                                                <input type="number" min="0" name="nominal_tf"
                                                    class="form-control nominal-input" data-payment
                                                    onwheel="this.blur()" onkeyup="updateTotalBayarAndKembalian(this)"
                                                    ondblclick="isiDenganSisaTagihan(this)">
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <label class="form-label">No. Rek
                                                    Pengirim</label>
                                                <input type="text" class="form-control" name="norek_pengirim_tf">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="card mb-3">
                                    <div class="card-header">Ditanggung Dokter</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Dokter</label>
                                                <select class="form-select select2">
                                                    <option>Dr. A</option>
                                                    <option>Dr. B</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nominal Dijamin
                                                    Dokter</label>
                                                <input type="text" class="form-control"
                                                    onkeyup="updateTotalBayarAndKembalian(this)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">Ditanggung Karyawan</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Karyawan</label>
                                                <select class="form-select select2">
                                                    <option>Karyawan A</option>
                                                    <option>Karyawan B</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nominal Dijamin
                                                    Karyawan</label>
                                                <input type="text" class="form-control"
                                                    onkeyup="updateTotalBayarAndKembalian(this)">
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col mt-3">
                    <label class="form-label">Keterangan / Agunan</label>
                    <textarea class="form-control" rows="4" name="keterangan"></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>
                <div class="col text-right">
                    <button type="submit" class="btn btn-primary ms-2" id="btn-bayar">
                        <i class="fas fa-money-bill-alt me-1"></i> Bayar
                    </button>
                </div>
            </div>

        </form>
    @else
        <div class="row">
            <div class="col">
                <h4>List Billing Pasien</h4>
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
        function toggleInputs(selectEl) {
            const row = selectEl.closest('tr');

            const ccInput = row.querySelector('.cc-number');
            const authInput = row.querySelector('.auth-number');
            const batchInput = row.querySelector('.batch-number');
            const nominalInput = row.querySelector('.nominal-input');

            const isSelected = selectEl.value.trim() !== '';

            [ccInput, authInput, batchInput, nominalInput].forEach(input => {
                input.disabled = !isSelected;
                if (!isSelected) input.value = '';
            });
        }

        // Saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edc-select').forEach(select => {
                toggleInputs(select); // jalankan agar semua input disable kalau belum ada value
            });

            // DP Pasien = Total Bayar
            const dpPasien = document.getElementById('dpPasien');
            const totalBayar = document.getElementById('totalBayar');

            if (dpPasien && totalBayar) {
                totalBayar.value = dpPasien.value;
            }

            // Button Bayar
            const bayarBtn = document.getElementById('btn-bayar');
            const form = bayarBtn.closest('form');
            form.addEventListener('submit', function(e) {
                // Disable button
                bayarBtn.disabled = true;
                // Ganti isi button jadi loading
                bayarBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...`;
            });
        });

        function updateTotalBayarAndKembalian(inputElement) {
            const wajibBayar = Number($('#wajibBayar').val()) || 0;
            const dpPasien = Number($('#dpPasien').val()) || 0;
            const tunai = Number($('#tunai').val()) || 0;
            let totalBayar = dpPasien;

            // Jumlahkan semua input dengan data-payment
            $('input[data-payment]').each(function() {
                totalBayar += Number($(this).val()) || 0;
            });

            $('#totalBayar').val(totalBayar);

            // Hitung kembalian, tapi pastikan minimal 0
            const kembalian = Math.max(0, totalBayar - wajibBayar);
            $('#kembalian').val(kembalian);

            // Hitung dan tampilkan sisa tagihan, minimal 0
            const sisaTagihan = Math.max(0, wajibBayar - totalBayar - dpPasien);
            $('#sisaTagihan').val(sisaTagihan);
        }

        function isiDenganSisaTagihan(inputElement) {
            const sisaTagihan = Number($('#sisaTagihan').val()) || 0;

            if (sisaTagihan <= 0) return;

            inputElement.value = sisaTagihan;

            updateTotalBayarAndKembalian(inputElement);
        }


        $(document).ready(function() {
            if ($('#bilinganTable').length) {
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
                                return '<input readonly type="text" class="form-control edit-input" value="' +
                                    data + '" data-column="tanggal" data-id="' + row.id +
                                    '" style="width: auto; max-width: 100%; white-space: nowrap;">';
                            }
                        },
                        {
                            data: 'total_tagihan',
                            name: 'total_tagihan',
                            className: 'total-tagihan',
                            render: function(data, type, row) {
                                return '<input readonly type="text" class="form-control edit-input format-currency" value="' +
                                    (data !== null ? parseFloat(data).toLocaleString('id-ID') :
                                        '0') +
                                    '" data-column="total_tagihan" data-id="' + row.id + '">';
                            }
                        },
                        {
                            data: 'jaminan',
                            name: 'jaminan',
                            className: 'jaminan',
                            render: function(data, type, row) {
                                return '<input readonly type="text" class="form-control edit-input format-currency" value="' +
                                    (data !== null ? parseFloat(data).toLocaleString('id-ID') :
                                        '0') +
                                    '" data-column="jaminan" data-id="' + row.id + '">';
                            }
                        },
                        {
                            data: 'tagihan_pasien',
                            name: 'tagihan_pasien',
                            className: 'tagihan-pasien',
                            render: function(data, type, row) {
                                return '<input readonly type="text" class="form-control edit-input format-currency" value="' +
                                    (data !== null ? parseFloat(data).toLocaleString('id-ID') :
                                        '0') +
                                    '" data-column="tagihan_pasien" data-id="' + row.id + '">';
                            }
                        },
                        {
                            data: 'jumlah_terbayar',
                            name: 'jumlah_terbayar',
                            className: 'jumlah-terbayar',
                            render: function(data, type, row) {
                                return '<input readonly type="text" class="form-control edit-input format-currency" value="' +
                                    (data !== null ? parseFloat(data).toLocaleString('id-ID') :
                                        '0') +
                                    '" data-column="jumlah_terbayar" data-id="' + row.id + '">';
                            }
                        },
                        {
                            data: 'sisa_tagihan',
                            name: 'sisa_tagihan',
                            className: 'sisa-tagihan',
                            render: function(data, type, row) {
                                return '<input readonly type="text" class="form-control edit-input format-currency" value="' +
                                    (data !== null ? parseFloat(data).toLocaleString('id-ID') :
                                        '0') +
                                    '" data-column="sisa_tagihan" data-id="' + row.id + '">';
                            }
                        },
                        {
                            data: 'kembalian',
                            name: 'kembalian',
                            className: 'kembalian',
                            render: function(data, type, row) {
                                return '<input readonly type="text" class="form-control edit-input format-currency" value="' +
                                    (data !== null ? parseFloat(data).toLocaleString('id-ID') :
                                        '0') +
                                    '" data-column="kembalian" data-id="' + row.id + '">';
                            }
                        },
                        {
                            data: 'print',
                            name: 'print',
                            className: 'no-wrap',
                            render: function(data, type, row) {
                                return '<div style="white-space: nowrap;">' + data + '</div>';
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
                    className: 'table-sm'
                });
            }

            $('#pembayaranTagihan').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let wajibBayar = parseFloat($('#wajibBayar').val().replace(/\./g, '').replace(',', '.'));
                let dpPasien = parseFloat($('#dpPasien').val().replace(/\./g, '').replace(',', '.'));
                let sisaTagihan = parseFloat($('#sisaTagihan').val().replace(/\./g, '').replace(',', '.'));
                let kembalian = parseFloat($('#kembalian').val().replace(/\./g, '').replace(',', '.'));
                let totalTagihan = wajibBayar - dpPasien;
                const userId = '{{ auth()->user()->id }}';
                const bilinganId = '{{ $bilingan->id }}';
                const jumlahTerbayar = parseFloat($('#totalBayar').val().replace(/\./g, '').replace(',',
                    '.'));
                let tagihanPasien = parseFloat($('#wajibBayar').val().replace(/\./g, '').replace(',', '.'));
                let now = new Date();
                let day = now.getDate();
                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
                    "Nov", "Dec"
                ];
                let month = monthNames[now.getMonth()];
                let year = now.getFullYear();
                let hours = now.getHours().toString().padStart(2, '0');
                let minutes = now.getMinutes().toString().padStart(2, '0');
                let seconds = now.getSeconds().toString().padStart(2, '0');
                let formattedDate = `${day} ${month} ${year} ${hours}:${minutes}:${seconds}`;
                let billNotes = `Lunas, Tgl Bayar: ${formattedDate}`;

                formData +=
                    `&bilingan_id=${bilinganId}&user_id=${userId}&jumlah_terbayar=${jumlahTerbayar}&total_tagihan=${totalTagihan}&tagihan_pasien=${tagihanPasien}&kembalian=${kembalian}&sisa_tagihan=${sisaTagihan}&bill_notes=${billNotes}`;

                $.ajax({
                    url: '/simrs/kasir/pembayaran-tagihan',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        showSuccessAlert('Pembayaran berhasil disimpan.');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(message);
                    }
                });
            });

            $(document).on('click', '.btn-print-bill', function(e) {
                e.preventDefault();
                let billingId = $(this).data('billing-id');
                let url = billingId ? `/simrs/kasir/print-bill/${billingId}` : '/simrs/kasir/print-bill';
                window.open(url, 'popupWindow',
                    'toolbar=yes,scrollbars=yes,resizable=yes,fullscreen=yes,top=0,left=0,width=' +
                    screen.width + ',height=' + screen.height);
            });

            $(document).on('click', '.btn-print-kwitansi', function(e) {
                e.preventDefault();
                let billingId = $(this).data('billing-id');
                let url = billingId ? `/simrs/kasir/print-kwitansi/${billingId}` :
                    '/simrs/kasir/print-kwitansi';
                window.open(url, 'popupWindow',
                    'toolbar=yes,scrollbars=yes,resizable=yes,fullscreen=yes,top=0,left=0,width=' +
                    screen.width + ',height=' + screen.height);
            });
        });
    </script>
@endsection
