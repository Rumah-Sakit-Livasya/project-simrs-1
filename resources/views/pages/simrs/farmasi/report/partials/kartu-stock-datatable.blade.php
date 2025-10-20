<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Kartu Stok</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        <thead class="bg-primary-600">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Gudang</th>
                                <th>Keterangan</th>
                                <th>Stock Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Stock Akhir</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($logs) && isset($saldo_awal))
                                @php
                                    $saldo_berjalan = $saldo_awal;
                                    $count = 0;
                                @endphp
                                <!-- Baris untuk Saldo Awal -->
                                <tr class="table-info">
                                    <td colspan="5" class="text-center fw-bold">SALDO AWAL</td>
                                    <td class="fw-bold">{{ number_format($saldo_awal) }}</td>
                                    <td colspan="2"></td>
                                    <td class="fw-bold">{{ number_format($saldo_awal) }}</td>
                                    <td></td>
                                </tr>

                                @foreach ($logs as $log)
                                    @php
                                        $stock_awal_baris = $saldo_berjalan;
                                        $adjustment = 0;
                                        $gudang_id_filter = request('gudang_id');

                                        if ($gudang_id_filter) {
                                            if ($log->after_gudang_id == $gudang_id_filter) {
                                                $adjustment = $log->after_qty - $log->before_qty;
                                            }
                                            if (
                                                $log->before_gudang_id == $gudang_id_filter &&
                                                $log->after_gudang_id != $gudang_id_filter
                                            ) {
                                                $adjustment = -$log->before_qty;
                                            }
                                        } else {
                                            $adjustment = $log->after_qty - $log->before_qty;
                                        }

                                        if ($adjustment == 0) {
                                            continue;
                                        }

                                        $stock_akhir_baris = $stock_awal_baris + $adjustment;
                                        $saldo_berjalan = $stock_akhir_baris;

                                        $qty_masuk = $adjustment > 0 ? $adjustment : 0;
                                        $qty_keluar = $adjustment < 0 ? abs($adjustment) : 0;

                                        // *** PERBAIKAN DENGAN HELPER OPTIONAL() ***
                                        $code = 'N/A';
                                        $source_attributes = optional($log->source)->getAttributes(); // Tidak akan error jika $log->source null

                                        if (is_array($source_attributes)) {
                                            foreach ($source_attributes as $key => $value) {
                                                if (str_starts_with($key, 'kode') && !is_null($value)) {
                                                    $code = $value;
                                                    break;
                                                }
                                            }
                                        }

                                        $keterangan =
                                            optional($log->source)->keterangan ??
                                            ($log->keterangan ?? 'Tanpa Keterangan');
                                        $nama_user = optional($log->user)->name ?? 'User Tidak Dikenal';

                                        $gudang_tampil = 'N/A';
                                        if ($adjustment < 0) {
                                            $gudang_tampil =
                                                optional($log->before_gudang)->nama ?? 'Gudang Asal Dihapus';
                                        } else {
                                            $gudang_tampil =
                                                optional($log->after_gudang)->nama ?? 'Gudang Tujuan Dihapus';
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                        <td>{{ $code }}</td>
                                        <td>{{ $gudang_tampil }}</td>
                                        <td>{{ $keterangan }}</td>
                                        <td>{{ number_format($stock_awal_baris) }}</td>
                                        <td class="text-success">
                                            {{ $qty_masuk > 0 ? '+' . number_format($qty_masuk) : 0 }}</td>
                                        <td class="text-danger">
                                            {{ $qty_keluar > 0 ? '-' . number_format($qty_keluar) : 0 }}</td>
                                        <td class="fw-bold">{{ number_format($stock_akhir_baris) }}</td>
                                        <td>{{ $nama_user }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Gudang</th>
                                <th>Keterangan</th>
                                <th>Stock Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Stock Akhir</th>
                                <th>User</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
