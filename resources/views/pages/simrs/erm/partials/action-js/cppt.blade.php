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
                url: '{{ route('cppt.dokter.get') }}',
                type: 'GET',
                dataType: 'json',
                data: filterData
            });

            const getPerawatData = $.ajax({
                url: '{{ route('cppt.perawat.get') }}',
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

        // ===================================================================
        // EVENT LISTENERS UNTUK TOMBOL AKSI BARU (GUNAKAN EVENT DELEGATION)
        // ===================================================================
        const mainForm = $('#cppt-perawat-rajal-form');

        // -- TOMBOL EDIT --
        $('body').on('click', '.edit-cppt-btn', function() {
            const cpptId = $(this).data('id');
            if (!cpptId) return;

            $.get(`{{ url('cppt') }}/${cpptId}/edit`)
                .done(function(data) {
                    // Hapus method spoofing lama jika ada
                    mainForm.find('input[name="_method"]').remove();

                    // Tambahkan method spoofing untuk PUT dan ID
                    mainForm.prepend(`<input type="hidden" name="_method" value="PUT">`);
                    mainForm.prepend(`<input type="hidden" name="cppt_id" value="${data.id}">`);

                    // Isi form dengan data
                    $('#subjective').val(data.subjective);
                    $('#objective').val(data.objective);
                    $('#assesment').val(data.assesment);
                    $('#planning').val(data.planning);
                    $('#evaluasi').val(data.evaluasi);
                    $('#instruksi').val(data.instruksi);
                    // Isi perawat/dokter jika ada
                    // $('#perawat_id').val(data.user_id).trigger('change');

                    // Tampilkan form dan scroll ke sana
                    $('#add_soap').collapse('show');
                    $('html, body').animate({
                        scrollTop: $('#add_soap').offset().top - 100
                    }, 500);
                })
                .fail(function(jqXHR) {
                    alert(jqXHR.responseJSON.error || 'Gagal mengambil data CPPT.');
                });
        });

        // -- TOMBOL COPY --
        // -- TOMBOL COPY BARU (MENGGUNAKAN AJAX) --
        $('body').on('click', '.copy-soap-btn', function() {
            const cpptId = $(this).data('id');
            if (!cpptId) return;

            // Tampilkan loading atau feedback visual (opsional)
            // Swal.fire({ title: 'Menyalin data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

            // Gunakan endpoint 'edit' untuk mendapatkan data mentah yang akurat
            $.get(`{{ url('api/simrs/erm/cppt') }}/${cpptId}/edit`) // Sesuaikan URL dengan route Anda
                .done(function(data) {
                    // Swal.close(); // Tutup loading

                    // 1. Pastikan form dalam mode 'CREATE'
                    mainForm.find('input[name="_method"], input[name="cppt_id"]').remove();
                    mainForm.trigger("reset"); // Reset semua field terlebih dahulu

                    // 2. Isi form dengan data SOAP dari respons AJAX
                    // Gunakan `data.subjective || ''` untuk mencegah 'null' atau 'undefined' tertulis di form
                    $('#subjective').val(data.subjective || '');
                    $('#objective').val(data.objective || '');
                    $('#assesment').val(data.assesment || '');
                    $('#planning').val(data.planning || '');

                    // 3. Evaluasi dan Instruksi tidak di-copy, jadi dikosongkan secara eksplisit
                    $('#evaluasi').val('');
                    $('#instruksi').val('');

                    // 4. Buka panel form dan scroll ke sana
                    $('#add_soap').collapse('show');
                    $('html, body').animate({
                        scrollTop: $('#add_soap').offset().top - 100
                    }, 500);
                })
                .fail(function(jqXHR) {
                    // Swal.close(); // Tutup loading
                    alert(jqXHR.responseJSON.error || 'Gagal mengambil data untuk disalin.');
                });
        });
        // -- TOMBOL SBAR --
        $('body').on('click', '.sbar-btn', function() {
            const cpptId = $(this).data('id');
            $('#sbar_cppt_id').val(cpptId);
            $('#sbar-form').trigger("reset");
            $('#modal-sbar').modal('show');
        });

        $('#save-sbar-btn').click(function() {
            const cpptId = $('#sbar_cppt_id').val();
            $.ajax({
                url: `{{ url('cppt') }}/${cpptId}/sbar`,
                type: 'POST',
                data: $('#sbar-form').serialize(),
                success: function(response) {
                    $('#modal-sbar').modal('hide');
                    Swal.fire('Sukses', response.success, 'success');
                },
                error: function(jqXHR) {
                    alert(jqXHR.responseJSON.error || 'Gagal menyimpan SBAR.');
                }
            });
        });

        // -- TOMBOL HAPUS --
        $('body').on('click', '.delete-cppt-btn', function() {
            const cpptId = $(this).data('id');
            if (!cpptId) return;

            Swal.fire({
                title: 'Anda yakin?',
                text: "Data CPPT ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('cppt') }}/${cpptId}`,
                        type: 'POST', // Gunakan POST dengan _method spoofing
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire('Dihapus!', response.success, 'success');
                            applyFilters(); // Muat ulang data
                        },
                        error: function(jqXHR) {
                            alert(jqXHR.responseJSON.error ||
                                'Gagal menghapus data.');
                        }
                    });
                }
            });
        });

        // -- TOMBOL VERIFIKASI --
        $('body').on('click', '.verify-cppt-btn', function() {
            const cpptId = $(this).data('id');
            // Logika untuk verifikasi
            Swal.fire({
                title: 'Verifikasi CPPT?',
                text: "Anda akan memverifikasi catatan ini.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya, Verifikasi'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('cppt') }}/${cpptId}/verify`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Terverifikasi!', response.success,
                                'success');
                            // Mungkin tambahkan visual feedback (seperti centang hijau) atau reload
                            applyFilters();
                        },
                        error: function(jqXHR) {
                            alert(jqXHR.responseJSON.error ||
                                'Gagal melakukan verifikasi.');
                        }
                    });
                }
            });
        });

        // ===================================================================
        // UPDATE LOGIKA SIMPAN UTAMA
        // ===================================================================
        $('#bsSOAP').off('click').on('click', function(e) {
            e.preventDefault();

            // Pastikan tombol dinonaktifkan setelah klik pertama untuk mencegah double-click
            $(this).prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
            );

            const mainForm = $('#cppt-perawat-rajal-form');
            const cpptId = mainForm.find('input[name="cppt_id"]').val();
            let url, method;

            if (cpptId) {
                // Ini adalah mode UPDATE
                url = `{{ url('cppt') }}/${cpptId}`;
                method = 'POST'; // Kita akan menggunakan POST dengan _method spoofing
            } else {
                // Ini adalah mode CREATE
                const registrationNumber = "{{ $registration->registration_number }}";
                url =
                    "{{ route('cppt.store', ['type' => 'rawat-jalan', 'registration_number' => '__reg__']) }}"
                    .replace('__reg__', registrationNumber);
                method = 'POST';
            }

            $.ajax({
                url: url,
                type: method,
                data: mainForm.serialize(),
                success: function(response) {
                    Swal.fire('Sukses!', response.success || 'Data berhasil disimpan.',
                        'success');
                    mainForm.find('input[name="_method"], input[name="cppt_id"]')
                        .remove(); // Reset form ke mode create
                    mainForm.trigger('reset');
                    $('#add_soap').collapse('hide');
                    applyFilters(); // Muat ulang data
                },
                error: function(jqXHR) {
                    // Tampilkan error validasi
                    if (jqXHR.status === 422) {
                        let errors = Object.values(jqXHR.responseJSON.errors).join('\n');
                        alert('Error Validasi:\n' + errors);
                    } else {
                        alert(jqXHR.responseJSON.error || 'Terjadi kesalahan.');
                    }
                }
            }).always(function() {
                // PENTING: Aktifkan kembali tombol setelah request selesai (baik sukses maupun gagal)
                $('#bsSOAP').prop('disabled', false).html(
                    '<span class="mdi mdi-content-save"></span> Simpan');
            });
        });

        // PASSING AUTHENTICATED USER ID TO JAVASCRIPT
        const loggedInUserId = {{ Auth::id() }};

        // Fungsi terpisah untuk membuat HTML kartu agar kode lebih bersih
        function generateCardHtml(data) {
            const entryTime = new Date(data.created_at).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            let cpptTitle = 'Catatan ' + data.tipe_cppt.charAt(0).toUpperCase() + data.tipe_cppt.slice(1);
            const canModify = (loggedInUserId === data.user_id);

            // VARIABEL BARU UNTUK BLOK TANDA TANGAN
            const signatureBlock = data.signature_url ? `
                <div class="cppt-card-footer">
                    <img src="${data.signature_url}" alt="Tanda Tangan">
                    <span class="author-name">${data.user.name}</span>
                </div>
            ` : ''; // Jika tidak ada TTD, render string kosong

            // KEMBALIKAN TEMPLATE DENGAN BLOK TANDA TANGAN
            return `
                <div class="cppt-entry-card">
                    <div class="cppt-card-header">
                        <div class="info">
                            <div class="status">${data.tipe_rawat.toUpperCase()}</div>
                            <div class="title">${cpptTitle} : ${entryTime}</div>
                            <div class="author">${data.user.name}</div>
                        </div>
                        <div class="cppt-card-actions">
                            <i class="mdi mdi-eye pointer verify-cppt-btn" data-id="${data.id}" title="Verifikasi"></i>
                            <i class="mdi mdi-pencil pointer ${canModify ? 'edit-cppt-btn' : 'text-muted'}" data-id="${data.id}" title="${canModify ? 'Edit' : 'Hanya pembuat yang bisa edit'}"></i>
                            <i class="mdi mdi-file-document pointer sbar-btn" data-id="${data.id}" title="Form SBAR"></i>
                            <i class="mdi mdi-content-copy pointer copy-soap-btn" data-id="${data.id}" title="Copy"></i>
                            <i class="mdi mdi-delete-forever pointer ${canModify ? 'delete-cppt-btn' : 'text-muted'}" data-id="${data.id}" title="${canModify ? 'Hapus' : 'Hanya pembuat yang bisa hapus'}"></i>
                        </div>
                    </div>
                    <div class="cppt-card-body">
                        ${data.subjective ? `<div class="soap-section" id="subjective-${data.id}"><span class="soap-label">Subjective :</span><div class="soap-content">${data.subjective}</div></div>` : ''}
                        ${data.objective ? `<div class="soap-section" id="objective-${data.id}"><span class="soap-label">Objective :</span><div class="soap-content">${data.objective}</div></div>` : ''}
                        ${data.assesment ? `<div class="soap-section" id="assesment-${data.id}"><span class="soap-label">Assessment :</span><div class="soap-content">${data.assesment}</div></div>` : ''}
                        ${data.planning ? `<div class="soap-section" id="planning-${data.id}"><span class="soap-label">Plan :</span><div class="soap-content">${data.planning}</div></div>` : ''}
                        ${data.evaluasi ? `<div class="soap-section"><span class="soap-label">Evaluasi :</span><div class="soap-content">${data.evaluasi}</div></div>` : ''}
                        ${data.instruksi ? `<div class="soap-section"><span class="soap-label">Instruksi :</span><div class="soap-content">${data.instruksi}</div></div>` : ''}
                    </div>
                    ${signatureBlock} {{-- TEMPATKAN BLOK TANDA TANGAN DI SINI --}}
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
