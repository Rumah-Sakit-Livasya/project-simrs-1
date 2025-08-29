@extends('pages.simrs.erm.index')

@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-pengkajian-awal-neonatus" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $form_data = $pengkajian->data ?? []; @endphp

            <div class="card-body">
                <h3 class="text-center text-primary font-weight-bold">PENGKAJIAN AWAL MEDIS NEONATUS</h3>
                <hr>

                {{-- Include semua bagian form sebagai partials --}}
                @include('pages.simrs.erm.form.dokter.component.neonatus-anamnesis', [
                    'data' => $form_data,
                ])
                @include('pages.simrs.erm.form.dokter.component.neonatus-vital-apgar', [
                    'data' => $form_data,
                ])
                @include('pages.simrs.erm.form.dokter.component.neonatus-status-generalis', [
                    'data' => $form_data,
                ])
                @include('pages.simrs.erm.form.dokter.component.neonatus-penunjang-diagnosis', [
                    'data' => $form_data,
                ])
                @include('pages.simrs.erm.form.dokter.component.neonatus-program-kerja', [
                    'data' => $form_data,
                ])
                @include('pages.simrs.erm.form.dokter.component.neonatus-kriteria-pulang', [
                    'data' => $form_data,
                ])

                <hr>
                {{-- Tanda Tangan --}}
                <div class="row text-center mt-4">
                    <div class="col-md-6">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Dokter Pemeriksa',
                            'name_prefix' => 'signatures[dokter_pemeriksa]',
                            'role' => 'dokter_pemeriksa',
                            'index' => 'neonatus_dokter_pemeriksa',
                            'signature_model' => $pengkajian->signatures()->where('role', 'dokter_pemeriksa')->first(),
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Verifikasi DPJP',
                            'name_prefix' => 'signatures[dpjp]',
                            'role' => 'dpjp',
                            'index' => 'neonatus_dpjp',
                            'signature_model' => $pengkajian->signatures()->where('role', 'dpjp')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-pengkajian-neonatus">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('pages.simrs.erm.form.dokter.component.js.neonatus-js')
@endpush
