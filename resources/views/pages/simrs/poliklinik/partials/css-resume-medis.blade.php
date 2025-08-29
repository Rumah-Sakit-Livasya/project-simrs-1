<style>
    main {
        overflow-x: hidden;
    }

    input[type="time"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .badge {
        cursor: pointer;
    }

    .badge.badge-orange {
        @apply bg-orange-500 text-white;
    }

    .badge.badge-red {
        @apply bg-red-500 text-white;
    }

    @media (max-width: 768px) {
        .img-baker {
            width: 45%;
            margin-bottom: 1rem;
        }
    }

    @media (min-width: 992px) {
        .nav-function-hidden:not(.nav-function-top) .page-sidebar:hover {
            left: -16.25rem;
            -webkit-transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
            transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
        }

        .nav.nav-tabs.action-erm {
            position: fixed;
            background: #ffffff;
            width: 100%;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 15px;
            z-index: 1;
        }

        .tab-content {
            margin-top: 55px;
        }
    }

    .slide-on-mobile {
        width: 20rem;
    }

    .text-decoration-underline {
        text-decoration: underline;
    }

    .text-secondary {
        font-size: 12px;
    }

    @media only screen and (max-width: 992px) {
        .slide-on-mobile-left {
            border-right: 1px solid rgba(0, 0, 0, 0.09);
            left: 0;
        }

        .slide-on-mobile {
            width: 17rem;
        }
    }

    #toggle-pasien i {
        color: #3366b9;
    }

    #js-slide-left {
        border-right: 1px solid rgba(0, 0, 0, 0.3);
        background: white;
    }

    #js-slide-left.hide {
        display: none;
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
</style>
