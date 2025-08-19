<!-- Tambah Laporan Modal -->
<div class="modal fade" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Tambah Laporan Baru</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-content">
            <form id="create-form-laporan" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="organization_id"
                        value="{{ auth()->user()->employee->organization_id }}">

                    <div class="row mb-3">
                        <!-- Input Tanggal Laporan -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label mb-1 font-weight-normal" for="create-tanggal">Tanggal
                                    Laproan<i class="text-danger">*</i></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                    </div>
                                    <input type="text" id="create-tanggal"
                                        class="form-control datepicker @error('tanggal') is-invalid @enderror"
                                        placeholder="Select a date" name="tanggal">
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Jenis Laporan -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create-jenis" class="font-weight-bold">Jenis Laporan</label>
                                <select class="form-control select2" id="create-jenis" name="jenis" required>
                                    <option value="" selected disabled>Pilih Jenis Laporan</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="kendala">Kendala</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pilih Unit (Organisasi) -->
                        <div class="col-md-4" id="create-organization-field" style="display: none">
                            <div class="form-group">
                                <label for="create-unit_terkait" class="font-weight-bold">Unit</label>
                                <select class="form-control select2" id="create-unit_terkait" name="unit_terkait">
                                    <option value="" selected disabled>Pilih Organisasi</option>
                                    @foreach (\App\Models\Organization::all() as $org)
                                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hanya untuk laporan kegiatan</small>
                            </div>
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="create-kegiatan" class="font-weight-bold">Uraian Lengkap</label>
                                <textarea class="form-control" id="create-kegiatan" name="kegiatan" rows="4" required
                                    placeholder="Deskripsikan laporan secara detail"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create-status" class="font-weight-bold">Status</label>
                                <select class="form-control select2" id="create-status" name="status" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="ditunda">Ditunda</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            <!-- Keterangan -->

                            <div class="form-group" id="create-keterangan-field" style="display: none;">
                                <label for="keterangan" class="font-weight-bold">Keterangan</label>
                                <textarea class="form-control" id="create-keterangan-field" name="keterangan" rows="4"
                                    placeholder="Deskripsikan keterangan status secara detail"></textarea>
                            </div>
                        </div>

                        {{-- @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)') --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Timeline</label>
                                    <div class="timeline-inputs">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">Masuk</span>
                                            </div>
                                            <input type="time" class="form-control" name="jam_masuk">
                                        </div>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">Diproses</span>
                                            </div>
                                            <input type="time" class="form-control" name="jam_diproses">
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">Selesai</span>
                                            </div>
                                            <input type="time" class="form-control" name="jam_selesai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- @endif --}}
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="create-dokumentasi" class="form-label">Dokumentasi (Opsional)</label>
                                <!-- Div untuk menampilkan preview gambar -->
                                <div id="image-preview" class="my-3" style="display: none;">
                                    <img id="preview-img" src="" alt="Preview"
                                        style="max-width: 100%; max-height: 200px; margin-top: 10px; border-radius: 11px">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="create-dokumentasi"
                                        name="dokumentasi" accept=".jpg,.jpeg,.png,.pdf">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                                <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
