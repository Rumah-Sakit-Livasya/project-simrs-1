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
                                <th>[MRN] Nama Lengkap</th>
                                <th>Dokter</th>
                                <th>Diagnosa Awal</th>
                                <th>Kategori Diet</th>
                                <th>Asuransi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $registration)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $registration->kelas_rawat->kelas }}
                                    </td>
                                    <td>
                                        {{ $registration->patient->bed->room->ruangan }}
                                    </td>
                                    <td>
                                        {{ $registration->patient->bed->nama_tt }}
                                    </td>
                                    <td>
                                        {{ $registration->registration_number }}
                                    </td>
                                    <td>
                                        [{{ $registration->patient->medical_record_number }}]
                                        {{ $registration->patient->name }}
                                    </td>
                                    <td>
                                        {{ $registration->doctor->employee->fullname }}
                                    </td>
                                    <td>
                                        {{ $registration->diagnosa_awal }}
                                    </td>
                                    <td>
                                        {{ $registration->diet_gizi ? $registration->diet_gizi->category->nama : 'Belum Terpilih' }}
                                    </td>
                                    <td>
                                        {{ $registration->penjamin->penjamin ?? '-' }}
                                    </td>
                                    <td>
                                        <a class="mdi mdi-backburger pointer mdi-24px text-secondary"
                                            onclick="pilihMenuJamMakan({{ $registration->id }})" title="Pilih kategori diet"
                                            data-id="{{ $registration->id }}"></a>
                                        <a class="mdi mdi-silverware pointer mdi-24px text-success"
                                            onclick="orderPasien({{ $registration->id }})" title="Order makanan pasien"
                                            data-id="{{ $registration->id }}"></a>
                                        <a class="mdi mdi-account-group pointer mdi-24px text-info"
                                            onclick="orderKeluarga({{ $registration->id }})"
                                            title="Order makanan keluarga pasien"
                                            data-id="{{ $registration->id }}"></a>
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
                                <th>[MRN] Nama Lengkap</th>
                                <th>Dokter</th>
                                <th>Diagnosa Awal</th>
                                <th>Kategori Diet</th>
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

    function orderPasien(id) {
        let url = "/simrs/gizi/popup/order/pasien/" + id;
        let width = screen.width / 2;
        let height = screen.height / 2
        let left = width - (width / 2);
        let top = height - (height / 2);
        window.open(
            url,
            "popupWindow_orderGiziPasien_" + id,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    function orderKeluarga(id) {
        let url = "/simrs/gizi/popup/order/keluarga/" + id;
        let width = screen.width / 2;
        let height = screen.height / 2
        let left = width - (width / 2);
        let top = height - (height / 2);
        window.open(
            url,
            "popupWindow_orderGiziKeluarga_" + id,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    function pilihMenuJamMakan(id) {
        let url = "/simrs/gizi/popup/pilih-diet/" + id;
        let width = screen.width;
        let height = screen.height
        window.open(
            url,
            "popupWindow_pilihMenuJamMakan_" + id,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
