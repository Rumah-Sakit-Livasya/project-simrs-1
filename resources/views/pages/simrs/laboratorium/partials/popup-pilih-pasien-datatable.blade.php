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
    // === FUNGSI UTAMA UNTUK MENGIRIM DATA ===
    function pilihPasien(registrationData) {
        if (window.opener) {
            // **PERBAIKAN KUNCI ADA DI SINI**
            // Kirim objek dengan format yang sama seperti yang diharapkan listener di halaman order
            window.opener.postMessage({
                type: 'pasien_selected', // Tipe pesan
                data: registrationData // Data pasien
            }, "*"); // Target origin '*' untuk development, bisa diperketat di produksi
        } else {
            alert("Tidak dapat menemukan halaman pembuka (opener).");
        }
        window.close();
    }

    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#dt-popup-pasien').DataTable({
            responsive: true,
            "order": [
                [0, "desc"]
            ] // Urutkan berdasarkan kolom pertama (tanggal) secara descending
        });

        // Tambahkan event listener untuk setiap baris di tabel
        $('#dt-popup-pasien tbody').on('click', 'tr', function() {
            // Ambil data JSON dari atribut data-pasien
            const encodedData = $(this).data('pasien');
            if (encodedData) {
                const decodedJson = atob(encodedData); // Decode dari Base64
                const registrationObject = JSON.parse(decodedJson); // Parse menjadi objek

                // Panggil fungsi untuk mengirim data
                pilihPasien(registrationObject);
            }
        });

        // Inisialisasi Date Range Picker
        $('#registration_date_filter').daterangepicker({
            opens: 'left',
            autoUpdateInput: false, // Penting: jangan update input secara otomatis
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        // Event saat tanggal dipilih
        $('#registration_date_filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                'YYYY-MM-DD'));
        });

        // Event saat tombol 'Clear' di picker diklik
        $('#registration_date_filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    // Helper untuk format input No. RM
    function formatAngka(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 6) value = value.substr(0, 6);
        if (value.length > 0) {
            input.value = value.match(/.{1,2}/g).join('-');
        } else {
            input.value = '';
        }
    }
</script>
