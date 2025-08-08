<!-- Section: Order Persalinan -->
<div class="panel">
    <div class="panel-hdr">
        <h2>
            <i class="mdi mdi-seat-flat-angled mr-2 text-info"></i>
            Order Persalinan (VK)
        </h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="dt-order-vk" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-info-600">
                        <tr>
                            <th>Tgl Rencana</th>
                            <th>Dokter DPJP</th>
                            <th>Bidan</th>
                            <th>Jenis Persalinan</th>
                            <th>Indikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data dimuat oleh AJAX -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">
                                <button type="button" class="btn btn-sm btn-outline-info waves-effect waves-themed"
                                    id="btn-tambah-order-vk" data-toggle="modal" data-target="#modal-order-vk">
                                    <span class="fal fa-plus-circle mr-1"></span>
                                    Tambah Order VK
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary waves-effect waves-themed"
                                    id="btn-reload-order-vk">
                                    <span class="fal fa-sync mr-1"></span>
                                    Reload
                                </button>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Section: Tindakan Persalinan (Sudah Dilaksanakan) -->
<div class="panel mt-4">
    <div class="panel-hdr">
        <h2>
            <i class="fas fa-baby-carriage mr-2 text-pink"></i>
            Tindakan Persalinan (Sudah Dilaksanakan)
        </h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="dt-tindakan-vk" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-pink-500">
                        <tr>
                            <th>Tgl Tindakan</th>
                            <th>Dokter</th>
                            <th>Bidan</th>
                            <th>Lama Kala 1</th>
                            <th>Lama Kala 2</th>
                            <th>User Create</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data dimuat oleh AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include Modal untuk VK -->
@include('pages.simrs.pendaftaran.partials.modal-order-vk')
