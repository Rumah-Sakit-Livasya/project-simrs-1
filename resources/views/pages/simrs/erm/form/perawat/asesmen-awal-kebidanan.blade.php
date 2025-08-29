@extends('pages.simrs.erm.index')
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-asesmen-awal-kebidanan" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php $data = $pengkajian->toArray(); @endphp

            <div class="card-body">
                <h3 class="text-center text-success font-weight-bold">ASESMEN AWAL KEBIDANAN</h3>
                <hr>

                {{-- Bagian-bagian form akan di-include di sini --}}
                {{-- Sebagian bisa menggunakan kembali partial dari form dewasa --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-info-masuk', ['data' => $data])

                {{-- Partial KHUSUS Kebidanan --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-kebidanan-riwayat-kehamilan', [
                    'data' => $data,
                ])

                {{-- Menggunakan kembali partial dari form dewasa karena isinya mirip --}}
                @include('pages.simrs.erm.form.perawat.component.asesmen-riwayat-kesehatan', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-psikososial', ['data' => $data])
                @include('pages.simrs.erm.form.perawat.component.asesmen-pemeriksaan-fisik', [
                    'data' => $data,
                ])
                @include('pages.simrs.erm.form.perawat.component.asesmen-nyeri-jatuh', ['data' => $data])

                <h4 class="text-primary mt-4 font-weight-bold">XI. DIAGNOSA KEBIDANAN</h4>
                <div class="form-group">
                    <label>1.</label>
                    <input type="text" name="diagnosa_kebidanan[1]" class="form-control"
                        value="{{ $data['diagnosa_kebidanan'][1] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>2.</label>
                    <input type="text" name="diagnosa_kebidanan[2]" class="form-control"
                        value="{{ $data['diagnosa_kebidanan'][2] ?? '' }}">
                </div>

                {{-- Tanda Tangan --}}
                <hr>
                <div class="row text-center mt-4">
                    <div class="col-md-6 offset-md-3">
                        @include('pages.simrs.erm.partials.signature-many', [
                            'judul' => 'Nama Perawat / Bidan Pengkaji',
                            'name_prefix' => 'signatures[bidan_pengkaji]',
                            'role' => 'bidan_pengkaji',
                            'index' => 'asesmen_kebidanan_pengkaji',
                            'signature_model' => $pengkajian->signatures()->where('role', 'bidan_pengkaji')->first(),
                        ])
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-asesmen-kebidanan">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- JavaScript untuk kalkulasi skor dan submit form --}}
    @include('pages.simrs.erm.partials.action-js.asesmen-kebidanan-js')
@endpush
