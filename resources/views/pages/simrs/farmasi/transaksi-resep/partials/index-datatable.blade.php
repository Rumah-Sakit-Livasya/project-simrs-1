<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }

    .nama-pasien {
        color: greenyellow;
    }

    .rm-pasien {
        color: salmon;
    }

    .reg-pasien {
        color: slateblue;
    }

    .nama-dokter {
        color: greenyellow;

    }

    .poli-dokter {
        color: orange;
    }
</style>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Resep</i></span>
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
                                <th>Tanggal</th>
                                <th>No Resep</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Nominal</th>
                                <th>User Entry</th>
                                <th>Status</th>
                                <th>Telaah Resep</th>
                                <th>Antrol BPJS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($reseps as $resep)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Order Gizi"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $resep->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>

                                        <div class="display-none" id="popover-content-{{ $resep->id }}">
                                            @include(
                                                'pages.simrs.farmasi.transaksi-resep.partials.detail-resep',
                                                [
                                                    'resep' => $resep,
                                                ]
                                            )
                                        </div>
                                    </td>
                                    <td>
                                        {{ tgl($resep->created_at) }}
                                    </td>
                                    <td>
                                        {{ $resep->kode_re }}
                                    </td>
                                    <td>
                                        <span class="nama-pasien">{{ $resep->registration->patient->name }}</span> <br>
                                        <span
                                            class="rm-pasien">{{ $resep->registration->patient->medical_record_number }}</span>
                                        <br>
                                        <span class="reg-pasien">{{ $resep->registration->registration_number }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="nama-dokter">{{ isset($resep->cppt->doctor_id) ? $resep->cppt->doctor->employee->fullname : $resep->registration->doctor_fullname }}</span><br>
                                        <span class="poli-dokter">{{ $resep->registration->department->name }}</span>
                                    </td>
                                    <td>
                                        {{ rp($resep->total) }}
                                    </td>
                                    <td>
                                        {{ $resep->user->name }}
                                    </td>
                                    <td>
                                        {{ $resep->billed ? 'Billed' : 'Belum Bill' }}
                                    </td>
                                    <td>
                                        Coming Soon!
                                    </td>
                                    <td>
                                        Coming Soon!
                                    </td>
                                    <td>
                                        <a class="fas fa-pencil pointer fa-lg text-secondary edit-btn"
                                            title="Edit resep" data-id="{{ $resep->id }}"></a>
                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                            title="Hapus resep" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-clipboard-list pointer fa-lg text-info telaah-btn"
                                            title="Telaah resep" data-id="{{ $resep->id }}"></a>

                                        <a class="fas fa-print pointer fa-lg text-success print-e-tiket-btn"
                                            title="Print E-Tiket" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-print pointer fa-lg text-info print-e-tiket-ranap-btn"
                                            title="Print E-Tiket Ranap" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-print pointer fa-lg text-primary print-penjualan-btn"
                                            title="Print Penjualan" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-print pointer fa-lg text-warning print-resep-btn"
                                            title="Print Resep" data-id="{{ $resep->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Nama Pemesan</th>
                                <th>Untuk</th>
                                <th>[KELAS] Nama Pasien</th>
                                <th>No RM / No Reg</th>
                                <th>Waktu Makan</th>
                                <th>Harga</th>
                                <th>Ditagihkan?</th>
                                <th>Pembayaran</th>
                                <th>Pesanan</th>
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
