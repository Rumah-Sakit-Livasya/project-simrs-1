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
                            <h2 class="font-weight-bold">
                                <i class="mdi mdi-flask-outline mdi-24px"></i> ORDER LABORATORIUM
                            </h4>
                        </header>
                        <hr style="border-color: #868686; margin-top: 30px; margin-bottom: 30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Dokter</th>
                                                <th>Alat</th>
                                                <th>Jml</th>
                                                <th>Kelas</th>
                                                <th>Lokasi</th>
                                                <th>Entry By</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be added here dynamically -->
                                           
                                        </tbody>
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


    <div class="modal fade" id="modal-tambah-alat" tabindex="-1" aria-hidden="true" data-id="{{ $registration->id }}">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Tambah Pemakaian Alat Medis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                    @csrf
                    @method('post')
                    <input type="hidden" id="registration" value="{{ $registration->id }}">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="tglOrder" class="col-sm-3 col-form-label">Tgl Order</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tglOrder" placeholder="Pilih tanggal"
                                    value={{ now()->format('Y-m-d') }}>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="doctor" class="col-sm-3 col-form-label">Dokter</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-dropdown" id="doctor" style="width: 100%;">
                                    {{-- @foreach ($doctors as $doctor)
                                        @if ($doctor->id == $registration->doctor_id)
                                            <option value="{{ $doctor->id }}" selected>
                                                {{ $doctor?->employee?->fullname }}
                                            </option>
                                        @else
                                            <option value="{{ $doctor->id }}">
                                                {{ $doctor?->employee?->fullname }}
                                            </option>
                                        @endif
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="departement" class="col-sm-3 col-form-label">Poliklinik</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-dropdown" id="departement" style="width: 100%;">
                                    @foreach ($departements as $departement)
                                        <option value="{{ $departement->id }}"
                                            data-groups="{{ $departement->grup_tindakan_medis ? json_encode($departement->grup_tindakan_medis->toArray()) : '[]' }}">
                                            {{ $departement->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kelas" class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-dropdown" id="kelas" style="width: 100%;">
                                    <option value="{{ $registration->kelas_rawat_id }}">
                                        {{ $registration->kelas_rawat?->kelas }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alat_medis" class="col-sm-3 col-form-label">Alat Medis</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-dropdown" id="alat_medis" style="width: 100%;">
                                    <option value="" selected>Pilih Alat Medis</option>
                                    {{-- @dd($tindakan_medis) --}}
                                    {{-- @foreach ($list_peralatan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="lokasi" class="col-sm-3 col-form-label">Lokasi</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-dropdown" id="lokasi" style="width: 100%;">
                                    <option value="">Pilih Alat Medis</option>
                                    <option value="OK">OK</option>
                                    <option value="KTD">KTD</option>
                                    <option value="VK">VK</option>
                                    <option value="LAINNYA">LAINNYA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="qty" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.poliklinik.partials.action-js.pemakaian_alat')
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');

            $('.select2-dropdown').select2({
                placeholder: 'Pilih item berikut',
                dropdownParent: $('#modal-tambah-alat')
            });

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
