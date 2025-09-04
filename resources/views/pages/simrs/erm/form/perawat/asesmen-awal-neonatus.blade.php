@extends('pages.simrs.erm.index')
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-asesmen-awal-ranap-neonatus" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <h3 class="text-center text-success font-weight-bold">ASESMEN AWAL KEPERAWATAN RAWAT INAP NEONATUS</h3>
                <hr>

                {{-- Bagian-bagian form akan di-include di sini --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-neonatus-info-masuk', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-neonatus-riwayat', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-neonatus-keadaan-umum', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-neonatus-penilaian-fisik', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-neonatus-nyeri-masalah', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-neonatus-info-pulang', [
                    'data' => $data,
                ])

                {{-- Tanda Tangan --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Perawat yang memeriksa dan menyerahkan bayi',
                            'name_prefix' => 'signatures[perawat_pemeriksa]',
                            'role' => 'perawat_pemeriksa',
                            'index' => 'asesmen_neonatus_pemeriksa',
                            'signature_model' => $pengkajian->signatures()->where('role', 'perawat_pemeriksa')->first(),
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Yang Menerima Bayi',
                            'name_prefix' => 'signatures[penerima_bayi]',
                            'role' => 'penerima_bayi',
                            'index' => 'asesmen_neonatus_penerima',
                            'signature_model' => $pengkajian->signatures()->where('role', 'penerima_bayi')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-asesmen-neonatus">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- JavaScript untuk kalkulasi skor Apgar, Down Score, FLACC, dll. --}}
    @include('pages.simrs.erm.partials.action-js.asesmen-neonatus-js')
@endpush
