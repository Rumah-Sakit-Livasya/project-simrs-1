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
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
            <div class="col">
                <label>Nominal:</label>
                <input type="text" class="form-control" name="nominal" placeholder="Masukkan Nominal">
            </div>
            <div class="col">
                <label>Keterangan:</label>
                <input type="text" class="form-control" name="keterangan" placeholder="Masukkan Keterangan">
            </div>
            <div class="col">
                <label>Total DP:</label>
                <input type="text" class="form-control" name="total_dp"
                    value="{{ $bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') - $bilingan->down_payment->where('tipe', 'DP Refund')->sum('nominal') : '0') : '0' }}"
                    readonly>
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
            $('#DownPaymentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/down-payment/data/{{ $bilingan->id }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json && json.data ? json.data : [];
                    }
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
                    },
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
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5
            });
        });
    </script>
@endsection
