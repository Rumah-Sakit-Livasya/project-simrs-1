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
                    Daftar <span class="fw-300"><i>Registrasi Pasien</i></span>
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
                                <th>Tanggal Registrasi</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>Nama Lengkap</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $registration)
                                <tr style="cursor: pointer" onclick="pilihPasien({{ $registration }})">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a onclick="pilihPasien({{ $registration }})">
                                            {{ $registration->registration_date }}
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="pilihPasien({{ $registration }})">
                                            {{ $registration->patient->medical_record_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="pilihPasien({{ $registration }})">
                                            {{ $registration->registration_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="pilihPasien({{ $registration }})">
                                            {{ $registration->patient->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="pilihPasien({{ $registration }})">
                                            {{ $registration->poliklinik }}
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="pilihPasien({{ $registration }})">
                                            {{ $registration->patient->penjamin->name ?? '-' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Registrasi</th>
                                <th>No. RM</th>
                                <th>No. Registrasi</th>
                                <th>Nama Lengkap</th>
                                <th>Poly / Ruang</th>
                                <th>Penjamin</th>
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
    function pilihPasien(registration) {
        if (window.opener) {
            // --- INI PERBAIKANNYA ---
            // Buat objek pesan yang sesuai dengan yang diharapkan halaman utama
            const message = {
                type: 'patientSelected', // Tambahkan properti 'type'
                data: registration // Kirim objek registrasi langsung
            };

            // Kirim pesan dengan format yang benar
            window.opener.postMessage(message, "*");
            // -------------------------

        } else {
            alert("window.opener is not defined");
        }
        window.close();
    }
</script>
