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
                        @include('pages.simrs.erm.partials.detail-pasien')
                        <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-borderless">
                                    <tbody>
                                        @foreach ($form as $item)
                                            <tr>
                                                <td style="width: 20%;" valign="middle">
                                                    <label class="mt-2">{{ $item->nama_kategori }}</label>
                                                </td>
                                                <td style="width: 3%;" valign="middle">
                                                    <label class="mt-2">:</label>
                                                </td>
                                                <td style="width: 50%;">
                                                    <select class="select2 form-control" name="form_id"
                                                        id="form_id_{{ $item->id }}">
                                                        <option value=""></option>
                                                        @foreach ($item->form_templates as $template)
                                                            <option value="{{ $template->id }}">
                                                                {{ $template->nama_form }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td style="width: 20%;">
                                                    <button class="btn btn-primary tambah-form btn-block">Tambah</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12" style="margin-bottom: 100px;">
                                @foreach ($daftar_pengkajian as $item)
                                    <div class="card mb-2">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="nama-form">
                                                {{ $item->form_template->nama_form }}
                                            </div>
                                            <div class="action-form">
                                                <i class="fas fa-print mr-2 text-primary"></i>
                                                <i class="fas fa-pencil mr-2 text-warning"></i>
                                                <i class="fas fa-trash text-danger"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.poliklinik.partials.js-filter')
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            // $('#doctor_id').select2({
            //     placeholder: 'Pilih Dokter',
            // });

            $('.tambah-form').on('click', function(e) {
                e.preventDefault();

                let idForm = $(this).closest('tr').find('select').val();

                if (idForm) {
                    // Panggil route yang sudah dienkripsi dari Blade
                    let registrationId = "{{ $registration->id }}"; // Ambil registration ID dari Blade
                    let url =
                        "{{ route('poliklinik.pengkajian-lanjutan.show', [':registrationId', ':encryptedId']) }}"
                        .replace(':encryptedId', btoa(idForm)) // Enkripsi dengan Base64
                        .replace(':registrationId', registrationId); // Tambahkan registration ID

                    // Ukuran popup
                    let popupWidth = 1200;
                    let popupHeight = 600;

                    // Hitung posisi tengah
                    let screenWidth = window.screen.width;
                    let screenHeight = window.screen.height;
                    let left = (screenWidth - popupWidth) / 2;
                    let top = (screenHeight - popupHeight) / 2.8;

                    // Buka popup di tengah
                    window.open(url, '_blank',
                        `width=${popupWidth},height=${popupHeight},top=${top},left=${left}`);
                } else {
                    alert('Silakan pilih departement terlebih dahulu.');
                }
            });
        });
    </script>
@endsection
