<div class="modal fade p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" name="date" id="date" disabled>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="shift_id">Shift</label>
                                <select class="select2 form-control w-100  @error('shift_id') is-invalid @enderror"
                                    id="shift_id" name="shift_id">
                                    <option value=""></option>
                                    @foreach ($shifts as $col)
                                        <option value="{{ $col->id }}">
                                            {{ $col->name }} -
                                            {{ '(' . $col->time_in . ' - ' . $col->time_out . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="clock_in">Clock In</label>
                                <input type="time" class="form-control" id="clock_in" name="clock_in">
                                @error('clock_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="clock_out">Clock Out</label>
                                <input type="time" class="form-control" id="clock_out" name="clock_out">
                                @error('clock_out')
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
