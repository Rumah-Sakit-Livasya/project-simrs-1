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
                    Daftar <span class="fw-300"><i>Supplier</i></span>
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
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Phone</th>
                                <th>Fax</th>
                                <th>Email</th>
                                <th>Contact Person</th>
                                <th>Contact Person Phone</th>
                                <th>Contact Person Email</th>
                                <th>No Rek</th>
                                <th>Bank</th>
                                <th>TOP</th>
                                <th>Tipe TOP</th>
                                <th>PPN</th>
                                <th>Aktif?</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $supplier->nama }}
                                    </td>
                                    <td>
                                        {{ $supplier->alamat }}
                                    </td>
                                    <td>
                                        {{ $supplier->phone }}
                                    </td>
                                    <td>
                                        {{ $supplier->fax }}
                                    </td>
                                    <td>
                                        {{ $supplier->email }}
                                    </td>
                                    <td>
                                        {{ $supplier->contact_person }}
                                    </td>
                                    <td>
                                        {{ $supplier->contact_person_phone }}
                                    </td>
                                    <td>
                                        {{ $supplier->contact_person_email }}
                                    </td>
                                    <td>
                                        {{ $supplier->no_rek }}
                                    </td>
                                    <td>
                                        {{ $supplier->bank }}
                                    </td>
                                    <td>
                                        {{ $supplier->top ? strtolower($supplier->top) : '' }}
                                    </td>
                                    <td>
                                        {{ $supplier->tipe_top ? ucfirst(str_replace('_', ' ', $supplier->tipe_top)) : '' }}
                                    </td>
                                    <td>
                                        {{ $supplier->ppn }}
                                    </td>
                                    <td>
                                        {{ $supplier->aktif ? 'Aktif' : 'Non Aktif' }}
                                    </td>
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $supplier->id }}"
                                            title="Edit" data-id="{{ $supplier->id }}"></a>

                                        @include(
                                            'pages.simrs.warehouse.master-data.partials.edit-supplier-modal',
                                            [
                                                'supplier' => $supplier,
                                            ]
                                        )

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $supplier->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Phone</th>
                                <th>Fax</th>
                                <th>Email</th>
                                <th>Contact Person</th>
                                <th>Contact Person Phone</th>
                                <th>Contact Person Email</th>
                                <th>No Rek</th>
                                <th>Bank</th>
                                <th>TOP</th>
                                <th>Tipe TOP</th>
                                <th>PPN</th>
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
