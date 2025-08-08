@extends('inc.layout')
@section('title', 'Setup Min Max Stock per Gudang')
@section('content')
    <style>
        #loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <a title="Kembali" href="{{ route('warehouse.master-data.setup-min-max-stock') }}"
                                class="btn btn-outline-primary waves-effect waves-themed">
                                <span class="fal fa-chevron-left"></span>
                            </a>&nbsp; Setup <span class="fw-300"><i>Min Max Stock per Gudang</i> <i id="loading-spinner"
                                    class="fas fa-spinner fa-spin"></i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form action="{{ route('warehouse.master-data.setup-min-max-stock.store') }}" method="post">
                                @csrf
                                @method('post')
                                <div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2" style="text-align: right">
                                                <label class="form-label text-end" for="gudang_id">
                                                    Gudang <i title="Refresh" onclick="SMMSClass.handleGudangChange()"
                                                        class="btn btn-outline-primary waves-effect waves-themed">
                                                        <span class="fal fa-redo-alt"></span>
                                                    </i>
                                                </label>
                                            </div>
                                            <div class="col-xl">
                                                <select name="gudang_id" id="gudang_id" class="form-control" required>
                                                    <option value="" selected disabled hidden>Pilih Gudang</option>
                                                    @foreach ($gudangs as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2" style="text-align: right">
                                                <label class="form-label text-end" for="select-barang">
                                                    Masukkan Barang
                                                </label>
                                            </div>
                                            <div class="col-xl">
                                                <select id="select-barang" class="form-control">
                                                    <option value="" selected disabled hidden>Pilih Barang</option>
                                                    @foreach ($barangs as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-type="{{ $item->tipe ? 'Farmasi' : 'NonFarmasi' }}">
                                                            {{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- datatable start -->
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kode Satuan</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kode Satuan</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <!-- datatable end -->

                                <div class="row">
                                    <div class="col-xl"></div>
                                    <div class="col-xl-2"> <a
                                            href="{{ route('warehouse.master-data.setup-min-max-stock') }}"
                                            class="btn btn-outline-primary waves-effect waves-themed">
                                            <span class="fal fa-chevron-left"></span>
                                            Kembali
                                        </a>
                                    </div>
                                    <div class="col-xl-7"></div>
                                    <div class="col-xl-2"> <button type="submit" class="btn btn-primary">
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        window._gudangs = @json($gudangs);
        window._satuans = @json($satuans);
        window._barang_farmasi = @json($barang_farmasi);
        window._barang_non_farmasi = @json($barang_non_farmasi);

        $(document).ready(function() {
            $("select").select2();
        });

        // format input to number only function
        // on keyup
        function formatInputToNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>

    <script src="{{ asset('js/simrs/warehouse/master-data/setup-min-max-stock.js') }}?v={{ time() }}"></script>

@endsection
