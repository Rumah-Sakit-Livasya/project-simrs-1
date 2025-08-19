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
                    Daftar <span class="fw-300"><i>Stock Status</i></span>
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
                                <th>Detail</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Kategori</th>
                                <th>Golongan</th>
                                <th>Stock</th>
                                <th>Nominal</th>
                                <th>Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                @php
                                    $movements = $item->stored_items->sum(function ($storedItem) {
                                        if (request('gudang_id') && request('gudang_id') !== null) {
                                            if ($storedItem->gudang_id != request('gudang_id')) {
                                                return 0;
                                            }
                                        }
                                        if (request('tanggal_end') && request('tanggal_end') !== null) {
                                            if ($storedItem->pbi->created_at > request('tanggal_end')) {
                                                return 0;
                                            }
                                        }
                                        return $storedItem->calculateMovementSince(request('tanggal_end') ?: now());
                                    });

                                    $pre_stock = $item->stored_items->sum(function ($storedItem) {
                                        if (request('gudang_id') && request('gudang_id') !== null) {
                                            if ($storedItem->gudang_id != request('gudang_id')) {
                                                return 0;
                                            }
                                        }
                                        if (request('tanggal_end') && request('tanggal_end') !== null) {
                                            if ($storedItem->pbi->created_at > request('tanggal_end')) {
                                                return 0;
                                            }
                                        }
                                        return $storedItem->qty;
                                    });

                                    $stock = $pre_stock - $movements;

                                    $expired_qty = 0;
                                    foreach ($item->stored_items as $stored) {
                                        if (request('gudang_id') && request('gudang_id') !== null) {
                                            if ($stored->gudang_id !== request('gudang_id')) {
                                                continue;
                                            }
                                        }
                                        if (request('tanggal_end') && request('tanggal_end') !== null) {
                                            if ($stored->pbi->created_at > request('tanggal_end')) {
                                                continue;
                                            }
                                        }
                                        // compare date now() with $stored->pbi->tanggal_exp
                                        $expired = \Carbon\Carbon::parse($stored->pbi->tanggal_exp)->startOfDay();
                                        if ($expired->lt(request('tanggal_end') ?: \Carbon\Carbon::today())) {
                                            $expired_qty += $stored->qty;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Stock Status"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $loop->iteration }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $loop->iteration }}">
                                            @include(
                                                'pages.simrs.farmasi.report.partials.stock-status-detail',
                                                ['item' => $item]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->satuan->nama }}</td>
                                    <td>{{ $item->kategori->nama }}</td>
                                    <td>{{ $item->golongan->nama }}</td>
                                    <td>{{ $stock }}
                                    </td>
                                    <td> {{ rp($stock * $item->hna) }} </td>
                                    <td>{{ $expired_qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Kategori</th>
                                <th>Golongan</th>
                                <th>Stock</th>
                                <th>Nominal</th>
                                <th>Expired</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
