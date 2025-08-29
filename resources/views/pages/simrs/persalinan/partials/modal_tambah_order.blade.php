  <div class="modal fade" id="modal-order-vk" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <form id="form-order-vk" onsubmit="return false;">
                  @csrf
                  <input type="hidden" id="order_vk_id" name="order_vk_id">
                  <input type="hidden" id="selected_registration_id" name="registration_id">
                  <div class="modal-header bg-primary-600 text-white">
                      <h5 class="modal-title" id="modalOrderVKLabel">Input Order Persalinan</h5>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <!-- Step 1 -->
                      <div id="vk-step-1">
                          <div class="form-group" id="registration-info">
                              <label>Pasien</label>
                              <div class="alert alert-info py-2">
                                  <strong id="patient-info">Loading...</strong>
                              </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label class="required-label">Tanggal Persalinan</label>
                                  <input type="datetime-local" class="form-control" name="tgl_persalinan" required>
                              </div>
                              <div class="form-group col-md-6">
                                  <label class="required-label">Kelas Rawat</label>
                                  <select class="form-control select2-modal" name="kelas_rawat_id" required
                                      style="width: 100%;"></select>
                              </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label class="required-label">Dokter/Bidan Operator</label>
                                  <select class="form-control select2-modal" name="dokter_bidan_operator_id" required
                                      style="width: 100%;"></select>
                              </div>
                              <div class="form-group col-md-6">
                                  <label>Asisten Operator</label>
                                  <select class="form-control select2-modal" name="asisten_operator_id"
                                      style="width: 100%;"></select>
                              </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label>Dokter Resusitator</label>
                                  <select class="form-control select2-modal" name="dokter_resusitator_id"
                                      style="width: 100%;"></select>
                              </div>
                              <div class="form-group col-md-6">
                                  <label>Dokter Anestesi</label>
                                  <select class="form-control select2-modal" name="dokter_anestesi_id"
                                      style="width: 100%;"></select>
                              </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label>Asisten Anestesi</label>
                                  <select class="form-control select2-modal" name="asisten_anestesi_id"
                                      style="width: 100%;"></select>
                              </div>
                              <div class="form-group col-md-6">
                                  <label>Dokter Umum</label>
                                  <select class="form-control select2-modal" name="dokter_umum_id"
                                      style="width: 100%;"></select>
                              </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label class="required-label">Tipe Penggunaan</label>
                                  <select class="form-control select2-modal" name="tipe_penggunaan_id" required
                                      style="width: 100%;"></select>
                              </div>
                              <div class="form-group col-md-6">
                                  <label class="required-label">Kategori</label>
                                  <select class="form-control select2-modal" name="kategori_id" required
                                      style="width: 100%;"></select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="required-label">Melahirkan Bayi?</label>
                              <div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="melahirkan_bayi"
                                          id="melahirkan_ya" value="1" required>
                                      <label class="form-check-label" for="melahirkan_ya">Ya</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="melahirkan_bayi"
                                          id="melahirkan_tidak" value="0">
                                      <label class="form-check-label" for="melahirkan_tidak">Tidak</label>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- Step 2 -->
                      <div id="vk-step-2" class="d-none">
                          <h5 class="required-label">Pilih Satu Tindakan Utama</h5>
                          <div id="tindakan-grid-container" class="tindakan-grid"></div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                      <button type="button" class="btn btn-secondary" id="btn-vk-kembali"
                          style="display:none;">Kembali</button>
                      <button type="button" class="btn btn-primary" id="btn-vk-lanjut">Lanjut</button>
                      <button type="button" class="btn btn-primary" id="btn-simpan-order-vk" style="display:none;">
                          <i class="fas fa-save mr-1"></i>Simpan
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
