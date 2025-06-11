@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    <style>
        main {
            overflow-x: hidden;
        }

        #js-slide-left .position-fixed {
            width: unset !important;
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

        aside.page-sidebar,
        .page-content-wrapper {
            max-height: 100vh;
        }

        #js-slide-left .position-fixed {
            width: 18.7%;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- notice the utilities added to the wrapper below -->
        <div class="d-flex flex-grow-1 p-0 shadow-1 layout-composed">
            <!-- left slider panel : must have unique ID-->

            {{-- FILTER PASIEN --}}
            <div id="js-slide-left"
                class="h-100 flex-wrap flex-shrink-0 position-relative slide-on-mobile slide-on-mobile-left bg-primary-200 pattern-0 pt-3">
                <div class="position-fixed h-100" style="width: 20rem !important">
                    <row class="justify-content-center">
                        <div class="col">
                            <form action="javascript:void(0)" method="POST" id="filter_pasien">
                                @csrf
                                <div class="form-group mb-2">
                                    <select
                                        class="select2 form-control @error('departement_id') is-invalid @enderror filter-pasien"
                                        name="departement_id" id="departement_id">
                                        @if ($path === 'igd')
                                            @foreach ($departements as $departement)
                                                <option value=""></option>
                                                <option value="{{ $departement->id }}"
                                                    {{ $departement->id == $departement->id ? 'selected' : '' }}>
                                                    {{ $departement->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($departements as $departement)
                                                <option value=""></option>
                                                <option value="{{ $departement->id }}"
                                                    {{ ($registration->departement_id ?? '') == $departement->id ? 'selected' : '' }}>
                                                    {{ $departement->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('departement_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <select
                                        class="select2 form-control @error('doctor_id') is-invalid @enderror filter-pasien"
                                        name="doctor_id" id="doctor_id">
                                        <option value=""></option>
                                    </select>

                                    @error('doctor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <input type="text" id="nama_pasien" name="nama_pasien"
                                        class="form-control filter-pasien" placeholder="Nama Pasien">
                                </div>
                                <div class="form-group">
                                    {{-- <button type="submit" class="btn btn-primary w-100">Submit</button> --}}
                                </div>
                            </form>

                            {{-- DAFTAR PASIEN --}}
                            @include('pages.simrs.erm.partials.list-pasien')
                        </div>
                    </row>
                </div>
            </div>

            <!-- middle content area -->
            <div class="d-flex flex-column flex-grow-1 bg-white">
                {{-- Menu --}}
                @include('pages.simrs.erm.partials.menu')


                {{-- content start --}}
                @if (isset($registration) || $registration != null)
                    @yield('erm')
                @else
                    <div class="row" style="height: 90%">
                        <div class="col-lg-12 d-flex align-items-center justify-content-center">
                            <div class="logo-dashboard-simrs text-center">
                                <h3 class="text-center spaced-text gradient-text">COMING SOON</h3>
                                <img src="{{ asset('img/logo.png') }}" width="130" height="130" alt="Logo RS">
                                <h3 class="text-center spaced-text mt-3">RUMAH SAKIT LIVASYA</h3>
                                <p style="letter-spacing: 0.2em">Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka
                                    Telp
                                    081211151300</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
    @include('pages.simrs.erm.partials.ttd')
    @include('pages.simrs.erm.partials.ttd-many')
@endsection
@section('plugin')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        function openSignaturePad(index, target) {
            $('#btn_save_ttd').attr('data-target', target);
            $('#signatureModal').modal('show');
        }

        function saveSignature() {
            if (!hasDrawn) {
                alert("Silakan buat tanda tangan terlebih dahulu.");
                return;
            }

            const dataURL = canvas.toDataURL('image/png');
            const target = $('#btn_save_ttd').attr('data-target');

            // Simpan base64 ke input hidden
            $('#signature_image').val(dataURL);

            // Tampilkan preview image
            $('#signature_preview').attr('src', dataURL).show();

            // Tutup modal dan bersihkan canvas
            $('#signatureModal').modal('hide');
            clearCanvas();
        }

        let currentSignatureIndex = null;

        function openSignaturePadMany(index) {
            currentSignatureIndex = index;

            // Tampilkan modal
            $('#signatureModalMany').data('target-index', index).modal('show');

            // Atur data-index di canvas
            const canvas = document.getElementById('canvas-many');
            if (canvas) {
                canvas.setAttribute('data-index', index);
                clearCanvas(); // bersihkan canvas sebelum mulai tanda tangan baru
            }
        }


        function saveSignatureMany() {
            if (!hasDrawn) {
                alert("Silakan buat tanda tangan terlebih dahulu.");
                return;
            }

            const dataURL = canvas.toDataURL('image/png');

            // Masukkan ke input sesuai index
            document.querySelectorAll('input[name="signature_image[]"]')[currentSignatureIndex].value = dataURL;

            // Tampilkan preview (jika kamu pakai ID dinamis, sesuaikan)
            const previews = document.querySelectorAll('#signature_preview');
            if (previews[currentSignatureIndex]) {
                previews[currentSignatureIndex].src = dataURL;
                previews[currentSignatureIndex].style.display = 'block';
            }

            $('#signatureModalMany').modal('hide');
        }


        $(document).ready(function() {
            const pengkajian = @json($pengkajian ?? []);

            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });

            function loadDoctors(departementId) {
                if (departementId) {
                    $.ajax({
                        url: '/api/simrs/erm/get-jadwal-dokter/' + departementId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#doctor_id').empty();
                            $('#doctor_id').append('<option value=""></option>');
                            $.each(data, function(key, value) {
                                $('#doctor_id').append('<option value="' + value.doctor_id +
                                    '">' + value.doctor_name + '</option>');
                            });

                            // Preselect if available
                            var selectedDoctorId = $('#doctor_id').data('selected');
                            if (selectedDoctorId) {
                                $('#doctor_id').val(selectedDoctorId).trigger('change');
                            }
                        }
                    });
                } else {
                    $('#doctor_id').empty().append('<option value=""></option>');
                }
            }

            $(window).on('load', function() {
                loadDoctors($('#departement_id').val());
            });

            $('#departement_id').on('change', function() {
                loadDoctors($(this).val());
            });


            if (pengkajian) {
                $('#diagnosa-keperawatan').val(pengkajian.diagnosa_keperawatan).select2();
                $('#rencana-tindak-lanjut').val(pengkajian.rencana_tindak_lanjut).select2();
                $('#nyeri').val(pengkajian.nyeri).select2();
                $('#penurunan_bb').val(pengkajian.penurunan_bb).select2();
                $('#asupan_makan').val(pengkajian.asupan_makan).select2();
                $('#status_psikologis').val(pengkajian.status_psikologis).select2();
                $('#status_spiritual').val(pengkajian.status_spiritual).select2();
                $('#penghasilan').val(pengkajian.penghasilan).select2();
            }

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });

            // FILTER PASIEN
            $('.filter-pasien').on('change', function(e) {
                e.preventDefault(); // Mencegah form submit langsung
                console.log('changed')
                const path = "{{ $path }}";
                $.ajax({
                    url: `/api/simrs/erm/filter-pasien/${path}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // Tambahkan token CSRF
                        route: window.location.href,
                        departement_id: $('#filter_pasien #departement_id').val(),
                        doctor_id: $('#filter_pasien #doctor_id').val(),
                        patient: $('#filter_pasien #nama_pasien').val()
                    },

                    dataType: "json",
                    beforeSend: function() {
                        $('#daftar-pasien .col-12').html(
                            '<p>Sedang memuat...</p>'); // Tambahkan loading
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#daftar-pasien .col-12').html(response.html);
                        } else {
                            $('#daftar-pasien .col-12').html(
                                '<p>Tidak ada data pasien.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Terjadi kesalahan, silakan coba lagi.");
                    }
                });
            });


            // if ($('#filter_pasien #departement_id').val() != null || $('#filter_pasien #doctor_id').val() !=
            //     null) {
            //     $.ajax({
            //         type: "POST",
            //         data: {
            //             _token: "{{ csrf_token() }}", // Tambahkan token CSRF
            //             route: window.location.href,
            //             departement_id: $('#filter_pasien #departement_id').val(),
            //             doctor_id: $('#filter_pasien #doctor_id').val()
            //         },
            //         dataType: "json",
            //         beforeSend: function() {
            //             $('#daftar-pasien .col-12').html(
            //                 '<p class="text-center mt-3">Sedang memuat...</p>'); // Tambahkan loading
            //         },
            //         success: function(response) {


            //             if (response.success) {
            //                 $('#daftar-pasien .col-12').html(response.html);
            //             } else {
            //                 $('#daftar-pasien .col-12').html(
            //                     '<p>Tidak ada data pasien.</p>');
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             console.log(xhr.responseText);
            //             alert("Terjadi kesalahan, silakan coba lagi.");
            //         }
            //     });
            // }
        });
    </script>
    @yield('plugin-erm')
    @include('pages.simrs.poliklinik.partials.action-js.pengkajian-perawat')
@endsection
