<div class="panel">
    <div class="panel-hdr">
        <h2>Hasil Laporan Kartu Stok</h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            @if (isset($logs))
                <div class="alert alert-info">
                    Menampilkan laporan untuk <strong>{{ $barang->nama }} ([{{ $satuan->nama }}])</strong>.
                    Saldo Awal pada periode ini: <strong>{{ $stokAwal ?? 0 }}</strong>.
                </div>
            @endif
            <table id="dt-kartu-stok" class="table table-bordered table-hover table-striped w-100">
                <thead class="bg-primary-600">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Gudang</th>
                        <th>Keterangan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stok Akhir</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($logs)
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Carbon\Carbon::parse($log->created_at)->isoFormat('D MMM Y, HH:mm') }}</td>
                                <td>{{ $log->kode_transaksi }}</td>
                                <td>{{ $log->gudang_transaksi }}</td>
                                <td>{{ $log->source->keterangan ?? $log->keterangan }}</td>
                                <td>{{ $log->adjustment > 0 ? $log->adjustment : '-' }}</td>
                                <td>{{ $log->adjustment < 0 ? abs($log->adjustment) : '-' }}</td>
                                <td><strong>{{ $log->stok_akhir }}</strong></td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
