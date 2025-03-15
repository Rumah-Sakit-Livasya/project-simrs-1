<div class="modal fade" id="ubah-piutang{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('piutang.update', $p->id) }}" method="post">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Piutang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="datepicker-modal-2">Tanggal Piutang</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                    </div>
                                    <input type="text" id="datepicker-modal-2" class="form-control"
                                        placeholder="Tanggal Piutang" aria-label="date"
                                        aria-describedby="datepicker-modal-2" name="tanggal"
                                        value="{{ old('tanggal', $p->id) }}">
                                </div>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="nominal">Nominal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="number" id="nominal" class="form-control" placeholder="Nominal"
                                        name="nominal" aria-label="Nominal" aria-describedby="nominal"
                                        value="{{ old('nominal', $p->nominal) }}">
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                    rows="5">{{ old('keterangan', $p->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
