<script>
    $(document).ready(function() {
        // ===================================================================
        // INISIALISASI AWAL
        // ===================================================================
        $('#cppt_doctor_id').val("{{ $registration->doctor_id }}");

        $('.btnAdd').click(function() {
            $('#add_soap').collapse('show');
        });

        $('#tutup').on('click', function() {
            $('#add_soap').collapse('hide');
            $('.btnAdd').attr('aria-expanded', 'false').addClass('collapsed');
        });

        // Inisialisasi Select2 & Datepicker untuk filter
        $('#view-filter-soap .sel2').select2({
            placeholder: "Pilih Opsi",
            allowClear: true
        });

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom'
        });

        // ===================================================================
        // FUNGSI UTAMA UNTUK MEMUAT DAN MERENDER DATA CPPT
        // ===================================================================
        function loadAndRenderCPPT(targetElementId, apiUrl, filterData) {
            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                data: filterData, // Menggunakan data filter yang dikirim
                success: function(response) {
                    const targetElement = $(`#${targetElementId}`);
                    targetElement.empty(); // Kosongkan container

                    if (response.length === 0) {
                        targetElement.html(
                            '<tr><td colspan="3" class="text-center">Tidak ada data CPPT yang ditemukan.</td></tr>'
                        );
                        return;
                    }

                    // Kelompokkan data berdasarkan tanggal
                    const groupedData = response.reduce((acc, data) => {
                        const date = new Date(data.created_at).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        if (!acc[date]) acc[date] = [];
                        acc[date].push(data);
                        return acc;
                    }, {});

                    // Urutkan tanggal dari yang terbaru ke terlama
                    const sortedDates = Object.keys(groupedData).sort((a, b) => new Date(b) -
                        new Date(a));

                    sortedDates.forEach(date => {
                        const entries = groupedData[date];

                        // Tambahkan baris pemisah tanggal
                        targetElement.append(`
                            <tr>
                                <td colspan="3" class="text-center font-weight-bold text-white" style="background-color: #886ab5;">${date}</td>
                            </tr>
                        `);

                        // Urutkan entri berdasarkan jam dari yang terbaru ke terlama
                        entries.sort((a, b) => new Date(b.created_at) - new Date(a
                            .created_at));

                        entries.forEach(data => {
                            const formattedDate = new Intl.DateTimeFormat('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                                timeZone: 'Asia/Jakarta'
                            }).format(new Date(data.created_at));

                            // Template HTML yang sama untuk dokter dan perawat
                            const rowHtml = `
                                <tr>
                                    <td class="text-center" style="width: 25%;">
                                        <div class="text-primary mt-3 font-weight-bold">${formattedDate}</div>
                                        <div class="text-success mt-2">${data.tipe_rawat.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}</div>
                                        <div class="mt-2 text-info">${data.user.name}</div>
                                        <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary mt-2"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div class="mt-2">
                                            ${data.signature_url ? `<img src="${data.signature_url}" style="width: 150px; height: auto;">` : ''}
                                        </div>
                                    </td>
                                    <td>
                                        <table class="table-soap nurse w-100">
                                            <tbody>
                                                <tr><td colspan="2" class="soap-text title">CPPT</td></tr>
                                                <tr><td class="soap-text text-center font-weight-bold" width="8%">S</td><td>${(data.subjective || '').replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text text-center font-weight-bold">O</td><td>${(data.objective || '').replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text text-center font-weight-bold">A</td><td>${(data.assesment || '').replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text text-center font-weight-bold">P</td><td>${(data.planning || '').replace(/\n/g, "<br>")}</td></tr>
                                                ${data.evaluasi ? `<tr><td class="soap-text text-center font-weight-bold">E</td><td>${(data.evaluasi || '').replace(/\n/g, "<br>")}</td></tr>` : ''}
                                                ${data.instruksi ? `<tr><td class="soap-text text-center font-weight-bold">I</td><td>${(data.instruksi || '').replace(/\n/g, "<br>")}</td></tr>` : ''}
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="text-center" style="width: 5%;">
                                        <div class="d-flex flex-column">
                                            <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap mb-2" data-id="${data.id}" title="Copy"></i>
                                            <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap mb-2" data-id="${data.id}" title="Hapus"></i>
                                            <i class="mdi mdi-pencil blue-text pointer mdi-18px edit-soap mb-2" data-id="${data.id}" title="Edit"></i>
                                            <i class="mdi mdi-printer green-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print"></i>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            targetElement.append(rowHtml);
                        });
                    });
                },
                error: function(xhr) {
                    console.error("Error loading CPPT data:", xhr.responseText);
                    $(`#${targetElementId}`).html(
                        '<tr><td colspan="3" class="text-center text-danger">Gagal memuat data.</td></tr>'
                    );
                }
            });
        }

        // ===================================================================
        // FUNGSI UNTUK MENJALANKAN FILTER
        // ===================================================================
        function applyFilters() {
            // Kumpulkan semua data filter ke dalam satu objek
            const filters = {
                registration_id: "{{ $registration->id }}",
                start_date: $('#sdate').val(),
                end_date: $('#edate').val(),
                care_status: $('#dept').val(),
                cppt_type: $('#role').val()
            };

            // Panggil fungsi render untuk dokter dan perawat dengan filter yang sama
            loadAndRenderCPPT('list_soap_dokter', '{{ route('cppt-dokter.get') }}', filters);
            loadAndRenderCPPT('list_soap_perawat', '{{ route('cppt.get') }}', filters);
        }

        // ===================================================================
        // EVENT LISTENERS DAN PEMANGGILAN AWAL
        // ===================================================================

        // 1. Terapkan filter saat tombol diklik
        $('#btn-apply-filter').on('click', function() {
            applyFilters();
        });

        // 2. Muat data awal saat halaman pertama kali dibuka
        applyFilters();

    });
</script>
