{{-- CSS untuk child row dan styling ikon --}}
<style>
    /* Styling untuk child row agar lebih menonjol */
    tr.details-shown>td {
        padding: 0 !important;
        border-bottom: 2px solid #3c6eb4 !important;
    }

    .child-table {
        width: 95%;
        margin: 10px auto;
    }

    .child-table thead {
        background-color: #eef3f9;
    }
</style>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Order Radiologi</i></span>
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
                                <th style="width: 20px;"></th> {{-- Kolom untuk ikon child row --}}
                                <th>Tanggal</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>No. Order</th>
                                <th>Nama Lengkap</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
                                <th>Dokter</th>
                                <th>Status Isi Hasil</th>
                                <th>Status Billed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data will be loaded via AJAX --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th></th> {{-- Kolom untuk ikon child row --}}
                                <th>Tanggal</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>No. Order</th>
                                <th>Nama Lengkap</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
                                <th>Dokter</th>
                                <th>Status Isi Hasil</th>
                                <th>Status Billed</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
