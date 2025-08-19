<div class="modal fade" id="modal-order-vk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Order Persalinan (VK)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-order-vk" onsubmit="return false;">
                    <input type="hidden" name="registration_id" id="vk_registration_id"
                        value="{{ $registration->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" id="order_vk_id" name="order_vk_id"> {{-- Untuk edit --}}

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="vk_no_registrasi">No Registrasi <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="vk_no_registrasi" class="form-control"
                                value="{{ $registration->registration_number }}" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="tgl_persalinan">Tgl Rencana Persalinan <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="tgl_persalinan" name="tgl_rencana_persalinan"
                                    class="form-control" placeholder="Pilih tanggal..." required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="dokter_dpjp_vk">Dokter DPJP <span
                                    class="text-danger">*</span></label>
                            <select id="dokter_dpjp_vk" name="dokter_id" class="form-control select2-modal-vk" required>
                                {{-- Opsi dimuat oleh AJAX --}}
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="bidan_vk">Bidan <span class="text-danger">*</span></label>
                            <select id="bidan_vk" name="bidan_id" class="form-control select2-modal-vk" required>
                                {{-- Opsi dimuat oleh AJAX --}}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="jenis_persalinan">Jenis Persalinan <span
                                    class="text-danger">*</span></label>
                            <select id="jenis_persalinan" name="jenis_persalinan_id"
                                class="form-control select2-modal-vk" required>
                                {{-- Opsi dimuat oleh AJAX --}}
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="kelas_vk">Kelas <span class="text-danger">*</span></label>
                            <select id="kelas_vk" name="kelas_id" class="form-control select2-modal-vk" required>
                                {{-- Opsi dimuat oleh AJAX --}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="indikasi_vk">Indikasi / Keterangan</label>
                        <textarea id="indikasi_vk" name="indikasi" rows="3" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-order-vk">Simpan Order</button>
            </div>
        </div>
    </div>
</div>
