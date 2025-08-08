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
                    Daftar <span class="fw-300"><i>Retur Barang</i></span>
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
                                <th>Kode Retur</th>
                                <th>Tanggal Retur</th>
                                <th>Supplier</th>
                                <th>Keterangan</th>
                                <th>User</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rbs as $rb)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Retur Barang"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $rb->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $rb->id }}">
                                            @include(
                                                'pages.simrs.warehouse.retur-barang.partials.rb-detail',
                                                ['rb' => $rb]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $rb->kode_retur }}</td>
                                    <td>{{ tgl($rb->tanggal_retur) }}</td>
                                    <td>{{ $rb->supplier->nama }}</td>
                                    <td>{{ $rb->keterangan }}</td>
                                    <td>{{ $rb->user->name }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $rb->id }}"></a>
                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $rb->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Kode Retur</th>
                                <th>Tanggal Retur</th>
                                <th>Supplier</th>
                                <th>Keterangan</th>
                                <th>User</th>
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
