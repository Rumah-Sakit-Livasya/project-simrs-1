@extends('inc.layout')
@section('title', 'Form Retur Resep')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        .modal-dialog {
            max-width: 70%;
        }

        .borderless-input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0
        }

        .qty {
            min-width: 100px;
            margin-left: 10px;
        }

        input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0;
        }

        #loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Retur Resep
                            &nbsp; <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form id="form-pr" name="form-pr" action="{{ route('farmasi.retur-barang.store') }}"
                                method="post">
                                @csrf
                                @method('post')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_retur">
                                                        Tanggal Retur
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_retur"
                                                        value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan">
                                                        Keterangan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ old('keterangan') }}"
                                                        class="form-control otc-change" id="keterangan" name="keterangan">
                                                    @error('keterangan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="patient_id">
                                                        Pasien*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="patient_id" required id="patient_id"
                                                        class="form-control select2">
                                                        <option value="" selected disabled hidden>Pilih Pasien
                                                        </option>
                                                        @foreach ($patients as $patient)
                                                            <option value="{{ $patient->id }}">
                                                                [{{ $patient->medical_record_number }}] {{ $patient->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="gudang_id">
                                                        Gudang Penerima*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="gudang_id" required id="gudang_id"
                                                        class="form-control select2">
                                                        <option value="" selected disabled hidden>Pilih Gudang
                                                        </option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row justify-content-center">
                                    <table class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Satuan</th>
                                                <th>No Batch</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="5">
                                                    <button type="button" id="add-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-plus mr-1"></span>
                                                        Tambah Item
                                                    </button>
                                                    @include('pages.simrs.farmasi.retur-resep.partials.modal-add-item-retur')
                                                </td>
                                                <td class="text-right">Total
                                                    <input type="hidden" value="0" name="nominal">
                                                </td>
                                                <td>
                                                    <span id="total-display">Rp 0</span>
                                                </td>
                                                <td>{{--  --}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="col-xl-12 mt-5">
                                    <div class="row">
                                        <div class="col-xl">
                                            <a onclick="window.close()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-xl text-right">
                                            <button type="submit" id="order-submit-final"
                                                class="btn btn-lg btn-danger waves-effect waves-themed">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan Final
                                            </button>
                                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(".select2").select2();
    </script>
    <script src="{{ asset('js/simrs/farmasi/retur-resep/retur.js') }}?v={{ time() }}"></script>
@endsection
