@extends('inc.layout')
@section('title', 'Form Tambah Resep')
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

        input[type='checkbox'] {
            width: 1.5rem;
            height: 1.5rem;
            margin: 0.5rem;
        }

        #loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }

        .embalase-label {
            display: inline-block;
            width: 80px;
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
                            Form Tambah Resep
                            &nbsp;
                            <i id="loading-spinner-head" class="loading fas fa-spinner fa-spin"></i>
                            <span class="loading-message loading text-info">Loading...</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page" class="loading"></div>
                        <div class="panel-content">
                            <form id="form-pr" name="form-pr" action="{{ route('farmasi.transaksi-resep.store') }}"
                                method="post">
                                @csrf
                                @method('post')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="order_date">
                                                        Tanggal Resep
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="date" class="form-control unclearable"
                                                        id="datepicker-1" placeholder="Select date" name="order_date"
                                                        value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="nama_pasien">
                                                        Nama Pasien
                                                    </label>
                                                    <button onclick="event.preventDefault()" class="btn btn-primary"
                                                        id="pilih-pasien-btn"><span
                                                            class="fal fa-search mr-1"></span></button>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="nama_pasien" name="nama_pasien">
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
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="nama_dokter">
                                                        Nama Dokter
                                                    </label>
                                                    <button onclick="event.preventDefault()" class="btn btn-primary"
                                                        id="pilih-dokter-btn"><span
                                                            class="fal fa-search mr-1"></span></button>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="nama_dokter" name="nama_dokter">
                                                    @error('nama_dokter')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="gudang_id">
                                                        Gudang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="gudang_id" id="gudang_id"
                                                        class="form-control unclearable select2" required>
                                                        <option value=""
                                                            {{ !isset($default_apotek) ? 'selected' : '' }} disabled hidden>
                                                            Pilih Gudang</option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}"
                                                                {{ isset($default_apotek) && $default_apotek->id == $gudang->id ? 'selected' : '' }}>
                                                                {{ $gudang->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="mrn_registration_number">
                                                        No RM / No Reg
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="mrn_registration_number"
                                                        name="mrn_registration_number">
                                                    @error('mrn_registration_number')
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
                                                    <label class="form-label text-end" for="penjamin">
                                                        Penjamin
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="penjamin" name="penjamin">
                                                    @error('penjamin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe_pasien">
                                                        Tipe Pasien
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control unclearable" name="tipe_pasien"
                                                        id="tipe_pasien">
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
                                                    <label class="form-label text-end" for="umur_jk">
                                                        Umur / Jenis Kelamin
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="umur_jk" name="umur_jk">
                                                    @error('umur_jk')
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
                                                    <label class="form-label text-end" for="alamat">
                                                        Alamat
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="alamat" name="alamat">
                                                    @error('alamat')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="poly_ruang">
                                                        Poly / Ruang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value=""
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="poly_ruang" name="poly_ruang">
                                                    @error('poly_ruang')
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
                                                    <label class="form-label text-end" for="no_telp">
                                                        No Telp
                                                    </label>
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

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="kronis">
                                                        Kronis
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="checkbox" name="kronis" id="kronis"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="embalase">
                                                        Embalase*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- create radio with name="embalase" with values "tidak", "item", "racikan" --}}
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="embalase"
                                                            id="embalase_racikan" value="racikan">
                                                        <label class="embalase-label">Racikan</label>
                                                        <input class="form-check-input" type="radio" name="embalase"
                                                            id="embalase_item" value="item">
                                                        <label class="embalase-label">Item</label>
                                                        <input class="form-check-input" type="radio" name="embalase"
                                                            checked id="embalase_tidak" value="tidak">
                                                        <label class="embalase-label">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="bmhp">
                                                        B.M.H.P.
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- create checkbox with name "bmhp" and label "Tidak ditagihkan ke pasien" --}}
                                                    <input type="checkbox" name="bmhp" id="bmhp">
                                                    <label for="bmhp"> Tidak ditagihkan ke pasien </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="dispensing">
                                                        Dispensing
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="checkbox" name="dispensing" id="dispensing"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="cari_obat">
                                                        Cari Obat
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select id="obat-select" class="form-control unclearable select2">
                                                        <option value="" selected disabled hidden> Pilih Obat
                                                        </option>
                                                        @if (isset($obats))
                                                            @foreach ($obats as $obat)
                                                                @php
                                                                    $items = $obat->stored_items->where(
                                                                        'gudang_id',
                                                                        $default_apotek->id,
                                                                    );
                                                                    $qty = $items->sum('qty');
                                                                    $obat->qty = $qty;
                                                                @endphp
                                                                @if ($qty > 0)
                                                                    <option value="{{ $obat->id }}" class="obat"
                                                                        data-qty="{{ $qty }}"
                                                                        data-item="{{ json_encode($obat) }}">
                                                                        {{ $obat->nama }} (Stock:
                                                                        {{ $qty }})</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <br>
                                                    {{-- create a checkbox with label "Zat Aktif" --}}
                                                    <div class="row">
                                                        <div class="col-xl">
                                                            <input type="checkbox" name="zat_aktif" id="zat_aktif">
                                                            <label for="zat_aktif"> Zat Aktif </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="bmhp">
                                                        Resep Manual
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- create checkbox with name "bmhp" and label "Tidak ditagihkan ke pasien" --}}
                                                    <textarea name="resep_manual" readonly id="resep-manual" cols="10" rows="3" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        {{--  --}}
                                    </div>
                                </div>

                                <hr>

                                <div class="row justify-content-center">
                                    <table class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Dijamin</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>UOM</th>
                                                <th>Qty</th>
                                                <th>Signa</th>
                                                <th>Instruksi</th>
                                                <th>Jam Pemberian</th>
                                                <th>Harga</th>
                                                <th>Embalase</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="9">
                                                    <button type="button" id="update-task-id-6-btn" disabled
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-check mr-1"></span>
                                                        Update Task Id 6
                                                    </button>

                                                    <button type="button" id="resep-elektronik-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-clipboard-list mr-1"></span>
                                                        Resep Elektronik
                                                    </button>

                                                    <button type="button" id="resep-harian-btn" disabled
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-notes-medical mr-1"></span>
                                                        Resep Harian
                                                    </button>
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
                                            <a onclick="window.history.back()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-xl text-right">
                                            <button type="submit" id="order-submit-final"
                                                class="btn btn-lg btn-primary waves-effect waves-themed">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(".select2").select2();
    </script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/api.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/utils.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/ui.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/handler.js') }}?v={{ time() }}"></script>

@endsection
