{{-- Atribut data-dismiss dan data-target digunakan untuk Bootstrap 4 --}}
<div class="modal fade" id="pilihMenuModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Paket Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    {{-- Struktur input-group untuk Bootstrap 4 menggunakan input-group-prepend --}}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fal fa-search"></i></span>
                        </div>
                        <input type="text" id="searchMenuInput" placeholder="Ketik untuk mencari nama menu..."
                            class="form-control">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover w-100 mb-0" id="menu-paket-table">
                        <thead class="bg-primary-600 text-white">
                            <tr>
                                <th>Nama Menu</th>
                                <th class="text-end" style="width: 15%;">Harga</th>
                                <th>Isi Makanan</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="menu-list">
                            @forelse ($menus as $menu)
                                <tr>
                                    <td class="align-middle fw-semibold">
                                        {{ $menu->nama }}
                                    </td>
                                    <td class="align-middle text-end">
                                        {{ rp($menu->harga) }}
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $makananDalamMenu = $menu->relationLoaded('makanan_menu')
                                                ? $menu->makanan_menu
                                                : [];
                                        @endphp
                                        @if (!empty($makananDalamMenu))
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($makananDalamMenu as $makananMenu)
                                                    @if ($makananMenu->aktif && $makananMenu->makanan)
                                                        <li
                                                            class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                            <span>
                                                                <i class="fal fa-utensil-fork text-muted me-2"></i>
                                                                {{ $makananMenu->makanan->nama }}
                                                            </span>
                                                            <span class="badge bg-info text-white">
                                                                {{ rp($makananMenu->makanan->harga) }}
                                                            </span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button" class="btn btn-sm btn-primary pilih-menu-btn"
                                            data-menu-id="{{ $menu->id }}">
                                            <i class="fal fa-check me-1"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <em>Tidak ada data menu yang tersedia.</em>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                {{-- Menggunakan data-dismiss untuk Bootstrap 4 --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
