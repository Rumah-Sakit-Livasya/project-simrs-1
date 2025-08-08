<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }
</style>


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
                                <th>Adjustment</th>
                                <th>Stock Akhir</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($logs))
                                @php
                                    $total_qty = $logs->sum('stock.qty');
                                    $count = 0;
                                @endphp
                                @foreach ($logs as $log)
                                    @php
                                        $move_out = false;
                                        $adjustment = $log->after_qty - $log->before_qty;
                                        $final = $total_qty;
                                        $before = $total_qty = $total_qty - $adjustment;
                                        $sign = $adjustment > 0 ? '+' : '';
                                        if (
                                            request('gudang_id') !== null &&
                                            $log->after_gudang_id != request('gudang_id')
                                        ) {
                                            $move_out = true;
                                        }

                                        // from $log->source, find column where it starts with "kode"
                                        // and store its value to $code
                                        $code = '';

                                        foreach ($log->source->getAttributes() as $key => $value) {
                                            if (Str::startsWith($key, 'kode')) {
                                                $code = $value;
                                                break;
                                            }
                                        }

                                        if ($code == '') {
                                            $code = 'Unknown Code';
                                        }
                                    @endphp
                                    @if ($adjustment != 0)
                                        <tr>
                                            <td>{{ ++$count }}</td>
                                            <td>{{ tgl($log->created_at) }}</td>
                                            <td>{{ $code }}</td>
                                            <td>{{ $move_out ? $log->before_gudang->nama : $log->after_gudang->nama }}
                                            </td>
                                            <td>{{ $log->source->keterangan ?? '' }}</td>
                                            <td>{{ $before }}</td>
                                            <td>{{ !$move_out ? $sign : '' }}{{ $move_out ? -$log->before_qty : $adjustment }}
                                            </td>
                                            <td>{{ $move_out ? 0 : $final }}</td>
                                            <td>{{ $log->user->name }}</td>
                                        </tr>
                                    @endif
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
                                <th>Adjustment</th>
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
