@extends('pages.simrs.erm.index')
@section('erm')
    <div class="card">
        <form id="form-asesmen-awal-ranap-lansia" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <h3 class="text-center text-success font-weight-bold">ASESMEN AWAL KEPERAWATAN RAWAT INAP LANSIA</h3>
                <hr>

                {{-- Bagian-bagian form di-include di sini --}}
                {{-- Kita bisa menggunakan kembali BANYAK partial dari form dewasa karena isinya sama --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-info-masuk', ['data' => $data])
                @include('pages.simrs.erm.form.perawat.component.asesmen-riwayat-kesehatan', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-psikososial', ['data' => $data])
                @include('pages.simrs.erm.form.perawat.component.asesmen-pemeriksaan-fisik', [
                    'data' => $data,
                ])

                {{-- Partial KHUSUS LANSIA untuk Nyeri, Status Fungsional, dan Jatuh --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-lansia-nyeri-fungsional-jatuh', [
                    'data' => $data,
                ])

                @include('pages.simrs.erm.form.perawat.component.asesmen-masalah-keperawatan', [
                    'data' => $data,
                ])

                {{-- Tanda Tangan --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6 offset-md-3">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Perawat Asesmen',
                            'name_prefix' => 'signatures[perawat_asesmen_lansia]',
                            'role' => 'perawat_asesmen_lansia',
                            'index' => 'asesmen_awal_lansia_perawat',
                            'signature_model' => $pengkajian->signatures()->where('role', 'perawat_asesmen_lansia')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-asesmen-lansia">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- JavaScript untuk kalkulasi skor lansia dan submit form --}}
    @include('pages.simrs.erm.partials.action-js.asesmen-lansia-js')
@endpush
