{{-- resources/views/pages/simrs/erm/partials/modal-resep-tindakan.blade.php --}}

<div class="modal fade" id="modal-resep-tindakan" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> {{-- Menggunakan modal-xl untuk ruang lebih luas --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Pilih Resep Obat & Tindakan Medis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Navigasi Tab --}}
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="resep-tab" data-toggle="tab" href="#resep" role="tab"
                            aria-controls="resep" aria-selected="true">Resep Obat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tindakan-tab" data-toggle="tab" href="#tindakan" role="tab"
                            aria-controls="tindakan" aria-selected="false">Tindakan Medis</a>
                    </li>
                </ul>

                {{-- Konten Tab --}}
                <div class="tab-content" id="myTabContent">
                    {{-- Konten Tab 1: Resep Obat --}}
                    <div class="tab-pane fade show active p-3" id="resep" role="tabpanel"
                        aria-labelledby="resep-tab">
                        <h5>Pencarian Obat</h5>
                        <form id="resep-search-form" class="mb-3" onsubmit="return false;">
                            <div class="input-group">
                                <input type="text" id="resep_search_input" class="form-control"
                                    placeholder="Ketik nama obat...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Cari Obat</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="resep-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Sediaan</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Konten Tab 2: Tindakan Medis --}}
                    <div class="tab-pane fade p-3" id="tindakan" role="tabpanel" aria-labelledby="tindakan-tab">
                        <h5>Pencarian Tindakan Medis</h5>
                        <form id="tindakan-search-form" class="mb-3" onsubmit="return false;">
                            <div class="input-group">
                                <input type="text" id="tindakan_search_input" class="form-control"
                                    placeholder="Ketik nama tindakan...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Cari Tindakan</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="tindakan-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Kode Tindakan</th>
                                        <th>Nama Tindakan</th>
                                        <th>Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
