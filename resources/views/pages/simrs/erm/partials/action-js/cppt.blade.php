<script>
    $(document).ready(function() {
        $('#cppt_doctor_id').val("{{ $registration->doctor_id }}")
        $('.btnAdd').click(function() {
            $('#add_soap').collapse('show');
        });

        $('#tutup').on('click', function() {
            $('#add_soap').collapse('hide');

            $('.btnAdd').attr('aria-expanded', 'false');
            $('.btnAdd').addClass('collapsed');
        });

        function loadCPPTDokter() {
            $.ajax({
                url: `{{ route('cppt-dokter.get') }}`,
                data: {
                    registration_id: "{{ $registration->id }}",
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#list_soap_dokter').empty();

                    // Group data by date
                    const groupedData = response.reduce((acc, data) => {
                        const date = new Date(data.created_at).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        if (!acc[date]) {
                            acc[date] = [];
                        }
                        acc[date].push(data);
                        return acc;
                    }, {});

                    // Sort dates in descending order
                    const sortedDates = Object.keys(groupedData).sort((a, b) => {
                        return new Date(b) - new Date(a);
                    });

                    // Iterate over sorted dates
                    sortedDates.forEach(date => {
                        const entries = groupedData[date];

                        // Add date separator row
                        $('#list_soap_dokter').append(`
                    <tr>
                        <td colspan="3" class="text-center text-bold text-white" style="background-color: #886ab5;">${date}</td>
                    </tr>
                `);

                        // Sort entries by time in descending order
                        entries.sort((a, b) => new Date(b.created_at) - new Date(a
                            .created_at));
                        // Add each entry for the date
                        entries.forEach(data => {
                            let formattedDate = new Intl.DateTimeFormat('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                                timeZone: 'Asia/Jakarta'
                            }).format(new Date(data.created_at));
                            var row = `
                                <tr>
                                    <td class="text-center">
                                        <div class="text-primary mt-3" style="font-weight:400;">${formattedDate}</div>
                                        <div class="text-success mt-3" style="font-weight:400;">${data.tipe_rawat}</div>
                                        <div class="input-oleh text-warning mb-2 mt-1">${data.user.name}</div>
                                        <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            ${data.signature_url ? `<img src="${data.signature_url}" width="200px;" height="100px;">` : ''}
                                        </div>
                                    </td>
                                    <td>
                                        <table width="100%" class="table-soap nurse">
                                            <tbody>
                                                <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
                                        <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
                                        <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
                                        <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
                                    </td>
                                </tr>
                                `;
                            // Tambahkan ke dalam tabel
                            $('#list_soap_dokter').append(row);
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function loadCPPTPerawat() {
            $.ajax({
                url: '{{ route('cppt.get') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    registration_id: "{{ $registration->id }}",
                },
                success: function(response) {
                    // Bersihkan isi tbody
                    $('#list_soap_perawat').empty();

                    // Group data by date
                    const groupedData = response.reduce((acc, data) => {
                        const date = new Date(data.created_at).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        if (!acc[date]) {
                            acc[date] = [];
                        }
                        acc[date].push(data);
                        return acc;
                    }, {});

                    // Iterate over grouped data and add to the table
                    for (const [date, entries] of Object.entries(groupedData)) {
                        // Add date separator row
                        $('#list_soap_perawat').append(`
                            <tr>
                                <td colspan="3" class="text-center text-bold text-white" style="background-color: #886ab5;">${date}</td>
                            </tr>
                        `);

                        // Add each entry for the date
                        entries.forEach(data => {
                            let formattedDate = new Intl.DateTimeFormat('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                                timeZone: 'Asia/Jakarta'
                            }).format(new Date(data.created_at));
                            var row = `
                                <tr>
                                    <td class="text-center">
                                        <div class="deep-purple-text">
                                            <div class="text-primary mt-3" style="font-weight:400;">${formattedDate}</div>
                                            <div class="text-success mt-3" style="font-weight:400;">${data.tipe_rawat}</div>
                                            <div class="input-oleh text-warning mb-2 mt-1">${data.user.name}</div>
                                            <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                            <div>
                                                ${data.signature_url ? `<img src="${data.signature_url}" width="200px;" height="100px;">` : ''}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <table width="100%" class="table-soap nurse">
                                            <tbody>
                                                <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning.replace(/\n/g, "<br>")}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">E</td><td>${data.evaluasi}</td></tr>
                                                <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
                                        <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
                                        <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
                                        <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
                                    </td>
                                </tr>
                            `;
                            $('#list_soap_perawat').append(row);
                        });
                    }

                    // Inisialisasi ulang DataTable
                    $('#cppt-perawat').DataTable({
                        paging: false,
                        searching: false,
                        ordering: false,
                        responsive: true,
                        pageLength: 5,
                        info: false // <-- ini yang menyembunyikan "Showing x to y of z entries"
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        loadCPPTPerawat();
        loadCPPTDokter();
    });
</script>
