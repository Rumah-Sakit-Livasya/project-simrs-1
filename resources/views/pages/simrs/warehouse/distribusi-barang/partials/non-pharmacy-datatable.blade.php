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
                    Daftar <span class="fw-300"><i>Distribusi Barang (Non Pharmacy)</i></span>
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
                                <th>Kode DB</th>
                                <th>Tanggal DB</th>
                                <th>Gudang Asal</th>
                                <th>Gudang Tujuan</th>
                                <th>Keterangan</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Kode SR</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dbs as $db)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Distribusi Barang"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $db->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $db->id }}">
                                            @include(
                                                'pages.simrs.warehouse.distribusi-barang.partials.db-detail',
                                                ['db' => $db]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $db->kode_db }}</td>
                                    <td>{{ tgl($db->tanggal_db) }}</td>
                                    <td>{{ $db->asal->nama }}</td>
                                    <td>{{ $db->tujuan->nama }}</td>
                                    <td>{{ $db->keterangan }}</td>
                                    <td>{{ $db->user->employee->fullname }}</td>
                                    <td>{{ ucfirst($db->status) }}</td>
                                    <td>{{ $db->sr?->kode_sr }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $db->id }}"></a>

                                        @if ($db->status == 'draft')
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Edit" data-id="{{ $db->id }}"></a>
                                            <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                title="Hapus" data-id="{{ $db->id }}"></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Kode DB</th>
                                <th>Tanggal DB</th>
                                <th>Gudang Asal</th>
                                <th>Gudang Tujuan</th>
                                <th>Keterangan</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Kode SR</th>
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
