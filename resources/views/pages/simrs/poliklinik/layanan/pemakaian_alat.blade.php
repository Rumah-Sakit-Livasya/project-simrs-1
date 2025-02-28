@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
@include('pages.simrs.poliklinik.partials.css-sidebar-custom')
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
            background-color: #ff5722;
            color: #ffffff;
        }

        .badge.badge-red {
            background-color: #f44336;
            color: #ffffff;
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
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- notice the utilities added to the wrapper below -->
        <div class="d-flex flex-grow-1 p-0 shadow-1 layout-composed">
            <!-- left slider panel : must have unique ID-->
            @include('pages.simrs.poliklinik.partials.filter-poli')

            <!-- middle content area -->
            <div class="d-flex flex-column flex-grow-1 bg-white">

                @include('pages.simrs.poliklinik.partials.menu-erm')

                {{-- content start --}}
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                        @include('pages.simrs.poliklinik.partials.detail-pasien')
                        <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                        <header class="text-primary text-center font-weight-bold mb-4">
                            <div id="alert-pengkajian"></div>
                            <h2 class="font-weight-bold">PEMAKAIAN ALAT</h4>
                        </header>
                        <hr style="border-color: #868686; margin-top: 30px; margin-bottom: 30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Dokter</th>
                                                <th style="white-space: nowrap">Alat</th>
                                                <th style="white-space: nowrap">Jml</th>
                                                <th style="white-space: nowrap">Kelas</th>
                                                <th style="white-space: nowrap">Lokasi</th>
                                                <th style="white-space: nowrap">User Entry</th>
                                                <th style="white-space: nowrap">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tindakan_medis_yang_dipakai as $row)
                                                <tr>
                                                    <td>{{ tgl_waktu($row->created_at) }}</td>
                                                    <td>{{ $row->doctor_id }}</td>
                                                    <td>{{ $row->tindakan_medis_id }}</td>
                                                    <td>{{ $row->kelas_rawat_id }}</td>
                                                    <td>{{ $row->qty }}</td>
                                                    <td>{{ $row->total_harga }}</td>
                                                    <td>{{ $row->user_entry }}</td>
                                                    <td>
                                                        <button class="btn btn-danger py-1 px-2">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Dokter</th>
                                                <th style="white-space: nowrap">Tindakan</th>
                                                <th style="white-space: nowrap">Kelas</th>
                                                <th style="white-space: nowrap">QTY</th>
                                                <th style="white-space: nowrap">F.O.C</th>
                                                <th style="white-space: nowrap">User Entry</th>
                                                <th style="white-space: nowrap">Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- datatable end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            $('#doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });
            $('#dt-basic-example').dataTable({
                responsive: false,
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });
        });
    </script>
    @include('pages.simrs.poliklinik.partials.js-filter')
@endsection
