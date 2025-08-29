@extends('pages.simrs.erm.index')
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-asesmen-awal-ranap" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <h3 class="text-center font-weight-bold">ASESMEN AWAL KEPERAWATAN RAWAT INAP</h3>
                <p class="text-center">(Diisi dalam 24 jam pertama pasien masuk ruang rawat)</p>
                <hr>

                {{-- Bagian-bagian form di-include di sini --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-info-masuk', ['data' => $data])
                @include('pages.simrs.erm.form.perawat.component.asesmen-riwayat-kesehatan', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-psikososial', ['data' => $data])
                @include('pages.simrs.erm.form.perawat.component.asesmen-pemeriksaan-fisik', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-nyeri-jatuh', ['data' => $data])
                @include('pages.simrs.erm.form.perawat.component.asesmen-masalah-keperawatan', [
                    'data' => $data,
                ])

                {{-- Tanda Tangan --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6 offset-md-3">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Perawat Asesmen',
                            'name_prefix' => 'signatures[perawat_asesmen]',
                            'role' => 'perawat_asesmen',
                            'index' => 'asesmen_awal_perawat',
                            'signature_model' => $pengkajian->signatures()->where('role', 'perawat_asesmen')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-asesmen">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- JavaScript untuk kalkulasi skor dan submit form --}}
    @include('pages.simrs.erm.partials.action-js.asesmen-js')
@endpush
