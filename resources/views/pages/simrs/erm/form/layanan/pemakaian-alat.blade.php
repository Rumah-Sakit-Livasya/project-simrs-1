@extends('pages.simrs.erm.index')
@section('erm')

    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian"></div>
                    <h2 class="font-weight-bold">PEMAKAIAN ALAT</h4>
                </header>
                <hr style="border-color: #868686; margin-top: 30px; margin-bottom: 30px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dt-pemakaian-alat" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Dokter</th>
                                        <th>Alat</th>
                                        <th>Jml</th>
                                        <th>Kelas</th>
                                        <th>Lokasi</th>
                                        <th>Entry By</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                {{-- KOSONGKAN TBODY INI. DataTables akan mengisinya. --}}
                                <tbody>
                                </tbody>
                                <tr>
                                    <th colspan="9" class="text-center">
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-themed"
                                            id="btn-tambah-tindakan" data-toggle="modal" data-id="{{ $registration->id }}"
                                            data-target="#modal-tambah-alat">
                                            <span class="fal fa-plus-circle"></span>
                                            Tambah Alat
                                        </button>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-tambah-alat" tabindex="-1" aria-hidden="true" data-id="{{ $registration->id }}">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Tambah Pemakaian Alat Medis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                        @csrf
                        @method('post')
                        <input type="hidden" id="registration" value="{{ $registration->id }}">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="tglOrder" class="col-sm-3 col-form-label">Tgl Order</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tglOrder" placeholder="Pilih tanggal"
                                        value={{ now()->format('Y-m-d') }}>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="doctor" class="col-sm-3 col-form-label">Dokter</label>
                                <div class="col-sm-9">
                                    <select class="form-select select2-dropdown" id="doctor-pemakaian-alat"
                                        style="width: 100%;">
                                        @foreach ($doctors as $doctor)
                                            @if ($doctor->id == $registration->doctor_id)
                                                <option value="{{ $doctor->id }}" selected>
                                                    {{ $doctor?->employee?->fullname }}
                                                </option>
                                            @else
                                                <option value="{{ $doctor->id }}">
                                                    {{ $doctor?->employee?->fullname }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="departement" class="col-sm-3 col-form-label">Poliklinik</label>
                                <div class="col-sm-9">
                                    <select class="form-select select2-dropdown" id="departement" style="width: 100%;">
                                        @foreach ($departements as $departement)
                                            <option value="{{ $departement->id }}"
                                                data-groups="{{ $departement->grup_tindakan_medis ? json_encode($departement->grup_tindakan_medis->toArray()) : '[]' }}">
                                                {{ $departement->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kelas" class="col-sm-3 col-form-label">Kelas</label>
                                <div class="col-sm-9">
                                    <select class="form-select select2-dropdown" id="kelas" style="width: 100%;">
                                        <option value="{{ $registration->kelas_rawat_id }}">
                                            {{ $registration->kelas_rawat?->kelas }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="alat_medis" class="col-sm-3 col-form-label">Alat Medis</label>
                                <div class="col-sm-9">
                                    <select class="form-select select2-dropdown" id="alat_medis" style="width: 100%;">
                                        <option value="" selected>Pilih Alat Medis</option>
                                        {{-- @dd($tindakan_medis) --}}
                                        @foreach ($list_peralatan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="lokasi" class="col-sm-3 col-form-label">Lokasi</label>
                                <div class="col-sm-9">
                                    <select class="form-select select2-dropdown" id="lokasi" style="width: 100%;">
                                        <option value="">Pilih Alat Medis</option>
                                        <option value="OK">OK</option>
                                        <option value="KTD">KTD</option>
                                        <option value="VK">VK</option>
                                        <option value="LAINNYA">LAINNYA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="qty" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-save-alat">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    {{-- HANYA PANGGIL PLUGIN DAN INCLUDE FILE JS. TIDAK ADA LOGIKA JS DI SINI. --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');

            $('.select2-dropdown').select2({
                placeholder: 'Pilih item berikut',
                dropdownParent: $('#modal-tambah-alat')
            });

            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            $('#dt-basic-example').dataTable({
                responsive: false,
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });
        });
    </script>
    {{-- Cukup include satu file ini yang sudah berisi semua logika --}}
    @include('pages.simrs.poliklinik.partials.action-js.pemakaian_alat')
@endsection
