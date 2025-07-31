<style>
    input {
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

                    <form action="{{ route('procurement.approval-po.pharmacy') }}" method="get">
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
                                                placeholder="mm/dd/yyyy - mm/dd/yyyy" name="tanggal_po">

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
                                            <label class="form-label text-end" for="approval">
                                                Approval Status
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="approval" id="approval-status" class="form-control">
                                                <option {{ request('approval') == 'unreviewed' ? 'selected' : '' }}
                                                    value="unreviewed">Unreviewed</option>
                                                <option {{ request('approval') == 'approve' ? 'selected' : '' }}
                                                    value="approve">Approved</option>
                                                <option {{ request('approval') == 'reject' ? 'selected' : '' }}
                                                    value="reject">Rejected</option>
                                                <option {{ request('approval') == 'revision' ? 'selected' : '' }}
                                                    value="revision">Revision</option>
                                                <option {{ request('approval') == 'all' ? 'selected' : '' }}
                                                    value="all">Semua</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="tipe">
                                                Tipe PO
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="tipe" id="tipe-request" class="form-control">
                                                <option value="" selected hidden disabled>Pilih Tipe Order
                                                </option>
                                                <option {{ !request('tipe') ? 'selected' : '' }} value="">Semua
                                                </option>
                                                <option {{ request('tipe') == 'normal' ? 'selected' : '' }}
                                                    value="normal">Normal</option>
                                                <option {{ request('tipe') == 'urgent' ? 'selected' : '' }}
                                                    value="urgent">Urgent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                        </div>
                                        <div class="col-xl" style="text-align: center">
                                            <button type="submit"
                                                class="btn btn-outline-primary waves-effect waves-themed">
                                                <span class="fal fa-search mr-1"></span>
                                                Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
