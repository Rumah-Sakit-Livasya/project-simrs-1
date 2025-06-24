@extends('inc.layout-no-side')
@section('title', 'Stock Adjustment')
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
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Stock Adjustment <span class="fw-300"><i>{{ $gudang->nama }}
                                    ([{{ $satuan->nama }}] {{ $barang->kode }} / {{ $barang->nama }})</i></span>
                            &nbsp;
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <form id="form-sa" name="form-sa"
                            action="{{ route('warehouse.revaluasi-stock.stock-adjustment.update') }}" method="post">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                            <input type="hidden" name="barang_{{ $type }}_id" value="{{ $barang->id }}">
                            <input type="hidden" name="satuan_id" value="{{ $satuan->id }}">
                            <input type="hidden" name="gudang_id" value="{{ $gudang->id }}">
                            <input type="hidden" name="tanggal_sa" value="{{ now() }}">

                            <div class="panel-content">
                                <!-- datatable start -->
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>#</th>
                                            <th>Kode PB</th>
                                            <th>Supplier</th>
                                            <th>Tanggal Terima</th>
                                            <th>Tanggal Exp</th>
                                            <th>Batch No</th>
                                            <th>Stok</th>
                                            <th>Adjustment (Delta)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        @foreach ($sis as $si)
                                            <tr id="si{{ $si->id }}">
                                                <input type="hidden" name="si_id[{{ $si->id }}]"
                                                    value="{{ $si->id }}">

                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $si->pbi->pb->kode_penerimaan }}</td>
                                                <td>{{ $si->pbi->pb->supplier->nama }}</td>
                                                <td>{{ tgl($si->pbi->pb->tanggal_terima) }}</td>
                                                <td>{{ tgl($si->pbi->tanggal_exp) }}</td>
                                                <td>{{ $si->pbi->batch_no }}</td>
                                                <td><input type="number" min="0" data-initial="{{ $si->qty }}"
                                                        name="qty[{{ $si->id }}]" class="qty"
                                                        value="{{ $si->qty }}"></td>
                                                <td class="delta">0</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <label for="keterangan">Keterangan</label>
                                                <input type="text" name="keterangan" class="form-control">
                                            </td>
                                            <td class="text-right">Total</td>
                                            <td id="qty-total">{{ $sis->sum('qty') }}</td>
                                            <td id="delta-total">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <!-- datatable end -->

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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('plugin')
    <script src="{{ asset('js/simrs/warehouse/revaluasi-stock/popup-edit-revaluasi-stock.js') }}?v={{ time() }}">
    </script>
@endsection
