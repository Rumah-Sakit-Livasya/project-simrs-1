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

        // Saat tombol Save Final diklik
        $('#bsSOAP').on('click', function() {
            submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
        });

        function loadCPPTData() {
            $.ajax({
                // url: '{{-- route('cppt.get') --}}', // Mengambil route Laravel
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Bersihkan tabel
                    $('#list_soap').empty();

                    // Iterasi setiap data dan tambahkan ke dalam tabel
                    $.each(response, function(index, data) {
                        var row = `
                        <tr>
                            <td class="text-center">
                                <div class="deep-purple-text">${data.created_at}<br>
                                    <span class="green-text" style="font-weight:400;">${data.tipe_rawat}</span><br>
                                    <b style="font-weight: 400;">Dokter ID: ${data.doctor_id}</b><br>
                                    <div class="input-oleh deep-orange-text">Input oleh: ${data.user_id}</div>
                                    <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                    <div>
                                        <img src="http://192.168.1.253/real/include/images/ttd_blank.png" width="200px;" height="100px;">
                                    </div>
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
                        $('#list_soap').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function submitFormCPPT(actionType) {
            const form = $('#cppt-dokter-rajal-form');
            const registrationNumber = "{{ $registration->registration_number }}";

            const url =
                "{{ route('cppt.store', ['type' => 'rawat-jalan', 'registration_number' => '__registration_number__']) }}"
                .replace('__registration_number__', registrationNumber);

            // Now you can use `url` in your form submission or AJAX request

            let formData = form.serialize(); // Ambil data dari form

            // Tambahkan tipe aksi (draft atau final) ke data form
            formData += '&action_type=' + actionType;

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    if (actionType === 'draft') {
                        showSuccessAlert('Data berhasil disimpan sebagai draft!');
                    } else {
                        showSuccessAlert('Data berhasil disimpan sebagai final!');
                    }
                    setTimeout(() => {
                        console.log('Reloading the page now.');
                        window.location.reload();
                    }, 1000);
                },
                error: function(response) {
                    // Tangani error
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        showErrorAlert(value[0]);
                    });
                }
            });
        }
    });
</script>
