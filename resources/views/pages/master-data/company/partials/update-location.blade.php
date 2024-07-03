<div class="modal fade p-0" id="ubah-lokasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form-location" data-id="">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Lokasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" value="{{ old('latitude') }}"
                            class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude"
                            placeholder="Masukan Latitude">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" value="{{ old('longitude') }}"
                            class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                            name="longitude" placeholder="Masukan Longitude">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius</label>
                        <input type="text" value="{{ old('radius') }}"
                            class="form-control @error('radius') is-invalid @enderror" id="radius" name="radius"
                            placeholder="Masukan Radius">
                        @error('radius')
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
