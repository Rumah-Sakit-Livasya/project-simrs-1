<div class="row">
    <div class="col-xl-12">
        <div id="panel-2" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Master Gudang</i></span>
                </h2>
                <div class="panel-toolbar">
                    {{--
                    Tombol Tambah ini SEKARANG DIKONTROL OLEH JAVASCRIPT.
                    Diberi ID 'btn-add' dan tidak lagi memiliki data-toggle/data-target.
                    --}}
                    <button id="btn-add" class="btn btn-primary btn-sm">
                        <i class="fal fa-plus mr-1"></i>
                        Tambah
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="dt-master-gudang" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Gudang</th>
                                <th>Cost Center</th>
                                <th class="text-center">Apotek?</th>
                                <th class="text-center">Default Rajal?</th>
                                <th class="text-center">Default Ranap?</th>
                                <th class="text-center">Warehouse?</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($master_gudangs as $gudang)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $gudang->nama }}</td>
                                    <td>{{ $gudang->cost_center ?? '-' }}</td>
                                    <td class="text-center">{!! $gudang->apotek
                                        ? '<span class="badge badge-success">Ya</span>'
                                        : '<span class="badge badge-secondary">Tidak</span>' !!}</td>
                                    <td class="text-center">{!! $gudang->rajal_default
                                        ? '<span class="badge badge-info">Ya</span>'
                                        : '<span class="badge badge-secondary">Tidak</span>' !!}</td>
                                    <td class="text-center">{!! $gudang->ranap_default
                                        ? '<span class="badge badge-info">Ya</span>'
                                        : '<span class="badge badge-secondary">Tidak</span>' !!}</td>
                                    <td class="text-center">{!! $gudang->warehouse
                                        ? '<span class="badge badge-success">Ya</span>'
                                        : '<span class="badge badge-secondary">Tidak</span>' !!}</td>
                                    <td class="text-center">{!! $gudang->aktif
                                        ? '<span class="badge badge-primary">Aktif</span>'
                                        : '<span class="badge badge-danger">Non Aktif</span>' !!}</td>
                                    <td class="text-center">
                                        {{--
                                        Tombol Edit ini juga DIKONTROL OLEH JAVASCRIPT.
                                        Hanya memiliki class 'edit-btn' dan atribut 'data-id'.
                                        --}}
                                        <button class="btn btn-xs btn-warning waves-effect waves-themed edit-btn"
                                            data-id="{{ $gudang->id }}" title="Edit Data">
                                            <i class="fal fa-pencil"></i>
                                        </button>

                                        {{-- Tombol Hapus tetap sama, dikontrol oleh JS --}}
                                        <button class="btn btn-xs btn-danger waves-effect waves-themed delete-btn"
                                            data-id="{{ $gudang->id }}" title="Hapus Data">
                                            <i class="fal fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                {{-- Menangani kasus jika tidak ada data sama sekali --}}
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="alert alert-info">
                                            Tidak ada data untuk ditampilkan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
