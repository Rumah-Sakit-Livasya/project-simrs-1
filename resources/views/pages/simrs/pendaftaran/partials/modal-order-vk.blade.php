<!-- Modal Persalinan (VK) -->
<div class="modal fade" id="modal-order-vk" tabindex="-1" role="dialog" aria-labelledby="modalOrderVKLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-order-vk">
                <input type="hidden" id="order_vk_id" name="order_vk_id">
                <input type="hidden" id="vk_registration_id" name="registration_id"
                    value="{{ $registration->id ?? 0 }}">

                <div class="modal-header bg-primary-600 text-white">
                    <h5 class="modal-title" id="modalOrderVKLabel">Input Tindakan Persalinan (VK)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Step 1: Informasi Persalinan -->
                    <div id="vk-step-1">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tgl_rencana_persalinan">Tanggal Persalinan <span
                                        class="required">*</span></label>
                                <input type="datetime-local" class="form-control" id="tgl_rencana_persalinan"
                                    name="tgl_persalinan" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kelas_rawat">Kelas Rawat <span class="required">*</span></label>
                                <select class="form-control" id="kelas_rawat" name="kelas_rawat_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dokter_bidan_operator">Dokter/Bidan Operator <span
                                        class="required">*</span></label>
                                <select class="form-control select2" id="dokter_bidan_operator"
                                    name="dokter_bidan_operator_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="asisten_operator">Asisten Operator</label>
                                <select class="form-control select2" id="asisten_operator" name="asisten_operator_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dokter_resusitator">Dokter Resusitator</label>
                                <select class="form-control select2" id="dokter_resusitator"
                                    name="dokter_resusitator_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dokter_anestesi">Dokter Anestesi</label>
                                <select class="form-control select2" id="dokter_anestesi" name="dokter_anestesi_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="asisten_anestesi">Asisten Anestesi</label>
                                <select class="form-control select2" id="asisten_anestesi" name="asisten_anestesi_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dokter_umum">Dokter Umum</label>
                                <select class="form-control select2" id="dokter_umum" name="dokter_umum_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tipe_persalinan">Tipe Penggunaan <span class="required">*</span></label>
                                <select class="form-control select2" id="tipe_persalinan" name="tipe_penggunaan_id"
                                    required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kategori">Kategori <span class="required">*</span></label>
                                <select class="form-control select2" id="kategori" name="kategori_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Melahirkan Bayi ? <span class="required">*</span></label>
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

                    <!-- Step 2: Pilih Tindakan -->
                    <div id="vk-step-2" class="d-none">
                        <div id="tindakan-grid-container" class="tindakan-grid">
                            <!-- Checkbox tindakan akan di-load via JS -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-vk-kembali"
                        style="display:none;">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn-vk-lanjut">Lanjut</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-order-vk" style="display:none;">
                        <i class="fas fa-save mr-1"></i>Simpan Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Debug untuk Test Dropdown Kelas Rawat -->
<div class="modal fade" id="modal-debug-kelas_rawat" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Debug Kelas Rawat Dropdown</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="debug-kelas_rawat">Kelas Rawat (Native Dropdown)</label>
                    <select class="form-control" id="debug-kelas_rawat">
                        <option value="">Loading...</option>
                    </select>
                    <small class="text-muted">Total options: <span id="debug-kelas_rawat-count">0</span></small>
                </div>
                <div class="mt-3">
                    <h6>Data dari API:</h6>
                    <pre id="debug-kelas_rawat-json"
                        style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px; max-height: 200px; overflow-y: auto;"></pre>
                </div>
                <div class="mt-3">
                    <h6>HTML Dropdown:</h6>
                    <pre id="debug-kelas_rawat-html"
                        style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px; max-height: 150px; overflow-y: auto;"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-test-load-kelas_rawat">Test Load Data</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
