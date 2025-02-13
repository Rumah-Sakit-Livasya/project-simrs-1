<div class="modal fade p-0" id="tambah-data" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form" enctype="multipart/form-data">
                <input type="hidden" name="type" value="rapat">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Agenda Rapat</h5>
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

                    <div class="form-group mb-3">
                        <label class="form-label mb-1 font-weight-normal" for="employee_id">Yang Mengundang<i
                                class="text-danger">*</i></label>
                        <select class="select2 form-control @error('employee_id') is-invalid @enderror"
                            name="employee_id" id="create-employee_id">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ old('employee_id', $employee->fullname) }}
                                </option>
                            @endforeach
                        </select>
                        @error('peserta')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title" class="font-weight-normal">Judul Rapat<i class="text-danger">*</i>
                        </label>
                        <input type="text" value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                            placeholder="Masukan Judul Rapat">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="create-peserta">Peserta Rapat<i class="text-danger">*</i></label>
                        <div class="row">
                            <div class="col-6">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1checkbox"
                                        name="direktur">
                                    <label class="custom-control-label" for="customSwitch1checkbox">Direktur <i
                                            class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['direktur'] !!}"></i></label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch2checkbox"
                                        name="wakil_direktur">
                                    <label class="custom-control-label" for="customSwitch2checkbox">Wakil
                                        Direktur <i class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['wakil_direktur'] !!}"></i></label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="kabag" name="kabag">
                                    <label class="custom-control-label" for="kabag">Kabag <i
                                            class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['kabag'] !!}"></i>
                                    </label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="kabid"
                                        name="kabid">
                                    <label class="custom-control-label" for="kabid">Kabid <i
                                            class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['kabid'] !!}"></i>
                                    </label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="kasubag"
                                        name="kasubag">
                                    <label class="custom-control-label" for="kasubag">Kepala Sub Bagian <i
                                            class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['kasubag'] !!}"></i></label>
                                </div>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="kasi"
                                        name="kasi">
                                    <label class="custom-control-label" for="kasi">Kepala Seksi<i
                                            class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['kasi'] !!}"></i></label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch5checkbox"
                                        name="karu_pelayanan">
                                    <label class="custom-control-label" for="customSwitch5checkbox">All Karu unit
                                        Pelayanan <i class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['karu_pelayanan'] !!}"></i></label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch6checkbox"
                                        name="pj_penunjang">
                                    <label class="custom-control-label" for="customSwitch6checkbox">All PJ Unit
                                        Penunjang <i class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['pj_penunjang'] !!}"></i></label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch7checkbox"
                                        name="pj_umum">
                                    <label class="custom-control-label" for="customSwitch7checkbox">All PJ Unit Bagian
                                        Umum <i class="fas fa-info-circle text-primary"
                                            data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                            data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="{!! $tooltipNames['pj_umum'] !!}"></i></label>
                                </div>
                            </div>
                        </div>

                        <!-- Mengubah input menjadi select2 -->
                        <div class="form-group mb-3">
                            <select class="select2 form-control @error('peserta') is-invalid @enderror"
                                name="peserta[]" id="create-peserta" multiple>
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
                            <label class="form-label font-weight-normal" for="perihal">Perihal<i
                                    class="text-danger">*
                                </i></label>
                            <textarea class="form-control @error('perihal') is-invalid @enderror" name="perihal" id="perihal" rows="5"></textarea>
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="is_online" name="is_online"
                                    value="1" onchange="toggleRoomName()">
                                <label class="custom-control-label" for="is_online">Online</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="room_name" class="font-weight-normal">Tempat / Nama Ruangan Rapat<i
                                    class="text-danger">*</i>
                            </label>
                            <input type="text" value="{{ old('room_name') }}"
                                class="form-control @error('room_name') is-invalid @enderror" id="room_name"
                                name="room_name" placeholder="Masukan Nama Ruangan">
                            @error('room_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="undangan">Undangan (File) <span class="text-danger fw-bold">*</span></label>
                            <div class="custom-file">
                                <input type="file"
                                    class="custom-file-input @error('undangan') is-invalid @enderror" name="undangan"
                                    id="undangan">
                                <label class="custom-file-label" for="undangan">Choose file</label>
                            </div>
                            @error('undangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="materi">Materi (File) </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('materi') is-invalid @enderror"
                                    name="materi" id="materi">
                                <label class="custom-file-label" for="materi">Choose file</label>
                            </div>
                            @error('materi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="absensi">Absensi (File) </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('absensi') is-invalid @enderror"
                                    name="absensi" id="absensi">
                                <label class="custom-file-label" for="absensi">Choose file</label>
                            </div>
                            @error('absensi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="notulen">Notulen (File) </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('notulen') is-invalid @enderror"
                                    name="notulen" id="notulen">
                                <label class="custom-file-label" for="notulen">Choose file</label>
                            </div>
                            @error('notulen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
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
