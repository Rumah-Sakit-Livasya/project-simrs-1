<!-- base vendor bundle:
DOC: if you remove pace.js from core please note on Internet Explorer some CSS animations may execute before a page is fully loaded, resulting 'jump' animations
+ pace.js (recommended)
+ jquery.js (core)
+ jquery-ui-cust.js (core)
+ popper.js (core)
+ bootstrap.js (core)
+ slimscroll.js (extension)
+ app.navigation.js (core)
+ ba-throttle-debounce.js (core)
+ waves.js (extension)
+ smartpanels.js (extension)
+ src/../jquery-snippets.js (core) -->
<script src="/js/vendors.bundle.js"></script>
<script src="/js/app.bundle.js"></script>
<script type="text/javascript">
    /* Activate smart panels */
    $('#js-page-content').smartPanel();
    // Fungsi untuk menampilkan notifikasi sukses SweetAlert
    function showSuccessAlert(message) {
        // alert("Sukses")
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: message,
            showConfirmButton: false,
            timer: 2000 // Durasi notifikasi dalam milidetik (ms)
        });
    }

    // Fungsi untuk menampilkan notifikasi kesalahan SweetAlert 
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false, // Mencegah penutupan saat klik di luar
            allowEscapeKey: false, // Mencegah penutupan saat tekan tombol ESC
            allowEnterKey: false // Mencegah penutupan saat tekan tombol Enter
        }).then((result) => {
            // Memuat ulang halaman jika pengguna mengklik tombol OK
            if (result.isConfirmed) {
                location.reload();
            }
        });
    }


    function showErrorAlertNoRefresh(message) {
        // alert('Terjadi Kesalahan');
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false, // Mencegah penutupan saat klik di luar
            allowEscapeKey: false, // Mencegah penutupan saat tekan tombol ESC
            allowEnterKey: false // Mencegah penutupan saat tekan tombol Enter
        });
    }

    $(document).ready(function() {
        $('#impersonateModal').on('shown.bs.modal', function() {
            $('#impersonate').select2({
                placeholder: "Select a user",
                dropdownParent: $('#impersonateModal'),
                allowClear: true,
            });
        });

        $('.employeeId').click(function() {
            var employeeId = $(this).data('employee-id');
            var width = screen.width;
            var height = screen.height;
            var popupWindow = window.open('/dashboard/attendances/employee/' + employeeId + '/payroll',
                'popupWindow',
                'width=' + width + ',height=' + height + ',scrollbars=yes');

            popupWindow.onbeforeunload = function() {
                location.reload();
            };
        });

        $('#global_search').on('keyup', function() {
            var query = $(this).val();

            if (query.length > 0) {
                $.ajax({
                    url: '{{ route('patients.search') }}',
                    type: 'GET',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        var results = $('#search-results');
                        results.empty();

                        if (data.length > 0) {
                            $.each(data, function(index, patient) {
                                var latestRegistration = patient.registration
                                    .length > 0 ? patient.registration[0] : null;
                                var link = '';

                                if (latestRegistration && latestRegistration
                                    .status === 'aktif') {
                                    link =
                                        `<a href="/daftar-registrasi-pasien/${latestRegistration.id}/">`;
                                } else {
                                    link =
                                        `<a href="/patients/${patient.id}/">`;
                                }

                                results.append(
                                    `<div class="search-item" style="padding: 10px; border-bottom: 1px solid #ccc;">` +
                                    link +
                                    `<strong>` + patient.name +
                                    `</strong><br>` +
                                    `No RM: ` + patient.medical_record_number +
                                    `<br>` +
                                    `Tgl Lahir: ` + patient.date_of_birth +
                                    `</a>` +
                                    `</div>`
                                );
                            });
                        } else {
                            results.append(
                                '<div class="search-item" style="padding: 10px;">No results found</div>'
                            );
                        }
                    }
                });
            } else {
                $('#search-results').empty();
            }
        });
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#global_search').length) {
            $('#search-results').empty();
        }
    });
</script>

<script src="/js/script.js"></script>

@yield('plugin')
@yield('plugin-secondary')
