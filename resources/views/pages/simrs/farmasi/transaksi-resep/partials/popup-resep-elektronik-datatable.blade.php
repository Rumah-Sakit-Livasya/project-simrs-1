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
                    Daftar <span class="fw-300"><i>Resep Elektronik</i></span>
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
                                <th>No Resep</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($res as $re)
                                <tr style="cursor: pointer">
                                    <td onclick="pilihResepElektronik({{ $re }})">{{ $loop->iteration }}</td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->kode_re }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->registration->patient->medical_record_number }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->registration->registration_number }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ tgl($re->created_at) }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->registration->patient->name }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->registration->doctor->employee->fullname }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->registration->departement->name }}
                                        </a>
                                    </td>
                                    <td onclick="pilihResepElektronik({{ $re }})">
                                        <a onclick="pilihResepElektronik({{ $re }})">
                                            {{ $re->registration->penjamin->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary waves-effect waves-themed">
                                            <span class="fal fa-check mr-1"></span>
                                            Acknowledge
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>No Resep</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
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
    function pilihResepElektronik(recipe) {
        if (window.opener) {
            window.opener.postMessage({
                data: recipe,
                type: "re"
            }, "*")
        } else {
            alert("window.opener is not defined");
        }
        // console.log(recipe);
        window.close();
    }
</script>
