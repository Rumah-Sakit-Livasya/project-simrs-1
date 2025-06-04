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
                    Daftar <span class="fw-300"><i>Approval PO (Non Pharmacy)</i></span>
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
                                <th>Kode PO</th>
                                <th>Tanggal PO</th>
                                <th>Tanggal APP</th>
                                <th>Supplier</th>
                                <th>Keterangan PO</th>
                                <th>Keterangan APP</th>
                                <th>User APP</th>
                                <th>Tipe PO</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pos as $po)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Purchase Request"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $po->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $po->id }}">
                                            @include(
                                                'pages.simrs.procurement.approval-po.partials.po-detail',
                                                ['po' => $po]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $po->kode_po }}</td>
                                    <td>{{ tgl($po->tanggal_po) }}</td>
                                    <td>{{ $po->tanggal_app ? tgl($po->tanggal_app) : 'Unapproved' }}</td>
                                    <td>{{ $po->supplier->nama }}</td>
                                    <td>{{ $po->keterangan }}</td>
                                    <td>{{ $po->keterangan_approval }}</td>
                                    <td>{{ $po->app_user && $po->app_user->employee->fullname }}</td>
                                    <td>{{ ucfirst($po->tipe) }}</td>
                                    <td>{{ rp($po->nominal) }}</td>
                                    <td>
                                        @switch($po->approval)
                                            @case('unreviewed')
                                                <span class="text-secondary">Unreviewed</span>
                                            @break

                                            @case('approve')
                                                <span class="text-success">Approved</span>
                                            @break

                                            @case('reject')
                                                <span class="text-danger">Rejected</span>
                                            @break

                                            @case('revision')
                                                <span class="text-info">Revision</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($po->approval == 'unreviewed')
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Review" data-id="{{ $po->id }}"></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Kode PO</th>
                                <th>Tanggal PO</th>
                                <th>Tanggal APP</th>
                                <th>Supplier</th>
                                <th>Keterangan PO</th>
                                <th>Keterangan APP</th>
                                <th>User APP</th>
                                <th>Tipe PO</th>
                                <th>Nominal</th>
                                <th>Status</th>
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
