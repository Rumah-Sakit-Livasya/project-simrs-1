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
        // FUNGSI UTAMA UNTUK MEMUAT DAN MERENDER DATA CPPT (VERSI 2 KOLOM)
        // ===================================================================
        function loadSideBySideTimeline(filterData) {
            const targetElement = $('#cppt-container');
            targetElement.html('<div class="text-center p-5">Memuat data CPPT...</div>');

            const getDokterData = $.ajax({
                url: '{{ route('cppt-dokter.get') }}',
                type: 'GET',
                dataType: 'json',
                data: filterData
            });

            const getPerawatData = $.ajax({
                url: '{{ route('cppt.get') }}',
                type: 'GET',
                dataType: 'json',
                data: filterData
            });

            Promise.all([getDokterData, getPerawatData])
                .then(([dokterResponse, perawatResponse]) => {
                    targetElement.empty();

                    const dokterData = dokterResponse || [];
                    const perawatData = perawatResponse || [];

                    if (dokterData.length === 0 && perawatData.length === 0) {
                        targetElement.html(
                            '<div class="text-center p-4">Tidak ada data CPPT yang ditemukan.</div>');
                        return;
                    }

                    // 1. Kumpulkan semua tanggal unik dari kedua dataset
                    const allDates = new Set();
                    [...dokterData, ...perawatData].forEach(item => {
                        const date = new Date(item.created_at).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        allDates.add(date);
                    });

                    // 2. Urutkan tanggal unik dari yang terbaru
                    const sortedUniqueDates = Array.from(allDates).sort((a, b) => new Date(b) - new Date(
                        a));

                    // 3. Kelompokkan setiap dataset berdasarkan tanggal
                    const groupDataByDate = (data) => data.reduce((acc, item) => {
                        const date = new Date(item.created_at).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        if (!acc[date]) acc[date] = [];
                        acc[date].push(item);
                        return acc;
                    }, {});

                    const groupedDokterData = groupDataByDate(dokterData);
                    const groupedPerawatData = groupDataByDate(perawatData);

                    // 4. Render HTML untuk setiap tanggal
                    sortedUniqueDates.forEach(date => {
                        // Tambahkan Header Tanggal yang mencakup kedua kolom
                        targetElement.append(`<div class="daily-cppt-header">${date}</div>`);

                        // Buat baris Bootstrap untuk menampung kedua kolom
                        const dailyRow = $('<div class="row"></div>');
                        targetElement.append(dailyRow);

                        // Buat Kolom Kiri (Dokter)
                        const dokterCol = $('<div class="col-md-6 cppt-column"></div>');
                        if (groupedDokterData[date]) {
                            // Urutkan entri berdasarkan waktu
                            groupedDokterData[date].sort((a, b) => new Date(b.created_at) -
                                new Date(a.created_at));
                            groupedDokterData[date].forEach(data => {
                                dokterCol.append(generateCardHtml(data));
                            });
                        }
                        dailyRow.append(dokterCol);

                        // Buat Kolom Kanan (Perawat)
                        const perawatCol = $('<div class="col-md-6 cppt-column"></div>');
                        if (groupedPerawatData[date]) {
                            // Urutkan entri berdasarkan waktu
                            groupedPerawatData[date].sort((a, b) => new Date(b.created_at) -
                                new Date(a.created_at));
                            groupedPerawatData[date].forEach(data => {
                                perawatCol.append(generateCardHtml(data));
                            });
                        }
                        dailyRow.append(perawatCol);
                    });
                })
                .catch(error => {
                    console.error("Gagal memuat data CPPT:", error);
                    targetElement.html(
                        '<div class="alert alert-danger">Gagal memuat data CPPT. Silakan coba lagi.</div>'
                    );
                });
        }

        // Fungsi terpisah untuk membuat HTML kartu agar kode lebih bersih
        function generateCardHtml(data) {
            const entryTime = new Date(data.created_at).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            let cpptTitle = 'Catatan ' + data.tipe_cppt.charAt(0).toUpperCase() + data.tipe_cppt.slice(1);
            // Anda bisa menambahkan logika kustom di sini jika perlu
            // if (data.tipe_cppt === 'bidan') cpptTitle = 'Catatan Bidan';

            return `
            <div class="cppt-entry-card">
                <div class="cppt-card-header">
                    <div class="info">
                        <div class="status">${data.tipe_rawat.toUpperCase()}</div>
                        <div class="title">${cpptTitle} : ${entryTime}</div>
                        <div class="author">${data.user.name}</div>
                    </div>
                    <div class="cppt-card-actions">
                        <i class="mdi mdi-content-copy pointer copy-soap" data-id="${data.id}" title="Copy"></i>
                        <i class="mdi mdi-pencil pointer edit-soap" data-id="${data.id}" title="Edit"></i>
                        <i class="mdi mdi-delete-forever pointer hapus-soap" data-id="${data.id}" title="Hapus"></i>
                    </div>
                </div>
                <div class="cppt-card-body">
                    ${data.subjective ? `<div class="soap-section"><span class="soap-label">Subjective :</span><div class="soap-content">${data.subjective}</div></div>` : ''}
                    ${data.objective ? `<div class="soap-section"><span class="soap-label">Objective :</span><div class="soap-content">${data.objective}</div></div>` : ''}
                    ${data.assesment ? `<div class="soap-section"><span class="soap-label">Assessment :</span><div class="soap-content">${data.assesment}</div></div>` : ''}
                    ${data.planning ? `<div class="soap-section"><span class="soap-label">Plan :</span><div class="soap-content">${data.planning}</div></div>` : ''}
                    ${data.evaluasi ? `<div class="soap-section"><span class="soap-label">Evaluasi :</span><div class="soap-content">${data.evaluasi}</div></div>` : ''}
                    ${data.instruksi ? `<div class="soap-section"><span class="soap-label">Instruksi :</span><div class="soap-content">${data.instruksi}</div></div>` : ''}
                </div>
            </div>`;
        }

        // ===================================================================
        // FUNGSI FILTER (KINI MEMANGGIL FUNGSI LAYOUT BARU)
        // ===================================================================
        function applyFilters() {
            const filters = {
                registration_id: "{{ $registration->id }}",
                start_date: $('#sdate').val(),
                end_date: $('#edate').val(),
                care_status: $('#dept').val(),
                cppt_type: $('#role').val()
            };

            loadSideBySideTimeline(filters);
        }

        $('#btn-apply-filter').on('click', applyFilters);
        applyFilters();

    });
</script>
