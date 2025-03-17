<div class="modal fade" id="modal-move" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" enctype="multipart/form-data"
                id="move-form">
                @method('patch')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Pindahkan Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="barang_id" id="barang_id">
                    <input type="hidden" name="item_code" id="item_code">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="form-group">
                        <label for="room_id">Nama Ruang</label>
                        <select class="form-control w-100 @error('room_id') is-invalid @enderror" id="room_id"
                            name="room_id">
                            <optgroup label="Ruangan">
                                @foreach ($allRoom as $room)
                                    <option value="{{ $room->id }}">
                                        {{ strtoupper($room->name) }}
                                    </option>
                                @endforeach
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </optgroup>
                        </select>
                        @error('room_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-arrow-circle-right mr-1"></span>
                        Pindahkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
