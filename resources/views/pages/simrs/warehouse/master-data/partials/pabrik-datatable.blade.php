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
                    Daftar <span class="fw-300"><i>Pabrik</i></span>
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
                                <th>Nama Pabrik</th>
                                <th>Alamat</th>
                                <th>Telp</th>
                                <th>Contact Person</th>
                                <th>Contact Person Phone</th>
                                <th>Aktif?</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pabriks as $pabrik)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $pabrik->nama }}
                                    </td>
                                    <td>
                                        {{ $pabrik->alamat }}
                                    </td>
                                    <td>
                                        {{ $pabrik->telp }}
                                    </td>
                                    <td>
                                        {{ $pabrik->contact_person }}
                                    </td>
                                    <td>
                                        {{ $pabrik->contact_person_phone }}
                                    </td>
                                    <td>
                                        {{ $pabrik->aktif ? 'Aktif' : 'Non Aktif' }}
                                    </td>
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $pabrik->id }}"
                                            title="Edit" data-id="{{ $pabrik->id }}"></a>

                                        @include(
                                            'pages.simrs.warehouse.master-data.partials.edit-pabrik-modal',
                                            [
                                                'pabrik' => $pabrik,
                                            ]
                                        )

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $pabrik->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nama Pabrik</th>
                                <th>Alamat</th>
                                <th>Telp</th>
                                <th>Contact Person</th>
                                <th>Contact Person Phone</th>
                                <th>Aktif?</th>
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
