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
                    Daftar <span class="fw-300"><i>Resep Harian</i></span>
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
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th>Ruang</th>
                                <th>Penjamin</th>
                                <th>Gudang</th>
                                <th>User Entry</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rhs as $rh)
                                <tr style="cursor: pointer">
                                    <td>{{ $loop->iteration }}</td>
                                    <td> <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                            data-bs-toggle="popover" data-bs-title="Detail Resep Harian"
                                            data-bs-html="true"
                                            data-bs-content-id="popover-content-{{ $rh->id }}">
                                            <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                        </button>
                                        <div class="display-none" id="popover-content-{{ $rh->id }}">
                                            @include(
                                                'pages.simrs.farmasi.resep-harian.partials.rh-detail',
                                                ['resep' => $rh]
                                            )
                                        </div>
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ tgl_waktu($rh->created_at) }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->kode_resep }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->registration->patient->medical_record_number }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->registration->registration_number }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->registration->patient->name }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->registration->doctor->employee->fullname }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->registration->kelas_rawat->kelas }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->registration->penjamin->name ?? '-' }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->gudang->nama }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->user->name }}
                                    </td>
                                    <td onclick="pilihResepHarian({{ $rh }})">
                                        {{ $rh->status ? 'Selesai' : 'Belum Selesai' }}
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
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th>Ruang</th>
                                <th>Penjamin</th>
                                <th>Gudang</th>
                                <th>User Entry</th>
                                <th>Status</th>
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
    function pilihResepHarian(recipe) {
        if (window.opener) {
            window.opener.postMessage({
                data: recipe,
                type: "rh"
            }, "*")
        } else {
            alert("window.opener is not defined");
        }
        // console.log(recipe);
        window.close();
    }
</script>
