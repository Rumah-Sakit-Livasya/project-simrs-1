<div class="modal fade p-0" id="tambah-data" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Pendidikan dan Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="form-group">
                        <label class="form-label mb-1 font-weight-normal" for="datepicker-modal-2">Tanggal<i
                                class="text-danger">*</i></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                            </div>
                            <input type="datetime-local" id="datepicker-modal-2"
                                class="form-control @error('datetime') is-invalid @enderror" placeholder="Select a date"
                                name="datetime">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="judul" class="font-weight-normal">Judul Diklat<i class="text-danger">*</i>
                        </label>
                        <input type="text" value="{{ old('judul') }}"
                            class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
                            placeholder="Masukan Judul Diklat">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pembicara" class="font-weight-normal">Pembicara Diklat<i class="text-danger">*</i>
                        </label>
                        <input type="text" value="{{ old('pembicara') }}"
                            class="form-control @error('pembicara') is-invalid @enderror" id="pembicara"
                            name="pembicara" placeholder="Masukan Pembicara Diklat">
                        @error('pembicara')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tempat" class="font-weight-normal">Tempat Diklat<i class="text-danger">*</i>
                        </label>
                        <input type="text" value="{{ old('tempat') }}"
                            class="form-control @error('tempat') is-invalid @enderror" id="tempat" name="tempat"
                            placeholder="Masukan Tempat Diklat">
                        @error('tempat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="create-peserta">Peserta Pelatihan<i class="text-danger">*</i></label>
                        <!-- Mengubah input menjadi select2 -->
                        <div class="form-group mb-3">
                            <select class="select2 form-control @error('peserta') is-invalid @enderror" name="peserta[]"
                                id="create-peserta" multiple>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ old('peserta', $employee->fullname) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peserta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-weight-normal" for="catatan">Catatan<i class="text-danger">*
                                </i></label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan" rows="5"></textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch mb-3">
                                <input type="radio" class="custom-control-input" id="internal" name="type"
                                    value="internal">
                                <label class="custom-control-label" for="internal">Internal</label>
                            </div>
                            <div class="custom-control custom-switch mb-3">
                                <input type="radio" class="custom-control-input" id="eksternal" name="type"
                                    value="eksternal" onchange="toggleRoomName()">
                                <label class="custom-control-label" for="eksternal">Eksternal</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
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
