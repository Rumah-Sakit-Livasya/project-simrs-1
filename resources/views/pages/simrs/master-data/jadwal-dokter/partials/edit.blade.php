<div class="modal fade" id="modal-edit-jadwal-dokter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- Form di-submit oleh javascript, jadi action dan method tidak wajib di sini --}}
            <form autocomplete="off" novalidate id="update-form">
                {{-- Method spoofing untuk PATCH/PUT --}}
                @method('PUT')
                @csrf
                <input type="hidden" id="edit-jadwal-id" name="jadwal_id">

                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Edit Jadwal Dokter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <div class="form-group">
                        <label for="edit-doctor-name">Dokter</label>
                        <input type="text" class="form-control" id="edit-doctor-name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit-hari">Hari</label>
                        <input type="text" class="form-control" id="edit-hari" readonly>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-jam-mulai">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="jam_mulai" id="edit-jam-mulai"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-jam-selesai">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="jam_selesai" id="edit-jam-selesai"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-kuota-regis-online">Kuota Registrasi Online</label>
                                <input type="number" class="form-control" name="kuota_regis_online"
                                    id="edit-kuota-regis-online">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false"
                        class="btn mx-1 btn-primary text-white">
                        <div class="ikon-edit">
                            <i class="fal fa-save mr-1"></i> Update
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
