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
                    Daftar <span class="fw-300"><i>Penerimaan Barang (Non Pharmacy)</i></span>
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
                                <th>Tanggal Penerimaan</th>
                                <th>Kode Penerimaan</th>
                                <th>Supplier</th>
                                <th>Kode PO</th>
                                <th>No Faktur</th>
                                <th>PPN</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pbs as $pb)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Penerimaan Barang"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $pb->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $pb->id }}">
                                            @include(
                                                'pages.simrs.warehouse.penerimaan-barang.partials.pb-detail',
                                                ['pb' => $pb]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ tgl($pb->tanggal_terima) }}</td>
                                    <td>{{ $pb->kode_penerimaan }}</td>
                                    <td>{{ $pb->supplier->nama }}</td>
                                    <td>{{ $pb->po?->kode_po }}</td>
                                    <td>{{ $pb->no_faktur }}</td>
                                    <td>{{ rp($pb->ppn_nominal) }}</td>
                                    <td>{{ rp($pb->total_final) }}</td>
                                    <td>{{ ucfirst($pb->status) }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $pb->id }}"></a>

                                        @if ($pb->status == 'draft')
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Edit" data-id="{{ $pb->id }}"></a>
                                            <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                title="Hapus" data-id="{{ $pb->id }}"></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Tanggal Penerimaan</th>
                                <th>Kode Penerimaan</th>
                                <th>Supplier</th>
                                <th>Kode PO</th>
                                <th>No Faktur</th>
                                <th>PPN</th>
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
