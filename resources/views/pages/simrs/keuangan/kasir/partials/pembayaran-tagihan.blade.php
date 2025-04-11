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
        <div class="row">
            <div class="col">
                <div class="card border mb-4 mb-xl-0">
                    <div class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                        <div class="card-title text-white">Wajib Bayar</div>
                    </div>
                    <div class="card-body">
                        <input type="text" class="form-control"
                            value="{{ number_format($bilingan->wajib_bayar ?? 0, 0, ',', '.') }}"
                            placeholder="Total Tagihan Pasien" readonly />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border mb-4 mb-xl-0">
                    <div class="card-header bg-trans-gradient py-2 pr-2 d-flex align-items-center flex-wrap">
                        <div class="card-title text-white">DP Pasien</div>
                    </div>
                    <div class="card-body">
                        <input type="text" class="form-control"
                            value="{{ number_format($bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') : 0) : 0, 0, ',', '.') }}"
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
                        <input type="text" class="form-control"
                            value="{{ number_format(($bilingan->wajib_bayar ?? 0) - ($bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') : 0) : 0), 0, ',', '.') }}"
                            placeholder="Masukkan Sisa Tagihan" readonly />
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
                        <input type="text" class="form-control" id="tunai" placeholder="Masukkan Tunai"
                            onkeyup="updateTotalBayarAndKembalian(this)" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border mb-4 mb-xl-0">
                    <div class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                        <div class="card-title text-white">Total Bayar</div>
                    </div>
                    <div class="card-body">
                        <input type="text" class="form-control" id="totalBayar" placeholder="Masukkan Total Bayar"
                            onkeyup="updateKembalian()" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border mb-4 mb-xl-0">
                    <div class="card-header bg-success py-2 pr-2 d-flex align-items-center flex-wrap">
                        <div class="card-title text-white">Kembalian</div>
                    </div>
                    <div class="card-body">
                        <input type="text" class="form-control" id="kembalian" placeholder="Masukkan Kembalian"
                            readonly />
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
                                                    <select class="form-select select2"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                        <option></option>
                                                        <option>MANDIRI</option>
                                                        <option>BCA</option>
                                                        <option>BNI</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select select2"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                        <option></option>
                                                        <option>Debit Card</option>
                                                        <option>Credit Card</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><select class="form-select select2"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                        <option></option>
                                                        <option>MANDIRI</option>
                                                        <option>BCA</option>
                                                        <option>BNI</option>
                                                    </select></td>
                                                <td><select class="form-select select2"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                        <option></option>
                                                        <option>Debit Card</option>
                                                        <option>Credit Card</option>
                                                    </select></td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><select class="form-select select2"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                        <option></option>
                                                        <option>MANDIRI</option>
                                                        <option>BCA</option>
                                                        <option>BNI</option>
                                                    </select></td>
                                                <td><select class="form-select select2"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                        <option></option>
                                                        <option>Debit Card</option>
                                                        <option>Credit Card</option>
                                                    </select></td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        onkeyup="updateTotalBayarAndKembalian(this)">
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
                                            <label class="form-label">Nominal
                                                Transfer</label>
                                            <input type="text" class="form-control"
                                                onkeyup="updateTotalBayarAndKembalian(this)">
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <label class="form-label">No. Rek
                                                Pengirim</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <label class="form-label">Keterangan / Agunan</label>
                <textarea class="form-control" rows="4"></textarea>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-primary ms-2">
                    <i class="fas fa-money-bill-alt"></i> Bayar
                </button>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col">
                <h4>List Billing Pasien</h4>
                <table class="table table-striped table-bordered" id="bilinganTable">
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
