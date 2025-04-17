@extends('inc.layout')
@section('title', 'Order Baru Laboratorium')
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
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Order Baru</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">

                            <form method="post" name="form-laboratorium" id="form-laboratorium">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="order_date">Tanggal</label>
                                                </div>
                                                <div class="col-xl">
                                                    <div class="form-group row">
                                                        <div class="col-xl ">
                                                            <input disabled type="date" class="form-control"
                                                                id="datepicker-1"
                                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                                placeholder="Select date" name="order_date"
                                                                value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                        </div>
                                                    </div>
                                                    @error('order_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="nama_pasien">
                                                        Nama Pasien
                                                    </label>
                                                    <button onclick="event.preventDefault()" class="btn btn-primary"
                                                        id="pilih-pasien-btn"><span
                                                            class="fal fa-search mr-1"></span></button>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" disabled value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="nama_pasien" name="nama_pasien">
                                                    @error('nama_pasien')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="mrn_registration_number">
                                                        No RM / Registrasi
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" disabled value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="mrn_registration_number"
                                                        name="mrn_registration_number">

                                                    <input type="hidden" name="medical_record_number" value=""
                                                        disabled>
                                                    <input type="hidden" name="registration_number" value=""
                                                        disabled>
                                                    @error('mrn_registration_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-4">
                                    <div class="col-xl-4">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe_pasien">
                                                        Tipe Pasien
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control" name="tipe_pasien" id="tipe_pasien">
                                                        <option value="rajal">Rawat Jalan</option>
                                                        <option value="ranap">Rawat Inap</option>
                                                        <option value="otc">OTC</option>
                                                    </select>
                                                    @error('tipe_pasien')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="date_of_birth">Tanggal Lahir</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="date" disabled value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="date_of_birth" name="date_of_birth">
                                                    @error('date_of_birth')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="poly_ruang">Poly/Ruang</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" disabled value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="poly_ruang" name="poly_ruang">
                                                    @error('poly_ruang')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-4">
                                    <div class="col-xl-4">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="order_type">
                                                        Tipe Order
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="order_type"
                                                            id="order_type_normal" value="normal" checked>
                                                        <label class="form-check-label" for="order_type_normal">
                                                            Normal
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="order_type"
                                                            id="order_type_cito" value="cito">
                                                        <label class="form-check-label" for="order_type_cito">
                                                            CITO (naik 30%)
                                                        </label>
                                                    </div>
                                                    @error('order_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                                </div>
                                                <div class="col-xl">
                                                    <div class="form-check">
                                                        <input disabled class="form-check-input" type="radio"
                                                            name="jenis_kelamin" id="gender_male" value="Laki-laki">
                                                        <label class="form-check-label" for="gender_male">
                                                            Laki-laki
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input disabled class="form-check-input" type="radio"
                                                            name="jenis_kelamin" id="gender_female" value="Perempuan">
                                                        <label class="form-check-label" for="gender_female">
                                                            Perempuan
                                                        </label>
                                                    </div>
                                                    @error('jenis_kelamin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="alamat">Alamat</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="alamat" name="alamat">
                                                    @error('alamat')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-4">
                                    <div class="col-xl-4">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-5" style="text-align: right">
                                                    <label class="form-label text-end" for="doctor_id">
                                                        Dokter Laboratorium
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="select2 form-control w-100" id="doctor_id"
                                                        name="doctor_id">
                                                        <option value=""></option>
                                                        @foreach ($laboratoriumDoctors as $doctor)
                                                            <option value="{{ $doctor->id }}">
                                                                {{ $doctor->employee->fullname }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('doctor_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="diagnosa_awal">Diagnosa Klinis</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="diagnosa_awal" name="diagnosa_awal">
                                                    @error('diagnosa_awal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label for="no_telp">No. Telp / HP</label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="no_telp" name="no_telp">
                                                    @error('no_telp')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-4">
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control mb-3" id="searchLaboratorium"
                                                placeholder="Cari tindakan...">
                                            <div class="grid">
                                                @foreach ($laboratorium_categories as $category)
                                                    <div class="card">
                                                        <h3>{{ $category->nama_kategori }}</h3>
                                                        @foreach ($category->parameter_laboratorium as $parameter)
                                                            @if ($parameter->is_order)
                                                                <div class="item parameter_laboratorium">
                                                                    <input type="checkbox" value="{{ $parameter->id }}"
                                                                        class="parameter_laboratorium_checkbox"
                                                                        id="parameter_laboratorium_{{ $parameter->id }}">
                                                                    <label>
                                                                        <span
                                                                            class="form-check-label">{{ $parameter->parameter }}</span>(<span
                                                                            id="harga_parameter_laboratorium_{{ $parameter->id }}">{{ rp(0) }}</span>)
                                                                    </label>

                                                                    <input type="number" value="1"
                                                                        class="form-control parameter_laboratorium_number"
                                                                        id="jumlah_{{ $parameter->id }}">
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-xl-8">
                                        <a href="{{ route('laboratorium.list-order') }}"
                                            class="btn btn-outline-primary waves-effect waves-themed">
                                            <span class="fal fa-arrow-left mr-1"></span>
                                            Kembali
                                        </a>
                                    </div>

                                    <div class="col-xl-3">
                                        <h3 class="text-success"> <i class="fa fa-calculator"></i> <span
                                                id="laboratorium-total">Rp
                                                0</span>
                                        </h3>
                                        <button onclick="event.preventDefault()"
                                            class="btn btn-primary waves-effect waves-themed submit-btn">
                                            <span class="fal fa-plus mr-1"></span>
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        window._kategoriLaboratorium = @json($laboratorium_categories);
        window._tarifLaboratorium = @json($tarifs);
        window._penjamins = @json($penjamins);
        window._kelasRawats = @json($kelas_rawats);

        // Select 2
        $(function() {
            $('.select2').select2({
                dropdownCssClass: "move-up"
            });
            $(".select2").on("select2:open", function() {
                // Mengambil elemen kotak pencarian
                var searchField = $(".select2-search__field");

                // Mengubah urutan elemen untuk memindahkannya ke atas
                searchField.insertBefore(searchField.prev());
            });
        });
    </script>
    <script src="{{ asset('js/simrs/order-laboratorium.js') }}?v={{ time() }}"></script>
@endsection
