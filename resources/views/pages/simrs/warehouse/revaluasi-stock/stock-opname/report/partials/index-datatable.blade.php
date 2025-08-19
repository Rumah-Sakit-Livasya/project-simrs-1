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
                    Daftar <span class="fw-300"><i>Stock Opname</i></span>
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
                                <th>Gudang</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Freeze By</th>
                                <th>Unfreeze By</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sogs as $sog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Selisih Stock Opname"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $sog->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $sog->id }}">
                                            @include(
                                                'pages.simrs.warehouse.revaluasi-stock.stock-opname.report.partials.so-detail',
                                                ['sog' => $sog]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $sog->gudang->nama }}</td>
                                    <td>{{ tgl($sog->start) }}</td>
                                    <td>{{ isset($sog->finish) ? tgl($sog->finish) : 'Ongoing' }}</td>
                                    <td>{{ $sog->start_user->employee->fullname }}</td>
                                    <td>{{ isset($sog->finish_user_id) ? $sog->finish_user->employee->fullname : 'Ongoing' }}
                                    </td>
                                    <td>
                                        <a class="mdi mdi-vector-difference pointer mdi-24px text-warning print-selisih-btn"
                                            title="Print Selisih" data-id="{{ $sog->id }}"></a>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-detail-btn"
                                            title="Print Detail" data-id="{{ $sog->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Gudang</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Freeze By</th>
                                <th>Unfreeze By</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
