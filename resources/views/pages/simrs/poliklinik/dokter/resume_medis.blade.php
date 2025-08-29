@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    @include('pages.simrs.poliklinik.partials.css-sidebar-custom')
    @include('pages.simrs.poliklinik.partials.css-resume-medis')
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

                        <hr style="border-color: #868686; margin-bottom: 50px;">
                        <header class="text-primary text-center font-weight-bold mb-4">
                            <div id="alert-pengkajian"></div>
                            <h2 class="font-weight-bold MB-3">RINGKASAN PASIEN RAWAT JALAN</h4>
                        </header>
                        <form action="javascript:void(0)" id="resume-medis-rajal-form">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                            @include('pages.simrs.poliklinik.partials.resume-medis.patient-info')
                            @include('pages.simrs.poliklinik.partials.resume-medis.anamnesa')
                            @include('pages.simrs.poliklinik.partials.resume-medis.icd-diagnosis')
                            @include('pages.simrs.poliklinik.partials.resume-medis.icd-procedure')
                            @include('pages.simrs.poliklinik.partials.resume-medis.signature')
                            @include('pages.simrs.poliklinik.partials.resume-medis.actions')
                        </form>
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

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });
        });
    </script>
    @include('pages.simrs.poliklinik.partials.action-js.resume-medis-rajal')
@endsection
