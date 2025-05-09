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
                    Daftar <span class="fw-300"><i>Kategori Barang</i></span>
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
                                <th>Nama Kategori</th>
                                <th>COA Inventory</th>
                                <th>COA Sales Outpatient</th>
                                <th>COA COGS Outpatient</th>
                                <th>COA Sales Inpatient</th>
                                <th>COA COGS Inpatient</th>
                                <th>COA Adjustment Daily</th>
                                <th>COA Adjustment SO</th>
                                <th>Konsinyasi</th>
                                <th>Aktif?</th>
                                <th>Kode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategoris as $kategori)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kategori->nama }}</td>
                                    <td>{{ $kategori->coa_inventory }}</td>
                                    <td>{{ $kategori->coa_sales_outpatient }}</td>
                                    <td>{{ $kategori->coa_cogs_outpatient }}</td>
                                    <td>{{ $kategori->coa_sales_inpatient }}</td>
                                    <td>{{ $kategori->coa_cogs_inpatient }}</td>
                                    <td>{{ $kategori->coa_adjustment_daily }}</td>
                                    <td>{{ $kategori->coa_adjustment_so }}</td>
                                    <td>{{ $kategori->konsinsyasi ? 'Ya' : 'Tidak' }}</td>
                                    <td>{{ $kategori->aktif ? 'Aktif' : 'Non Aktif' }}</td>
                                    <td>{{ $kategori->kode }}</td>
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $kategori->id }}"
                                            title="Edit" data-id="{{ $kategori->id }}"></a>

                                        @include(
                                            'pages.simrs.warehouse.master-data.partials.edit-kategori-barang-modal',
                                            [
                                                'kategori' => $kategori,
                                            ]
                                        )

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $kategori->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>COA Inventory</th>
                                <th>COA Sales Outpatient</th>
                                <th>COA COGS Outpatient</th>
                                <th>COA Sales Inpatient</th>
                                <th>COA COGS Inpatient</th>
                                <th>COA Adjustment Daily</th>
                                <th>COA Adjustment SO</th>
                                <th>Konsinyasi</th>
                                <th>Aktif?</th>
                                <th>Kode Barang</th>
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
