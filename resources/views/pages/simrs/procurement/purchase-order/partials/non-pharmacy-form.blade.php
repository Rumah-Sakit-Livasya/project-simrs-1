<style>
    .form-control {
        border: 0;
        border-bottom: 1.9px solid #eaeaea;
        margin-top: -.5rem;
        border-radius: 0;
    }
</style>
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form action="{{ route('procurement.purchase-order.non-pharmacy') }}" method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal_po">
                                                Tanggal PO
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="datepicker-1"
                                                placeholder="mm/dd/yyyy - mm/dd/yyyy" name="tanggal_po"
                                                value="{{ request('tanggal_po') }}" required>

                                            @error('tanggal_po')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="kode_po">
                                                Kode PO
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('kode_po') }}" class="form-control"
                                                id="kode_po" name="kode_po">
                                            @error('kode_po')
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
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="nama_barang">
                                                Nama Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama_barang') }}"
                                                class="form-control" id="nama_barang" name="nama_barang">
                                            @error('nama_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="status">
                                                Status Request
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="status" id="status-order" class="form-control">
                                                <option value="" selected hidden disabled>Pilih Status Order
                                                </option>
                                                <option {{ request('status') == '' ? 'selected' : '' }} value="">
                                                    Semua</option>
                                                <option {{ request('status') == 'draft' ? 'selected' : '' }}
                                                    value="draft">Draft</option>
                                                <option {{ request('status') == 'final' ? 'selected' : '' }}
                                                    value="final">Final</option>
                                                <option {{ request('status') == 'revision' ? 'selected' : '' }}
                                                    value="revision">Revision</option>
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
                                            <label class="form-label text-end" for="is_auto">
                                                Tipe Input
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="is_auto" id="tipe-input" class="form-control">
                                                <option value="0">Normal</option>
                                                <option value="1">Auto</option>
                                                <option value="">Semua</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="tipe">
                                                Tipe PO
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="tipe" id="tipe-input" class="form-control">
                                                <option value="" selected hidden disabled>Pilih Tipe PO</option>
                                                <option value="normal">Normal</option>
                                                <option value="urgent">Urgent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                            <div class="col-xl-3">
                                <button type="button" class="btn btn-primary waves-effect waves-themed"
                                    id="tambah-btn">
                                    <span class="fal fa-plus mr-1"></span>
                                    Tambah PO
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
