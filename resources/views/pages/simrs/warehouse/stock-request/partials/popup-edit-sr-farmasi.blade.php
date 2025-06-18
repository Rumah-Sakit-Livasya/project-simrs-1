@extends('inc.layout-no-side')
@section('title', 'Stock Request Form')
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
            width: 60px;
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
                            Stock Request Form
                            &nbsp; <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form id="form-pr" name="form-pr"
                                action="{{ route('warehouse.stock-request.pharmacy.update', $sr->id) }}" method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="id" value="{{ $sr->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_sr">
                                                        Tanggal*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_sr"
                                                        value="{{ $sr->tanggal_sr }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe">
                                                        Status*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="tipe" class="form-control" required>
                                                        {{-- normal, urgent --}}
                                                        <option value="normal"
                                                            {{ $sr->tipe == 'normal' ? 'selected' : '' }}>
                                                            Normal
                                                        </option>
                                                        <option value="urgent"
                                                            {{ $sr->tipe == 'urgent' ? 'selected' : '' }}>Urgent
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="asal_gudang_id">
                                                        Gudang Asal*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="asal_gudang_id" class="form-control select2"
                                                        id="asal-gudang" required>
                                                        <option value="" disabled selected hidden>Pilih Gudang
                                                        </option>
                                                        @foreach ($gudang_asals as $gudang)
                                                            <option value="{{ $gudang->id }}"
                                                                {{ $sr->asal_gudang_id == $gudang->id ? 'selected' : '' }}>
                                                                {{ $gudang->nama }}
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tujuan_gudang_id">
                                                        Gudang Tujuan*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="tujuan_gudang_id" id="tujuan-gudang"
                                                        class="form-control select2" required>
                                                        <option value="" disabled selected hidden>Pilih Gudang
                                                        </option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}"
                                                                {{ $sr->tujuan_gudang_id == $gudang->id ? 'selected' : '' }}>
                                                                {{ $gudang->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="row justify-content-center">
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-1" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan">
                                                        Keterangan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- input text with name "keterangan" --}}
                                                    <input type="text" class="form-control" name="keterangan"
                                                        id="keterangan" value="{{ $sr->keterangan }}">
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
                                                <th>Sisa Stok</th>
                                                <th>Qty</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems">
                                            @php
                                                $key_caches = [];
                                            @endphp
                                            @foreach ($sr->items as $item)
                                                @php
                                                    $key_cache = $item->barang->id . '/' . $item->satuan->id;
                                                    $key_caches[] = $key_cache;
                                                @endphp

                                                <tr id="item{{ $loop->iteration }}">
                                                    <input type="hidden" name="barang_id[{{ $loop->iteration }}]"
                                                        value="{{ $item->barang->id }}">
                                                    <input type="hidden" name="satuan_id[{{ $loop->iteration }}]"
                                                        value="{{ $item->satuan->id }}">
                                                    <input type="hidden" name="item_id[{{ $loop->iteration }}]"
                                                        value="{{ $item->id }}">

                                                    <td>{{ $item->barang->kode }}</td>
                                                    <td>{{ $item->barang->nama }}</td>
                                                    <td>{{ $item->satuan->nama }}</td>
                                                    <td>-</td>
                                                    <td><input type="number" name="qty[{{ $loop->iteration }}]"
                                                            min="1" step="1" class="form-control"
                                                            value="{{ $item->qty }}"></td>
                                                    <td><input type="text" value="{{ $item->keterangan }}"
                                                            name="keterangan_item[{{ $loop->iteration }}]"
                                                            class="form-control"></td>
                                                    <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                            title="Hapus"
                                                            onclick="PopupSRPharmacyClass.deleteItem({{ $loop->iteration }}, '{{ $key_cache }}')"></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <script>
                                                window._key_caches = @json($key_caches);
                                            </script>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="7">
                                                    <button type="button" id="add-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-plus mr-1"></span>
                                                        Tambah Item
                                                    </button>
                                                    @include('pages.simrs.warehouse.stock-request.partials.modal-add-item')
                                                </td>
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
                                            @if ($sr->status == 'draft')
                                                <button type="submit" id="order-submit-draft"
                                                    class="btn btn-lg btn-primary waves-effect waves-themed">
                                                    <span class="fal fa-save mr-1"></span>
                                                    Simpan Draft
                                                </button>
                                                <button type="submit" id="order-submit-final"
                                                    class="btn btn-lg btn-success waves-effect waves-themed">
                                                    <span class="fal fa-save mr-1"></span>
                                                    Simpan Final
                                                </button>
                                            @else
                                                <h1 style="color: red">Data sudah final</h1>
                                                <p>Tidak dapat diubah lagi</p>
                                            @endif
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
    <script src="{{ asset('js/simrs/warehouse/stock-request/popup-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
