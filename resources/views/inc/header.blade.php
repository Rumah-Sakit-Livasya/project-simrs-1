<meta charset="utf-8">
<title>
    @yield('title', 'SMART HR') - @yield('mywebname', 'SMART HR')
</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Analytics Dashboard">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
<!-- Call App Mode on ios devices -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- Remove Tap Highlight on Windows Phone IE -->
<meta name="msapplication-tap-highlight" content="no">
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
<link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-colorpicker/bootstrap-colorpicker.css">
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

{{-- Sweetalert2 --}}
<script src="/js/sweetalert2.min.js"></script>

<style>
    .header-btn[data-class='mobile-nav-on'] {
        border-color: #4679cc;
        background-color: #4679cc;
        background-image: -webkit-gradient(linear, left bottom, left top, from(#4679cc), to(#409eff));
        background-image: linear-gradient(to top, #4679cc, #409eff);
        color: #fff;
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
</style>
@yield('extended-css')

{{-- <script src="/js/jspdf.umd.min.js"></script> --}}
<!-- BEGIN Body -->
<!-- Possible Classes

* 'header-function-fixed'         - header is in a fixed at all times
* 'nav-function-fixed'            - left panel is fixed
* 'nav-function-minify'			  - skew nav to maximize space
* 'nav-function-hidden'           - roll mouse on edge to reveal
* 'nav-function-top'              - relocate left pane to top
* 'mod-main-boxed'                - encapsulates to a container
* 'nav-mobile-push'               - content pushed on menu reveal
* 'nav-mobile-no-overlay'         - removes mesh on menu reveal
* 'nav-mobile-slide-out'          - content overlaps menu
* 'mod-bigger-font'               - content fonts are bigger for readability
* 'mod-high-contrast'             - 4.5:1 text contrast ratio
* 'mod-color-blind'               - color vision deficiency
* 'mod-pace-custom'               - preloader will be inside content
* 'mod-clean-page-bg'             - adds more whitespace
* 'mod-hide-nav-icons'            - invisible navigation icons
* 'mod-disable-animation'         - disables css based animations
* 'mod-hide-info-card'            - hides info card from left panel
* 'mod-lean-subheader'            - distinguished page header
* 'mod-nav-link'                  - clear breakdown of nav links

>>> more settings are described inside documentation page >>>
-->
