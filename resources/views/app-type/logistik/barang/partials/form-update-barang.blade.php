<div class="modal fade" id="modal-ubah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" enctype="multipart/form-data"
                id="update-form">
                @method('patch')
                @csrf
                <input type="hidden" name="barang_id" id="barang_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="template_barang_id">Barang</label>
                                <select class="form-control w-100 @error('template_barang_id') is-invalid @enderror"
                                    id="template_barang_id" name="template_barang_id">
                                    <optgroup label="Template Barang">
                                        @foreach ($templates as $template)
                                            <option value="{{ $template->id }}">
                                                {{ strtoupper($template->name) }}
                                            </option>
                                        @endforeach
                                        @error('template_barang_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </optgroup>
                                </select>
                                @error('template_barang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="condition">Kondisi Barang</label>
                                <select class="form-control w-100 @error('condition') is-invalid @enderror"
                                    id="condition" name="condition">
                                    <optgroup label="Kondisi Barang">
                                        <option value="Baik">BAIK</option>
                                        <option value="Rusak">RUSAK</option>
                                    </optgroup>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="bidding_year">
                                    Tahun Pengadaan
                                </label>
                                <select class="form-control w-100 @error('bidding_year') is-invalid @enderror"
                                    id="bidding_year" name="bidding_year">
                                    <optgroup label="Tahun Pengadaan">
                                        <option value="2010">2010</option>
                                        <option value="2011">2011</option>
                                        <option value="2012">2012</option>
                                        <option value="2013">2013</option>
                                        <option value="2014">2014</option>
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                    </optgroup>
                                </select>
                                @error('bidding_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="custom_name">Nama Barang<sup>(Opsional)</sup></label>
                                <input type="text" value="{{ old('custom_name') }}"
                                    class="form-control @error('custom_name') is-invalid @enderror" id="custom_name"
                                    name="custom_name" placeholder="Nama Barang">
                                @error('custom_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="item_code">Kode Barang</label>
                                <input type="text" value="{{ old('item_code') }}"
                                    class="form-control @error('item_code') is-invalid @enderror" id="item_code"
                                    name="item_code" placeholder="Kode Barang" readonly>
                                @error('item_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="merk">Merk
                                    <sup>(Opsional)</sup></label>
                                <input type="text" value="{{ old('merk') }}"
                                    class="form-control @error('merk') is-invalid @enderror" id="merk"
                                    name="merk" placeholder="Merk">
                                @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="urutan_barang">Urutan Barang</label>
                                <input type="text" value="{{ old('urutan_barang') }}"
                                    class="form-control @error('urutan_barang') is-invalid @enderror" id="urutan_barang"
                                    name="urutan_barang" placeholder="Kode Barang" readonly>
                                @error('urutan_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        @if (Auth::user()->hasRole('super admin'))
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="company_id">
                                        Perusahaan
                                    </label>
                                    <select class="form-control w-100 @error('company_id') is-invalid @enderror"
                                        id="company_id" name="company_id">
                                        <optgroup label="Perusahaan">
                                            @foreach ($companies as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="harga_barang">Harga Barang</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" id="harga_barang"
                                        name="harga_barang">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil mr-1"></span>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
