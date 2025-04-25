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
                    Daftar <span class="fw-300"><i>Pasien</i></span>
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
                                <th>Kelas</th>
                                <th>Ruang</th>
                                <th>T. Tidur</th>
                                <th>No. Registrasi</th>
                                <th>Tgl. Reg</th>
                                <th>Nama Lengkap</th>
                                <th>Dokter</th>
                                <th>Diagnosa Awal</th>
                                <th>Asuransi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $order->kelas_rawat->kelas }}
                                    </td>
                                    <td>
                                      {{ $order->patient->bed->room->ruangan }}
                                    </td>
                                    <td>
                                       {{ $order->patient->bed->nama_tt }}
                                    </td>
                                    <td>
                                      {{ $order->registration_number }}
                                    </td>
                                    <td>
                                      {{ $order->date }}
                                    </td>
                                    <td>
                                       {{ $order->patient->name }}
                                    </td>
                                    <td>
                                        {{ $order->doctor->employee->fullname }}
                                    </td>
                                    <td>
                                     {{ $order->diagnosa_awal }}
                                    </td>
                                    <td>
                                      {{ $order->penjamin->penjamin ?? '-' }}
                                    </td>
                                    <td>
                                       
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Kelas</th>
                                <th>Ruang</th>
                                <th>T. Tidur</th>
                                <th>No. Registrasi</th>
                                <th>Tgl. Reg</th>
                                <th>Nama Lengkap</th>
                                <th>Dokter</th>
                                <th>Diagnosa Awal</th>
                                <th>Asuransi</th>
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
