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
                    Daftar <span class="fw-300"><i>Menu</i></span>
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
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Aktif?</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Menu" data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $menu->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $menu->id }}">
                                            @include('pages.simrs.gizi.partials.detail-menu-gizi', [
                                                'menu' => $menu,
                                            ])
                                        </div>
                                    </td>
                                    <td>
                                        {{ $menu->nama }}
                                    </td>
                                    <td>
                                        {{ $menu->category->nama }}
                                    </td>
                                    <td>
                                        {{ rp($menu->harga) }}
                                    </td>
                                    <td>
                                        {{ $menu->aktif ? 'Aktif' : 'Non Aktif' }}
                                    </td>
                                    <td>
                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $menu->id }}"
                                            title="Edit" data-id="{{ $menu->id }}"></a>

                                        @include('pages.simrs.gizi.partials.edit-menu-modal', [
                                            'menu' => $menu,
                                        ])

                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus"
                                            data-id="{{ $menu->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
    const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    list.map((el) => {
        let opts = {
            animation: true,
        }
        if (el.hasAttribute('data-bs-content-id')) {
            opts.content = document.getElementById(el.getAttribute('data-bs-content-id')).innerHTML;
            opts.html = true;
            opts.sanitize = false;
        }
        new bootstrap.Popover(el, opts);
    })
</script>
