@extends('inc.layout-no-side')
@section('title', 'Order Gizi untuk Pasien')

@section('extended-css')
    {{-- Tidak diperlukan CSS tambahan --}}
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Panel Utama --}}
        <div class="panel" id="panel-order-gizi">
            <div class="panel-hdr">
                <h2>
                    Pesan Makanan untuk: <span class="fw-300"><i>{{ $registration->patient->name }}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10"
                        data-original-title="Fullscreen" aria-label="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <form id="form-order-gizi" action="{{ route('gizi.order.store') }}" method="POST" novalidate>
                    @csrf
                    {{-- Input hidden --}}
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                    <input type="hidden" name="untuk" value="pasien">
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="digabung" value="1">
                    <input type="hidden" value="0" name="total_harga" id="total_harga_input">

                    {{-- Panel Body --}}
                    <div class="panel-content">
                        <div class="row">
                            {{-- KOLOM KIRI: INFORMASI ORDER --}}
                            <div class="col-lg-5">
                                <div class="card border mb-lg-0 mb-3">
                                    <div class="card-header bg-primary-50">
                                        <h5 class="mb-0 fw-500">Informasi Order</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Pasien</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-plaintext fw-500">{{ $registration->patient->name }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">RM / No. Reg</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-plaintext">
                                                    {{ $registration->patient->medical_record_number }} /
                                                    {{ $registration->registration_number }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="nama_pemesan">Pemesan</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="nama_pemesan" name="nama_pemesan"
                                                    value="{{ $registration->patient->name }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="tanggal_order">Tgl. Pesan</label>
                                            <div class="col-sm-8">
                                                <input type="datetime-local" id="tanggal_order" name="tanggal_order"
                                                    class="form-control" required
                                                    value="{{ now()->addHours(7)->format('Y-m-d\TH:i') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="kategori_id">Kategori</label>
                                            <div class="col-sm-8">
                                                <select name="kategori_id" id="kategori_id" class="select2 form-control"
                                                    required data-placeholder="Pilih Kategori...">
                                                    <option></option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="waktu_makan">Waktu Makan</label>
                                            <div class="col-sm-8">
                                                <select class="select2 form-control" id="waktu_makan" name="waktu_makan"
                                                    required data-placeholder="Pilih Waktu...">
                                                    <option></option>
                                                    @foreach ($jam_makans as $jam_makan)
                                                        <option value="{{ $jam_makan->nama }}">{{ $jam_makan->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Ditagihkan</label>
                                            <div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="ditagihkan_yes"
                                                        name="ditagihkan" value="1" checked>
                                                    <label class="custom-control-label" for="ditagihkan_yes">Ya</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="ditagihkan_no"
                                                        name="ditagihkan" value="0">
                                                    <label class="custom-control-label" for="ditagihkan_no">Tidak <small
                                                            class="text-danger">(khusus Super VIP)</small></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DETAIL PESANAN --}}
                            <div class="col-lg-7">
                                <div class="card border">
                                    <div class="card-header bg-primary-50">
                                        <h5 class="mb-0 fw-500">Detail Pesanan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label" for="search-food">Cari & Tambah Makanan</label>
                                            <div class="input-group">
                                                <select class="select2-food-search form-control" id="search-food"
                                                    aria-label="Cari Makanan">
                                                    <option></option>
                                                    @foreach ($foods as $food)
                                                        <option value="{{ $food->id }}">{{ $food->nama }}
                                                            [{{ rp($food->harga) }}]</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                                        data-target="#pilihMenuModal" title="Pilih dari Menu"
                                                        aria-label="Pilih dari Menu">
                                                        <i class="fal fa-list-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped w-100">
                                                <thead class="bg-primary-600">
                                                    <tr>
                                                        <th>Nama Makanan</th>
                                                        <th style="width: 100px;">Qty</th>
                                                        <th style="width: 120px;" class="text-right">Subtotal</th>
                                                        <th style="width: 50px;" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-food"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" class="text-right font-weight-bold">Total</td>
                                                        <td id="harga-display" class="text-right font-weight-bold">Rp 0
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel Footer (untuk tombol aksi) --}}
                    <div class="panel-footer d-flex flex-row align-items-center">
                        <button type="button" class="btn btn-secondary" onclick="window.close()">Tutup</button>
                        <button type="submit" id="order-submit" class="btn btn-primary ml-auto">
                            <i class="fal fa-save mr-1"></i>
                            Simpan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Modal diletakkan di akhir @section('content') --}}
        @include('pages.simrs.gizi.partials.pilih-menu-modal', ['menus' => $menus])
    </main>


@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/simrs/order-gizi.js"></script>
    <script>
        // Namespace data untuk menghindari polusi global scope
        window.giziData = {
            foods: @json($foods->keyBy('id')),
            menus: @json($menus->keyBy('id')->load('makanan_menu.makanan'))
        };

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "Pilih...",
                allowClear: true,
                dropdownParent: $(this).parent() // Penting untuk popup agar dropdown tidak terpotong
            });
            $('.select2-food-search').select2({
                placeholder: "Ketik nama makanan...",
                allowClear: true,
                dropdownParent: $(this).parent()
            });
        });
    </script>
    <script src="{{ asset('js/simrs/popup-order-gizi.js') }}"></script>
@endsection
