<div class="modal fade" tabindex="-1" id="modal-edit" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="update-form">
                @method('patch')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Ruang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Ruang</label>
                        <input type="text" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            placeholder="Nama Ruang">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="room_code">Kode Ruang</label>
                        <input type="text" value="{{ old('room_code') }}"
                            class="form-control @error('room_code') is-invalid @enderror" id="room_code"
                            name="room_code" placeholder="Kode Ruang" onkeyup="this.value = this.value.toUpperCase()">
                        @error('room_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="floor">Lantai</label>
                        <input type="number" value="{{ old('floor') }}"
                            class="form-control @error('floor') is-invalid @enderror" id="floor" name="floor"
                            placeholder="Lantai">
                        @error('floor')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-group mb-3">
                            <label for="update-organization_id">Unit Penanggungjawab Ruangan <i
                                    class="fas fa-info-circle text-primary"
                                    data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                    data-toggle="tooltip"
                                    title="Unit yang bertanggungjawab atas ruangan ini"></i></label>
                            <!-- Mengubah input menjadi select2 -->
                            <select class="select2 form-control @error('organization_id') is-invalid @enderror"
                                name="organization_id[]" id="update-organization_id" multiple>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}">
                                        {{ old('organization_id', $organization->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organization_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" name="status"
                                value="1">
                            <label class="custom-control-label" for="status">Aktif</label>
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
