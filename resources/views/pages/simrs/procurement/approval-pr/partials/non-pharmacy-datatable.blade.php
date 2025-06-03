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
                    Daftar <span class="fw-300"><i>Approval PR (Non Pharmacy)</i></span>
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
                                <th>Kode PR</th>
                                <th>Tanggal PR</th>
                                <th>Tanggal APP</th>
                                <th>Gudang</th>
                                <th>Keterangan PR</th>
                                <th>Keterangan APP</th>
                                <th>User APP</th>
                                <th>Tipe PR</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prs as $pr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Purchase Request"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $pr->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $pr->id }}">
                                            @include(
                                                'pages.simrs.procurement.approval-pr.partials.pr-detail',
                                                ['pr' => $pr]
                                            )
                                        </div>
                                    </td>
                                    <td>{{ $pr->kode_pr }}</td>
                                    <td>{{ tgl($pr->tanggal_pr) }}</td>
                                    <td>{{ $pr->tanggal_app ? tgl($pr->tanggal_app) : 'Unreviewed' }}</td>
                                    <td>{{ $pr->gudang->nama }}</td>
                                    <td>{{ $pr->keterangan }}</td>
                                    <td>{{ $pr->keterangan_approval }}</td>
                                    <td>{{ $pr->app_user && $pr->app_user->employee->fullname }}</td>
                                    <td>{{ ucfirst($pr->tipe) }}</td>
                                    <td>{{ rp($pr->nominal) }}</td>
                                    <td>{{ $pr->status == 'final' ? 'Unreviewed' : 'Reviewed' }}</td>
                                    <td>
                                        <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                            title="Print" data-id="{{ $pr->id }}"></a>

                                        @if ($pr->status == 'final')
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Review" data-id="{{ $pr->id }}"></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Kode PR</th>
                                <th>Tanggal PR</th>
                                <th>Tanggal APP</th>
                                <th>Gudang</th>
                                <th>Keterangan PR</th>
                                <th>Keterangan APP</th>
                                <th>User APP</th>
                                <th>Tipe PR</th>
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
