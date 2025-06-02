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
                    Daftar <span class="fw-300"><i>Purchase Order (Pharmacy)</i></span>
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
                                <th>Supplier</th>
                                <th>Keterangan PO</th>
                                <th>Keterangan APP</th>
                                <th>User Entry</th>
                                <th>Type PO</th>
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
                                            data-bs-toggle="popover" data-bs-title="Detail Purchase Order"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $po->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $po->id }}">
                                            @include(
                                                'pages.simrs.procurement.purchase-order.partials.po-detail',
                                                ['po' => $po]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $po->kode_po }}</td>
                                    <td>{{ tgl($po->tanggal_po) }}</td>
                                    <td>{{ $po->supplier->nama }}</td>
                                    <td>{{ $po->keterangan }}</td>
                                    <td>{{ $po->keterangan_approval }}</td>
                                    <td>{{ $po->user->employee->fullname }}</td>
                                    <td>{{ ucfirst($po->tipe) }}</td>
                                    <td>{{ rp($po->nominal) }}</td>
                                    <td>{{ ucfirst($po->status) }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $po->id }}"></a>

                                        @if ($po->status == 'draft')
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Edit" data-id="{{ $po->id }}"></a>
                                            <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                title="Hapus" data-id="{{ $po->id }}"></a>
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
                                <th>Gudang</th>
                                <th>Keterangan PO</th>
                                <th>Keterangan APP</th>
                                <th>User Entry</th>
                                <th>Type PO</th>
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
