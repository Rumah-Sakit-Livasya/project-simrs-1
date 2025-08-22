@extends('pages.simrs.erm.index')

@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-echocardiography" action="javascript:void(0)" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-center text-success font-weight-bold">FORMULIR ECHOCARDIOGRAPHY</h3>
                    <button class="btn btn-info" id="histori_pengkajian" type="button"><i class="fas fa-history"></i> Histori</button>
                </div>
                <hr>

                {{-- Include partials untuk setiap bagian form --}}
                @include('pages.simrs.erm.form.penunjang.partials.echo-single-element', ['data' => $data])
                @include('pages.simrs.erm.form.penunjang.partials.echo-valves', ['data' => $data])
                @include('pages.simrs.erm.form.penunjang.partials.echo-effusion-comment', ['data' => $data])
                @include('pages.simrs.erm.form.penunjang.partials.echo-conclusion-advice', ['data' => $data])

                {{-- Tanda Tangan Dokter (opsional, bisa ditambahkan di sini) --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6 offset-md-3">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Dokter Pemeriksa',
                            'name_prefix' => 'signatures[dokter_pemeriksa]',
                            'role' => 'dokter_pemeriksa',
                            'index' => 'echocardiography_pemeriksa',
                            'signature_model' => $pengkajian->signatures()->where('role', 'dokter_pemeriksa')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="button" class="btn btn-warning save-form" data-status="draft"><i class="fas fa-save"></i> Simpan (Draft)</button>
                <button type="button" class="btn btn-success save-form" data-status="final"><i class="fas fa-check-circle"></i> Simpan (Final)</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('pages.simrs.erm.form.penunjang.partials.echocardiography-js')
@endpush
