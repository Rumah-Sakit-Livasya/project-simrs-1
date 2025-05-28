@extends('inc.layout-no-side')
@section('title', 'Purchase Request Form')
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
                            Purchase Request Form
                            &nbsp; <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form id="form-pr" name="form-pr"
                                action="{{ route('procurement.approval-pr.pharmacy.update', ['id' => $pr->id]) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="id" value="{{ $pr->id }}">
                                <input type="hidden" name="kode_pr" value="{{ $pr->kode_pr }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_app">
                                                        Tanggal APP
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_app"
                                                        value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="kode_pr">
                                                        Kode PR
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $pr->kode_pr }}"
                                                        class="form-control" id="kode_pr" name="kode_pr">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="status">
                                                        Gudang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $pr->gudang->nama }}"
                                                        class="form-control" id="gudang_id" name="gudang_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_pr">
                                                        Tanggal PR
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_pr"
                                                        value="{{ $pr->tanggal_pr }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe">
                                                        Tipe
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ ucfirst($pr->tipe) }}"
                                                        class="form-control" id="tipe" name="tipe">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan">
                                                        Keterangan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" readonly name="keterangan"
                                                        value="{{ $pr->keterangan }}">
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
                                                <th>Stok</th>
                                                <th>Keterangan</th>
                                                <th>Harga Master</th>
                                                <th>Qty PR</th>
                                                <th>Subtotal</th>
                                                <th>Qty APP</th>
                                                <th>Subtotal APP</th>
                                                <th>Keterangan APP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems">
                                            @foreach ($pr->items as $item)
                                                <tr id="item{{ $loop->iteration }}">
                                                    <input type="hidden" name="item_id[{{ $loop->iteration }}]"
                                                        value="{{ $item->id }}">
                                                    <td>{{ $item->barang->kode }}
                                                        <input type="hidden" name="kode_barang[{{ $loop->iteration }}]"
                                                            value="{{ $item->barang->kode }}">
                                                    </td>
                                                    <td>{{ $item->barang->nama }}
                                                        <input type="hidden" name="nama_barang[{{ $loop->iteration }}]"
                                                            value="{{ $item->barang->nama }}">
                                                        <input type="hidden" name="barang_id[{{ $loop->iteration }}]"
                                                            value="{{ $item->barang->id }}">
                                                    </td>
                                                    <td>{{ $item->satuan->nama }}
                                                        <input type="hidden" name="unit_barang[{{ $loop->iteration }}]"
                                                            value="{{ $item->satuan->nama }}">
                                                        <input type="hidden" name="satuan_id[{{ $loop->iteration }}]"
                                                            value="{{ $item->satuan->id }}">
                                                    </td>
                                                    <td>Coming Soon!</td>
                                                    <td><input type="text" readonly
                                                            name="keterangan_item[{{ $loop->iteration }}]"
                                                            class="form-control" value="{{ $item->keterangan }}"></td>
                                                    <td>{{ rp($item->barang->hna) }}
                                                        <input type="hidden" name="hna[{{ $loop->iteration }}]" readonly
                                                            value="{{ $item->barang->hna }}">
                                                    </td>
                                                    <td><input type="number" name="qty[{{ $loop->iteration }}]" readonly
                                                            min="0" step="1" class="form-control"
                                                            value="{{ $item->qty }}"></td>
                                                    <td class="subtotal_pr">{{ rp($item->barang->hna * $item->qty) }}</td>
                                                    <td><input type="number" name="approved_qty[{{ $loop->iteration }}]"
                                                            min="0" max="{{ $item->qty }}" step="1" class="form-control"
                                                            value="{{ isset($item->approved_qty) ? $item->approved_qty : $item->qty }}"
                                                            onkeyup="PopupAPRPharmacyClass.refreshTotal()"
                                                            onchange="PopupAPRPharmacyClass.refreshTotal()"></td>
                                                    <td class="subtotal">{{ rp($item->barang->hna * $item->qty) }}</td>
                                                    <td><input type="text"
                                                            name="keterangan_item_app[{{ $loop->iteration }}]"
                                                            class="form-control" value="{{ $item->keterangan_approval }}"></td>
                                                    <td>
                                                        <select name="status_item[{{ $loop->iteration }}]"
                                                            class="form-control">
                                                            <option value="approved" {{ $item->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="rejected" {{ $item->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="7">Total PR </td>
                                                <td>{{ rp($pr->nominal) }}</td>
                                                <td class="text-right">Total APP
                                                    <input type="hidden" value="{{ $pr->nominal }}" name="nominal">
                                                </td>
                                                <td colspan="3" id="harga-display">{{ rp($pr->nominal) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan_approval">
                                                        Keterangan Approval
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text" class="form-control" name="keterangan_approval"
                                                        value="{{ $pr->keterangan_approval }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                            @if ($pr->status == 'final')
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
                                            @elseif($pr->status == 'reviewed')
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/simrs/procurement/approval-pr/popup-pharmacy.js') }}"></script>
@endsection
