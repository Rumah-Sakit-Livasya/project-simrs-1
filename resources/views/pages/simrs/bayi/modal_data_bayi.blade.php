<!-- resources/views/pages/simrs/persalinan/partials/modal_data_bayi.blade.php -->
<style>
    .close i {
        color: #000;
        /* z-index: 10; */
    }
</style>


<div class="modal fade" id="modal-data-bayi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="panel-hdr bg-primary-600">
                <h2><i class="fal fa-baby-carriage mr-2"></i> Data Bayi</h2>
                <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-window-close"></i>
                </button>
            </div>
            {{-- <div class="modal-header ">
                <h5 class="modal-title">Data Bayi</h5>
            </div> --}}
            <div class="modal-body">
                <!-- [DIUBAH] Area Tabel Data Bayi -->
                <div>
                    <div class="d-flex justify-content-start mb-3">
                        <button class="btn btn-primary btn-sm" id="btn-tambah-bayi">
                            <i class="fal fa-plus"></i> Tambah Bayi
                        </button>
                    </div>
                    <table id="dt-bayi-table" class="table table-bordered table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No RM</th>
                                <th>Nama Bayi</th>
                                <th>Tgl Lahir</th>
                                <th>Tgl Reg</th>
                                <th>No Label</th>
                                <th>Fungsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <hr class="my-4">

                <!-- [DIUBAH] Area Form Input (Slider), akan muncul di bawah tabel -->
                <div id="bayi-form-container" style="display: none;">
                    @include('pages.simrs.persalinan.partials.form_bayi')
                </div>
            </div>

        </div>
    </div>
</div>
