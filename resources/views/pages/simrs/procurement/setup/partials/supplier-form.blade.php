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

                    <form action="{{ route('procurement.setup.supplier') }}" method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="nama">
                                                Nama Supplier
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="nama" name="nama">
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="contact_person">
                                                Contact Person
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('contact_person') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="contact_person" name="contact_person">
                                            @error('contact_person')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="aktif">
                                                Status Aktif
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="aktif" id="status-aktif" class="form-control">
                                                <option value="" selected hidden disabled>Pilih Status Aktif
                                                </option>
                                                <option value="">Semua</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Non Aktif</option>
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
                                <button type="button" class="btn btn-primary waves-effect waves-themed" id="tambah-btn"
                                    data-bs-toggle="modal" data-bs-target="#addModal">
                                    <span class="fal fa-plus mr-1"></span>
                                    Tambah Supplier
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
