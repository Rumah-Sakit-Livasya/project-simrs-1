<style>
    .modal {
        top: 0 !important;
    }
</style>

<div class="modal fade font-weight-bold p-0" id="ubah-identitas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-identity-form">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Identitas &amp; Alamat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="identity_type">Tipe ID</label>
                                <input type="text" value="{{ old('identity_type') }}"
                                    class="form-control @error('identity_type') is-invalid @enderror" id="identity-type"
                                    name="identity_type" placeholder="Nama">
                                @error('identity_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Kode Pos</label>
                                <input type="text" value="{{ old('postal_code') }}"
                                    class="form-control @error('postal_code') is-invalid @enderror" id="postal-code"
                                    name="postal_code" placeholder="Nama">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="identity-number">No ID</label>
                                <input type="text" id="identity-number" class="form-control" name="identity_number"
                                    autocomplete="off" placeholder="O">
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="citizen-id-address">Alamat KTP</label>
                                <input type="text" id="citizen-id-address" class="form-control"
                                    name="citizen_id_address" autocomplete="off" placeholder="O">
                                @error('citizen_id_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="identity-expire-date">Tanggal kedaluwarsa ID</label>
                                <input type="text" id="identity-expire-date"
                                    class="form-control @error('identity_expire_date') is-invalid @enderror"
                                    name="identity_expire_date" autocomplete="off" placeholder="Permanen">
                                @error('identity_expire_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="residental-address">Tempat Tinggal</label>
                                <input type="text" id="residental-address" class="form-control"
                                    name="residental_address" autocomplete="off" placeholder="Tempat Tinggal">
                                @error('residental_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
