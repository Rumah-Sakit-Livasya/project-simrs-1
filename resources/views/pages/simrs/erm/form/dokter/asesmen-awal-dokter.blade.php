@extends('pages.simrs.erm.index')

@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-asesmen-awal-dokter" action="javascript:void(0)" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-center text-success font-weight-bold">ASESMEN AWAL DOKTER</h3>
                    <button class="btn btn-info" id="histori_pengkajian" type="button"><i class="fas fa-history"></i>
                        Histori</button>
                </div>
                <hr>

                {{-- Include partials untuk setiap bagian form --}}
                @include('pages.simrs.erm.form.dokter.component.tanda-vital', [
                    'data' => $data,
                    'pengkajian' => $pengkajianNurse,
                ])
                @include('pages.simrs.erm.form.dokter.component.info-masuk', ['data' => $data])
                @include('pages.simrs.erm.form.dokter.component.anamnesis', ['data' => $data])
                @include('pages.simrs.erm.form.dokter.component.pemeriksaan', ['data' => $data])
                @if (isset($registration->department) && Str::of($registration->department->name)->lower()->contains('bedah'))
                    @include('pages.simrs.erm.form.dokter.component.gambar-anatomi', ['data' => $data])
                @endif
                @include('pages.simrs.erm.form.dokter.component.edukasi-rencana', ['data' => $data])

                {{-- Bagian Tanda Tangan --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6 offset-md-3">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Dokter',
                            'name_prefix' => 'signatures[dokter_pemeriksa]',
                            'role' => 'dokter_pemeriksa',
                            'index' => 'asesmen_dokter_pemeriksa',
                            'signature_model' => $pengkajian->signatures()->where('role', 'dokter_pemeriksa')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="button" class="btn btn-warning save-form" data-status="draft"><i class="fas fa-save"></i>
                    Simpan (Draft)</button>
                <button type="button" class="btn btn-success save-form" data-status="final"><i
                        class="fas fa-check-circle"></i> Simpan (Final)</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Sesuaikan path jika berbeda --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/painterro@1.2.78/build/painterro.min.js"></script>
    @include('pages.simrs.erm.form.dokter.component.js.asesmen-awal-dokter-js', ['data' => $data])
@endpush
