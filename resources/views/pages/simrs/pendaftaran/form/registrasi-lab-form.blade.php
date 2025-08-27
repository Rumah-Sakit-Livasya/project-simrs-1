@section('extended-css')
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .card h3 {
            background-color: #cc33cc;
            color: white;
            padding: 10px;
            margin: -15px -15px 10px -15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .item:last-child {
            border-bottom: none;
        }

        .parameter_laboratorium_number {
            width: 60px;
            margin-left: 10px;
        }
    </style>
@endsection
@php
    // Definisikan semua data statis dan dinamis di satu tempat.
    $doctorOptions = $doctors->pluck('employee.fullname', 'id')->all();
    $orderTypeOptions = [
        'normal' => 'Normal',
        'cito' => 'CITO (naik 30%)',
    ];
@endphp

{{-- ID 'form-registrasi' digunakan agar konsisten dengan skrip AJAX standar. --}}
<form id="form-registrasi" data-action-url="{{ route('simpan.registrasi') }}">
    @csrf
    {{-- Hidden Inputs --}}
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="laboratorium"> {{-- Disesuaikan --}}
    <input type="hidden" name="poliklinik" value="LABORATORIUM">
    <input type="hidden" name="rujukan" value="inisiatif pribadi">
    <input type="hidden" name="penjamin_id" value="{{ $penjamin_standar_id }}">

    {{-- Notifikasi untuk pesan sukses/gagal umum dari AJAX --}}
    <div id="form-notification" class="alert d-none" role="alert"></div>

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-xl-6">
            <x-form-row label="Tanggal Registrasi" for="registration_date">
                <input type="text" class="form-control form-control-dashed" id="registration_date"
                    name="registration_date" readonly value="{{ old('registration_date', $today) }}">
            </x-form-row>

            <x-form-row label="Dokter Penanggung Jawab" for="doctor_id">
                <x-form-select id="doctor_id" name="doctor_id" :options="$doctorOptions" />
            </x-form-row>

            <x-form-row label="Tipe Order" for="order_type">
                <div class="frame-wrap pt-2">
                    @foreach ($orderTypeOptions as $value => $label)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="order_type_{{ $value }}"
                                name="order_type" value="{{ $value }}"
                                {{ old('order_type', 'normal') == $value ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="order_type_{{ $value }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </x-form-row>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-xl-6">
            <x-form-row label="Diagnosa*" for="diagnosa_awal">
                <textarea class="form-control" id="diagnosa_awal" name="diagnosa_awal" rows="5">{{ old('diagnosa_awal') }}</textarea>
            </x-form-row>

            <x-form-row label="Catatan" for="catatan">
                <textarea class="form-control" id="catatan" name="catatan" rows="5">{{ old('catatan') }}</textarea>
            </x-form-row>
        </div>

        {{-- Bagian Custom untuk Pemilihan Tindakan Laboratorium --}}
        <div class="col-xl-12 mt-4">
            <div class="panel">
                <div class="panel-hdr">
                    <h2>Pilih Tindakan Laboratorium</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <input type="text" class="form-control mb-3" id="searchLaboratorium"
                            placeholder="Cari tindakan atau kategori...">
                        <div class="grid">
                            {{-- Strukturnya tetap sama karena sangat spesifik dan dikontrol oleh JS --}}
                            @foreach ($laboratorium_categories as $category)
                                <div class="card">
                                    <h3>{{ $category->nama_kategori }}</h3>
                                    @foreach ($category->parameter_laboratorium as $parameter)
                                        @if ($parameter->is_order)
                                            <div class="item parameter_laboratorium">
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox"
                                                        name="lab_parameters[{{ $parameter->id }}][id]"
                                                        value="{{ $parameter->id }}"
                                                        class="parameter_laboratorium_checkbox"
                                                        id="parameter_laboratorium_{{ $parameter->id }}">
                                                    <label class="form-check-label ml-2"
                                                        for="parameter_laboratorium_{{ $parameter->id }}">
                                                        {{ $parameter->parameter }} (<span
                                                            id="harga_parameter_laboratorium_{{ $parameter->id }}">{{ rp(0) }}</span>)
                                                    </label>
                                                </div>
                                                <input type="number" name="lab_parameters[{{ $parameter->id }}][qty]"
                                                    value="1" class="form-control parameter_laboratorium_number"
                                                    id="jumlah_{{ $parameter->id }}" style="display: none;" disabled>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="col-xl-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}"
                        class="btn btn-lg btn-default waves-effect waves-themed">
                        <span class="fal fa-arrow-left mr-1 text-primary"></span>
                        <span class="text-primary">Kembali</span>
                    </a>
                </div>
                <div class="text-right">
                    <h4 class="text-success font-weight-bold mb-0">Total Biaya: <span id="laboratorium-total">Rp
                            0</span></h4>
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed mt-1"
                        id="simpan-btn">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Bagian Plugin JavaScript tetap tidak berubah, karena sudah merupakan praktik yang baik. --}}
@section('plugin')
    <script>
        // Data ini penting untuk logika kalkulasi di file JS eksternal
        window._kategoriLaboratorium = @json($laboratorium_categories);
        window._tarifLaboratorium = @json($tarifs);
    </script>
    <script src="{{ asset('js/simrs/form-laboratorium-registrasi.js') }}?v={{ time() }}"></script>
@endsection
