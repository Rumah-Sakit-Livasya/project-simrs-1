<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- base css -->
    <link rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <link id="mythemes" rel="stylesheet" media="screen, print" href="/css/themes/cust-theme-3.css">
    <link id="myskins" rel="stylesheet" media="screen, print" href="/css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="/css/miscellaneous/reactions/reactions.css">
    <link rel="stylesheet" media="screen, print" href="/css/miscellaneous/fullcalendar/fullcalendar.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/miscellaneous/jqvmap/jqvmap.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-regular.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/statistics/chartjs/chartjs.css">
    <link rel="stylesheet" media="screen, print" href="/css/statistics/chartist/chartist.css">
    <link rel="stylesheet" media="screen, print" href="/css/statistics/c3/c3.css">
    <link rel="stylesheet" media="screen, print" href="/css/statistics/dygraph/dygraph.css">
    <link rel="stylesheet" media="screen, print" href="/css/notifications/sweetalert2/sweetalert2.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/notifications/toastr/toastr.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-colorpicker/bootstrap-colorpicker.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/dropzone/dropzone.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/ion-rangeslider/ion-rangeslider.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/cropperjs/cropper.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/summernote/summernote.css">
    <link rel="stylesheet" media="screen, print" href="/css/miscellaneous/fullcalendar/fullcalendar.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/miscellaneous/lightgallery/lightgallery.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/page-invoice.css">
    <link rel="stylesheet" media="screen, print" href="/css/theme-demo.css">

    {{-- Boxicons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css' rel='stylesheet'>

    {{-- Sweetalert2 --}}
    <script src="/js/sweetalert2.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .header-btn[data-class='mobile-nav-on'] {
            border-color: #4679cc;
            background-color: #4679cc;
            background-image: -webkit-gradient(linear, left bottom, left top, from(#4679cc), to(#409eff));
            background-image: linear-gradient(to top, #4679cc, #409eff);
            color: #fff;
        }

        .nav-menu li a:hover>[class*='fa-'],
        .nav-menu li a:hover>.ni {
            color: #ffffff !important;
        }

        .nav-menu li a>[class*='fa-'],
        .nav-menu li a>.ni {
            color: #97b1dc !important;
            margin-top: -3px;
        }

        .header-function-fixed:not(.nav-function-top):not(.nav-function-fixed) .page-sidebar .primary-nav {
            margin-top: 3.125rem;
        }

        .bx {
            transform: scale(1.5);
            margin-right: 1rem;
        }

        .page-logo-text {
            flex: .5 0 auto !important;
        }

        .page-header {
            z-index: 1800 !important;
        }

        span.garis {
            display: inline-block;
            width: 30px;
            border: 1px solid #8f8f8f
        }

        @media only screen and (max-width: 768px) {
            #header-search-bar {
                display: none !important;
            }
        }

        @media only screen and (max-width: 400px) {
            span.garis {
                width: 15px;
            }
        }

        .step-round {
            width: calc(2.5rem + 2px) !important;
            line-height: 2.5rem !important;
            font-size: 14px;
        }

        .step-text {
            position: absolute;
            color: black;
            bottom: -30px;
            left: -1px;
            font-size: 12px;
            line-height: 12px;
        }

        .form-heading {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .hidden-content {
            /* opacity: 0; */
            visibility: hidden !important;
            position: absolute !important;
            top: -9999px !important;
            left: -9999px !important;
        }

        .show-content {
            visibility: visible !important;
            position: relative !important;
        }

        #waktu-realtime {
            font-size: 2.5em;
        }

        .notification:not(.notification-loading):before {
            content: "Tidak ada pengajuan!" !important;
        }

        @keyframes fadeIn {
            0% {
                bottom: -9999px !important;
                opacity: 0;
            }

            100% {
                bottom: 0px;
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .global-search input {
            transition: 5s ease-in-out;
            border: none;
        }

        .global-search:hover,
        .input-global:focus .global-search {
            border-bottom: 1px solid #3c6eb4;
        }

        *,
        select optgroup option,
        select option,
        select option {
            font-family: "Poppins", "sans-serif";
        }

        .icon {
            transform: scale(1.8);
            margin-right: 1.1rem;
            opacity: .3;
            margin-left: .1rem;
        }

        .form-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out;
        }

        aside.page-sidebar {
            margin-left: -3px;
        }

        .swal2-popup {
            margin-top: 80px !important;
        }

        #daftar-pasien-poli ul {
            padding: 0px;
            margin-top: 20px;
        }

        #daftar-pasien-poli li {
            display: grid;
            grid-template-columns: 45px 1fr;
            grid-template-rows: repeat(3, 20px);
            font-weight: 300;
            align-items: start;
            margin-top: 5px;
            column-gap: 5px;
            padding: 7px;
        }

        #daftar-pasien-poli li .number {
            grid-row: 1 / 8;
            font-size: 1.5rem;
            align-self: center;
            justify-self: center;
            color: #3f51b5;
        }

        #daftar-pasien-poli li .patient-name {
            color: #3366b9;
            font-size: 0.95rem;
            align-self: center;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            font-weight: 400;
        }

        #daftar-pasien-poli li .birth {
            color: #fd3995;
            font-size: 0.7rem;
            align-self: center;
            font-weight: 400;
        }

        #daftar-pasien-poli li .rm {
            font-size: 0.75rem;
            font-weight: 400;
            color: #6c757d;
        }

        .gradient-text {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .spaced-text {
            letter-spacing: 0.4em;
            font-weight: bold;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .logo-dashboard-simrs {
            width: 100%;
        }

        h4 {
            font-size: 12pt;
            font-weight: 600;
            margin-top: 12px;
        }

        .input-tanggal {
            height: 20px;
        }
    </style>
</head>

<body>
    {!! $formTemplate !!}
    
</body>

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

</html>
