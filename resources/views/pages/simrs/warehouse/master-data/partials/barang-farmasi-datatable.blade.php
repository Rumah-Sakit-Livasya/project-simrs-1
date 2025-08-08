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
                    Daftar <span class="fw-300"><i>Barang Farmasi</i></span>
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
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Golongan</th>
                                <th>Kategori</th>
                                <th>Kelompok</th>
                                <th>Harga Beli</th>
                                <th>Aktif?</th>
                                <th>Mapping</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $barang->kode }}
                                    </td>
                                    <td>
                                        {{ $barang->nama }}
                                    </td>
                                    <td>
                                        {{ $barang->satuan->kode }}
                                    </td>
                                    <td>
                                        {{ $barang->golongan ? $barang->golongan->nama : 'Unclassified' }}
                                    </td>
                                    <td>
                                        {{ $barang->kategori ? $barang->kategori->nama : 'Uncategorized' }}
                                    </td>
                                    <td>
                                        {{ $barang->kelompok?->nama }}
                                    </td>
                                    <td>
                                        {{ rp($barang->hna) }}
                                    </td>
                                    <td>
                                        {{ $barang->aktif ? 'Aktif' : 'Non Aktif' }}
                                    </td>
                                    <td>Coming Soon!</td>
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            title="Edit" data-id="{{ $barang->id }}"></a>

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $barang->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Golongan</th>
                                <th>Kategori</th>
                                <th>Kelompok</th>
                                <th>Harga Beli</th>
                                <th>Aktif?</th>
                                <th>Mapping</th>
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
