<div class="modal fade" id="tambah-komponen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" id="store-form-component" method="post"
                enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Komponen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Komponen</label>
                        <input type="text" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            placeholder="Name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Kalkulasi</label>
                        <div class="frame-wrap">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="create-kurangi" name="calculate"
                                    value="min">
                                <label class="custom-control-label" for="create-kurangi">Potongan</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="create-tambah" name="calculate"
                                    value="plus">
                                <label class="custom-control-label" for="create-tambah">Tunjangan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="value">Nilai</label>
                        <input type="number" value="{{ old('value') }}"
                            class="form-control @error('value') is-invalid @enderror" id="value" name="value"
                            placeholder="Nilai">
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe</label>
                        <select class="select2 form-control w-100" id="type" name="type">
                            <option value=""></option>
                            @foreach (['general', 'flexible'] as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <select class="select2 form-control w-100" id="periode" name="periode">
                            <option value=""></option>
                            @foreach (['permenit', 'perjam', 'perhari', 'perminggu', 'perbulan'] as $periode)
                                <option value="{{ $periode }}">{{ ucfirst($periode) }}</option>
                            @endforeach
                        </select>
                        @error('periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
