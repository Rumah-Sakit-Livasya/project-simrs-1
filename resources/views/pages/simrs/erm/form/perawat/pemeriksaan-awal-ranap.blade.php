@extends('pages.simrs.erm.index')

@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-notes-medical mr-2"></i>
            <h4 class="card-title mb-0">Pemeriksaan Awal Rawat Inap</h4>
        </div>
        <form id="form-pemeriksaan-awal-ranap" action="javascript:void(0);" autocomplete="off">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">

            <div class="card-body">
                <div class="row">
                    {{-- Kolom Kiri --}}
                    <div class="col-lg-6 border-right mb-4 mb-lg-0">
                        <h5 class="text-primary font-weight-bold mb-3">
                            <i class="fas fa-heartbeat mr-1"></i> Tanda Vital & Alergi
                        </h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="vital_sign_pr">Nadi (PR)</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="vital_sign_pr" id="vital_sign_pr" class="form-control" value="{{ $pengkajian->vital_sign_pr ?? '' }}" placeholder="Nadi">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vital_sign_rr">Respirasi (RR)</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="vital_sign_rr" id="vital_sign_rr" class="form-control" value="{{ $pengkajian->vital_sign_rr ?? '' }}" placeholder="Respirasi">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="vital_sign_bp">Tensi (BP)</label>
                                <div class="input-group">
                                    <input type="text" name="vital_sign_bp" id="vital_sign_bp" class="form-control" value="{{ $pengkajian->vital_sign_bp ?? '' }}" placeholder="Tensi">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vital_sign_temperature">Suhu (T)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" min="0" name="vital_sign_temperature" id="vital_sign_temperature" class="form-control" value="{{ $pengkajian->vital_sign_temperature ?? '' }}" placeholder="Suhu">
                                    <div class="input-group-append">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="allergy_medicine">Alergi Obat</label>
                                {{-- Hapus div pembungkus yang tidak perlu. Biarkan inputnya langsung. --}}
                                <input type="text" name="allergy_medicine" id="allergy_medicine" class="form-control" data-role="tagsinput" value="{{ $pengkajian->allergy_medicine ? implode(',', $pengkajian->allergy_medicine) : '' }}" placeholder="Tambah & enter">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="allergy_food">Alergi Makanan</label>
                                {{-- Hapus div pembungkus yang tidak perlu. Biarkan inputnya langsung. --}}
                                <input type="text" name="allergy_food" id="allergy_food" class="form-control" data-role="tagsinput" value="{{ $pengkajian->allergy_food ? implode(',', $pengkajian->allergy_food) : '' }}" placeholder="Tambah & enter">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="diagnosis">Diagnosa</label>
                            <textarea name="diagnosis" id="diagnosis" class="form-control" rows="2" placeholder="Masukkan diagnosa">{{ $pengkajian->diagnosis ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-lg-6">
                        <h5 class="text-primary font-weight-bold mb-3">
                            <i class="fas fa-ruler-vertical mr-1"></i> Antropometri & Catatan
                        </h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="height_badan">Tinggi Badan</label>
                                <div class="input-group">
                                    <input type="number" min="0" id="height_badan" name="anthropometry_height" class="form-control calc-bmi" value="{{ $pengkajian->anthropometry_height ?? '' }}" placeholder="Tinggi Badan">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="weight_badan">Berat Badan</label>
                                <div class="input-group">
                                    <input type="number" min="0" id="weight_badan" name="anthropometry_weight" class="form-control calc-bmi" value="{{ $pengkajian->anthropometry_weight ?? '' }}" placeholder="Berat Badan">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="bmi">Index Massa Tubuh (IMT)</label>
                                <div class="input-group">
                                    <input type="text" id="bmi" name="anthropometry_bmi" class="form-control" readonly value="{{ $pengkajian->anthropometry_bmi ?? '' }}" placeholder="IMT">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg/m²</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kat_bmi">Kategori IMT</label>
                                <input type="text" id="kat_bmi" name="anthropometry_bmi_category" class="form-control" readonly value="{{ $pengkajian->anthropometry_bmi_category ?? '' }}" placeholder="Kategori">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="anthropometry_chest_circumference">Lingkar Dada</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="anthropometry_chest_circumference" id="anthropometry_chest_circumference" class="form-control" value="{{ $pengkajian->anthropometry_chest_circumference ?? '' }}" placeholder="Lingkar Dada">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="anthropometry_abdominal_circumference">Lingkar Perut</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="anthropometry_abdominal_circumference" id="anthropometry_abdominal_circumference" class="form-control" value="{{ $pengkajian->anthropometry_abdominal_circumference ?? '' }}" placeholder="Lingkar Perut">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="registration_notes">Catatan Registrasi</label>
                            <textarea name="registration_notes" id="registration_notes" class="form-control" rows="2" placeholder="Catatan tambahan">{{ $pengkajian->registration_notes ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end bg-white">
                <button type="submit" class="btn btn-success px-4" id="btn-save">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"/>

    {{-- TAMBAHKAN BLOK STYLE INI --}}
    <style>
        .bootstrap-tagsinput {
            width: 100%;
            border: 1px solid #ced4da; /* Samakan dengan border input bootstrap */
            padding: .375rem .75rem; /* Samakan dengan padding input bootstrap */
            border-radius: .25rem; /* Samakan dengan border-radius bootstrap */
        }
        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
            background-color: #007bff; /* Warna biru primer */
            padding: .2em .6em .3em;
            font-size: 85%;
            border-radius: .25rem;
        }
        .bootstrap-tagsinput .tag [data-role="remove"] {
            margin-left: 8px;
            cursor: pointer;
        }
        .bootstrap-tagsinput .tag [data-role="remove"]:after {
            content: "x";
            padding: 0px 2px;
        }
        .bootstrap-tagsinput input {
            border: none;
            box-shadow: none;
            outline: none;
            background-color: transparent;
            padding: 0;
            margin: 0;
            width: auto !important; /* Mencegah input terlalu lebar */
            display: inline-block;
        }
    </style>
    {{-- AKHIR BLOK STYLE --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

    {{-- Include file JS Anda --}}
    @include('pages.simrs.erm.partials.action-js.pemeriksaan-awal-ranap-js')
@endpush
