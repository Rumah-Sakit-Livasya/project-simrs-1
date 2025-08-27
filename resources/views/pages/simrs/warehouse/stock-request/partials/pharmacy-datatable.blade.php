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
                    Daftar <span class="fw-300"><i>Stock Request (Pharmacy)</i></span>
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
                                <th>Kode SR</th>
                                <th>Tanggal SR</th>
                                <th>Gudang Asal</th>
                                <th>Gudang Tujuan</th>
                                <th>Keterangan</th>
                                <th>User</th>
                                <th>Tipe SR</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($srs as $sr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Stock Request"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $sr->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $sr->id }}">
                                            @include(
                                                'pages.simrs.warehouse.stock-request.partials.sr-detail',
                                                ['sr' => $sr]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $sr->kode_sr }}</td>
                                    <td>{{ tgl($sr->tanggal_sr) }}</td>
                                    <td>{{ $sr->asal->nama }}</td>
                                    <td>{{ $sr->tujuan->nama }}</td>
                                    <td>{{ $sr->keterangan }}</td>
                                    <td>{{ $sr->user->employee->fullname }}</td>
                                    <td>{{ ucfirst($sr->tipe) }}</td>
                                    <td>{{ ucfirst($sr->status) }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $sr->id }}"></a>

                                        @if ($sr->status == 'draft')
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Edit" data-id="{{ $sr->id }}"></a>
                                            <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                title="Hapus" data-id="{{ $sr->id }}"></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Kode SR</th>
                                <th>Tanggal SR</th>
                                <th>Gudang Asal</th>
                                <th>Gudang Tujuan</th>
                                <th>Keterangan</th>
                                <th>User</th>
                                <th>Tipe SR</th>
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
