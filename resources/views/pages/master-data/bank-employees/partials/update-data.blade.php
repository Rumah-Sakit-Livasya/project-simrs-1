<div class="modal fade p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="Pegawai">Pegawai</label>
                        <select class="select2 form-control w-100  @error('employee_id') is-invalid @enderror"
                            id="employee_id" name="employee_id">
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="name">Nama Bank</label>
                        <select class="select2 form-control w-100  @error('name') is-invalid @enderror" id="bank_id"
                            name="bank_id">
                            @foreach ($banks as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="account_number">Nomor Rekening</label>
                        <input type="text" value="{{ old('account_number') }}"
                            class="form-control @error('account_number') is-invalid @enderror" id="account_number"
                            name="account_number" placeholder="Masukan Nomor Rekening">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="account_holder_name">Nama Rekening</label>
                        <input type="hidden" name="status" value="on">
                        <input type="text" value="{{ old('account_holder_name') }}"
                            class="form-control @error('account_holder_name') is-invalid @enderror"
                            id="account_holder_name" name="account_holder_name" placeholder="Masukan Nama Rekening">
                        @error('account_holder_name')
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
