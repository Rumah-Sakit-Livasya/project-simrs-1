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
                            @foreach ($sas as $sa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Stock Status"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $sa->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $sa->id }}">
                                            @include(
                                                'pages.simrs.warehouse.revaluasi-stock.stock-adjustment.partials.sa-detail',
                                                ['sa' => $sa]
                                            )
                                        </div> --}}
                                    </td>
                                    <td>{{ $sa->kode_sa }}</td>
                                    <td>{{ tgl($sa->tanggal_sa) }}</td>
                                    <td>{{ $sa->gudang->nama }}</td>
                                    <td>{{ $sa->barang->nama }}</td>
                                    <td>{{ $sa->satuan->nama }}</td>
                                    <td>{{ $sa->keterangan }}</td>
                                    <td>{{ $sa->authorized_user->user->employee->fullname }}
                                        @if ($sa->authorized_user_id != $sa->user_id)
                                            (logged in as {{ $sa->user->employee->fullname }})
                                        @endif
                                    </td>
                                    <td>{{$sa->items->sum("qty")}}</td>
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
