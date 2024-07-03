<div class="modal fade font-weight-bold p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="form-group">
                        <label for="name">Nama Shift</label>
                        <input type="text" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            placeholder="Masukan Nama Shift">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="name">Jam Masuk</label>
                        <input type="time" value="{{ old('time_in') }}"
                            class="form-control @error('time_in') is-invalid @enderror" id="time_in" name="time_in"
                            placeholder="Masukan Deksripsi">
                        @error('time_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="name">Jam Keluar</label>
                        <input type="time" value="{{ old('time_out') }}"
                            class="form-control @error('time_out') is-invalid @enderror" id="time_out" name="time_out"
                            placeholder="Masukan Deksripsi">
                        @error('time_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="name">Status</label>
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="status" class="form-control">
                            <input type="checkbox" class="custom-control-input @error('status') is-invalid @enderror"
                                id="status">
                            <label class="custom-control-label" for="status" id="status-text"></label>
                        </div>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-edit">
                            <span class="fal fa-pencil mr-1"></span>
                            Ubah
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
