@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="rujuk-antar-rs" method="POST">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">SURAT RUJUKAN</h2>
                    </header>
                    <div class="row mt-5 justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Tanggal & Jam Masuk</label>
                                <input type="date" name="tgl_masuk" class="form-control"
                                    value="{{ $rujuk?->tgl_masuk ?? now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">IDENTITAS RUJUK</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="nama_ts" class="control-label text-primary">Nama Teman Sejawat</label>
                                <input type="text" name="nama_ts" id="nama_ts" class="form-control"
                                    value="{{ $rujuk?->nama_ts }}">
                            </div>
                            <div class="form-group">
                                <label for="nama_pasien" class="control-label text-primary">Nama Pasien</label>
                                <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" readonly
                                    value="{{ $rujuk?->nama_pasien ?? $registration->patient->name }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label text-primary">Alasan Pasien Keluar</label>
                                <select name="alasan_keluar" class="select2 form-select">
                                    <option></option>
                                    <option value="Ruang Rawat Inap Penuh"
                                        {{ ($rujuk?->alasan_keluar ?? '') == 'Ruang Rawat Inap Penuh' ? 'selected' : '' }}>
                                        Ruang Rawat Inap Penuh
                                    </option>
                                    <option value="Sarana/Prasarana Tidak Tersedia"
                                        {{ ($rujuk?->alasan_keluar ?? '') == 'Sarana/Prasarana Tidak Tersedia' ? 'selected' : '' }}>
                                        Saran/PrasaranaTidak Tersedia
                                    </option>
                                    <option value="Kebutuhan Spesialistik Tidak Tersedia"
                                        {{ ($rujuk?->alasan_keluar ?? '') == 'Kebutuhan Spesialistik Tidak Tersedia' ? 'selected' : '' }}>
                                        Kebutuhan Spesialistik TidakTersedia
                                    </option>
                                    <option value="Atas Permintaan Sendiri"
                                        {{ ($rujuk?->alasan_keluar ?? '') == 'Atas Permintaan Sendiri' ? 'selected' : '' }}>
                                        Atas Permintaan Sendiri
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="nama_rs" class="control-label text-primary">Nama Rumah Sakit</label>
                                <input type="text" name="nama_rs" id="nama_rs" class="form-control"
                                    value="{{ $rujuk?->nama_rs }}">
                            </div>
                            <div class="form-group">
                                <label for="umur_pasien" class="control-label text-primary">Umur Pasien</label>
                                <input type="text" name="umur_pasien" id="umur_pasien" class="form-control" readonly
                                    value="{{ $rujuk?->umur_pasien ?? \Carbon\Carbon::parse($registration->patient->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="rs_tujuan" class="control-label text-primary">Rumah Sakit Tujuan</label>
                                <input type="text" name="rs_tujuan" id="rs_tujuan" class="form-control"
                                    value="{{ $rujuk?->rs_tujuan }}">
                            </div>
                            <div class="form-group">
                                <label for="alamat_pasien" class="control-label text-primary">Alamat Pasien</label>
                                <input type="text" name="alamat_pasien" id="alamat_pasien" class="form-control" readonly
                                    value="{{ $rujuk?->alamat_pasien ?? $registration->patient->address }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="dokter_penerima" class="control-label text-primary">Dokter Penerima</label>
                                <input type="text" name="dokter_penerima" id="dokter_penerima" class="form-control"
                                    value="{{ $rujuk?->dokter_penerima }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label text-primary">Tanggal Masuk & Keluar</label>
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control" readonly
                                        value="{{ $rujuk?->tgl_masuk ?? $registration?->registration_date }}">
                                    <input type="text" name="tgl_keluar" class="form-control" readonly
                                        value="{{ $rujuk?->tgl_keluar ?? ($registration?->registration_close_date ?? 'Belum Keluar') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">PEMERIKSAAN PENUNJANG</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary" for="laboratorium">Laboratorium</label>
                                <textarea class="form-control" name="pemeriksaan_laboratorium" id="laboratorium" rows="3">{{ $rujuk?->pemeriksaan_laboratorium }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label text-primary" for="radiologi">Radiologi</label>
                                <textarea class="form-control" name="pemeriksaan_radiologi" id="radiologi" rows="3">{{ $rujuk?->pemeriksaan_radiologi }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label text-primary" for="lainnya">Lainnya</label>
                                <textarea class="form-control" name="pemeriksaan_lainnya" id="lainnya" rows="3">{{ $rujuk?->pemeriksaan_lainnya }}</textarea>
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">DIAGNOSA MASUK</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                {{-- <label class="form-label" for="example-textarea">Text area</label> --}}
                                <textarea class="form-control" name="diagnosa_masuk" id="example-textarea" rows="5">{{ $rujuk?->diagnosa_masuk }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary" for="tindakan_dan_terapi">Tindakan dan
                                    Terapi</label>
                                <textarea class="form-control" name="tindakan_dan_terapi" id="tindakan_dan_terapi" rows="3">{{ $rujuk?->tindakan_dan_terapi }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary" for="alasan-dirujuk">Alasan Dirujuk</label>
                                <textarea class="form-control" name="alasan_dirujuk" id="alasan-dirujuk" rows="3">{{ $rujuk?->alasan_dirujuk }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6 my-3">
                            <div class="form-group">
                                <label for="edukasi_pasien" class="control-label text-primary">Edukasi terhadap pasien dan
                                    keluarga</label>
                                <input type="text" name="edukasi_pasien" id="edukasi_pasien" class="form-control"
                                    value="{{ $rujuk?->edukasi_pasien }}">
                            </div>
                        </div>
                        <div class="col-md-6 my-3">
                            <div class="form-group">
                                <label for="dpjp" class="control-label text-primary">Dokter Penanggung Jawab</label>
                                <input type="text" name="dpjp" id="dpjp" class="form-control"
                                    value="{{ $rujuk?->dpjp ?? $registration->doctor->employee->fullname }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer mr-2"></span> Print
                                    </button>
                                    <div style="width: 40%" class="d-flex justify-content-end">
                                        {{-- <button type="button"
                                            class="btn mr-2 btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button> --}}
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-triage">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('plugin-erm')
    <script>
        $(document).ready(function() {
            var registrationId = {{ $registration->id }};

            $('#rujuk-antar-rs').on('submit', function(e) {
                console.log('Form submitted with registration ID:', registrationId);
                e.preventDefault(); // mencegah submit form default
                const form = $(this);
                const formData = form.serialize(); // serialize data form
                $.ajax({
                    url: '/api/simrs/erm/rujuk-antar-rs',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showSuccessAlert(response.message || 'Data berhasil disimpan');
                        // lakukan tindakan lain jika perlu
                    },
                    error: function(xhr) {
                        let errMsg = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(errMsg);
                    }
                });
            });
        });
    </script>
@endsection
