<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }

    .nama-pasien {
        color: green;
    }

    .rm-pasien {
        color: salmon;
    }

    .reg-pasien {
        color: slateblue;
    }

    .nama-dokter {
        color: green;

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
                                <th>Gudang</th>
                                <th>Nominal</th>
                                <th>User Entry</th>
                                <th>Status</th>
                                <th>Antrol BPJS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reseps as $resep)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Resep" data-bs-html="true"
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
                                        {{ tgl_waktu($resep->created_at) }}
                                    </td>
                                    <td>
                                        {{ $resep->kode_resep }}
                                    </td>
                                    <td>
                                        @php
                                            if ($resep->tipe_pasien == 'otc') {
                                                $nama = $resep->otc->nama_pasien;
                                                $mrn = 'OTC';
                                                $rn = $resep->otc->registration_number;
                                            } else {
                                                $nama = $resep->registration->patient->name;
                                                $mrn = $resep->registration->patient->medical_record_number;
                                                $rn = $resep->registration->registration_number;
                                            }
                                        @endphp
                                        <span class="nama-pasien">{{ $nama }}</span>
                                        <br>
                                        <span class="rm-pasien">No RM: {{ $mrn }}</span>
                                        <br>
                                        <span class="reg-pasien">No Reg: {{ $rn }}</span>
                                    </td>
                                    <td>
                                        @if ($resep->tipe_pasien == 'otc')
                                            <span class="poli-dokter">APOTIK</span>
                                        @else
                                            <span
                                                class="nama-dokter">{{ $resep->doctor->employee->fullname }}</span><br>
                                            <span
                                                class="poli-dokter">{{ $resep->doctor->department_from_doctors->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $resep->gudang->nama }}
                                    </td>
                                    <td>
                                        {{ rp($resep->total) }}
                                    </td>
                                    <td>
                                        {{ $resep->user->name }}
                                    </td>
                                    <td>
                                        {{ $resep->billed ? 'Lunas' : 'Belum Bill' }}
                                    </td>
                                    <td>
                                        Coming Soon!
                                    </td>
                                    <td>
                                        @if (!$resep->billed)
                                            <a class="fas fa-pencil pointer fa-lg text-secondary edit-btn"
                                                title="Edit resep" data-id="{{ $resep->id }}"></a>
                                            <a class="fal fa-times pointer fa-lg text-danger delete-btn"
                                                title="Hapus resep" data-id="{{ $resep->id }}"></a>
                                        @endif
                                        
                                        <a class="fas fa-clipboard-list pointer fa-lg text-info telaah-btn"
                                            title="Telaah resep" data-id="{{ $resep->id }}"></a>

                                        <a class="fas fa-print pointer fa-lg text-success print-e-tiket-btn"
                                            title="Print E-Tiket" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-print pointer fa-lg text-secondary print-e-tiket-ranap-btn"
                                            title="Print E-Tiket Ranap" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-print pointer fa-lg text-primary print-penjualan-btn"
                                            title="Print Penjualan" data-id="{{ $resep->id }}"></a>
                                        <a class="fas fa-print pointer fa-lg text-warning print-resep-btn"
                                            title="Print Resep" data-id="{{ $resep->id }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Tanggal</th>
                                <th>No Resep</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Gudang</th>
                                <th>Nominal</th>
                                <th>User Entry</th>
                                <th>Status</th>
                                <th>Antrol BPJS</th>
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