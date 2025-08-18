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

                        <div class="col-12" id="create-maintenance-field" style="display: none;">
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Tipe Kegiatan</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create-check-maintenance"
                                        name="jenis_kendala_checkbox[]" value="Maintenance">
                                    <label class="custom-control-label" for="create-check-maintenance">Terkait
                                        Maintenance Inventaris</label>
                                </div>
                            </div>
                        </div>

                        <!-- [BARU] Field dinamis untuk Perbaikan (muncul saat 'Kendala' dipilih) -->
                        <div class="col-12" id="create-perbaikan-field" style="display: none;">
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Tipe Kendala</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create-check-perbaikan"
                                        name="jenis_kendala_checkbox[]" value="Perbaikan">
                                    <label class="custom-control-label" for="create-check-perbaikan">Terkait Perbaikan
                                        Inventaris</label>
                                </div>
                            </div>
                        </div>

                        <!-- [BARU] Field dinamis untuk Ruangan & Barang (muncul saat checkbox di centang) -->
                        <div class="col-12" id="create-room-item-fields" style="display: none;">
                            <div class="card border p-3">
                                <div class="row">
                                    <!-- Pilih Ruangan -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="create-room" class="font-weight-bold">Pilih Ruangan</label>
                                            <select class="form-control select2" id="create-room"
                                                name="room_maintenance_id">
                                                <option value="" selected disabled>Pilih Ruangan</option>
                                                @if (isset($rooms))
                                                    @foreach ($rooms as $room)
                                                        <option value="{{ $room->id }}">
                                                            {{ strtoupper($room->name) }}
                                                        </option> <!-- Pastikan 'name' benar -->
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Pilih Barang -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="create-barang" class="font-weight-bold">Pilih Barang</label>
                                            <select class="form-control select2" id="create-barang" name="barang_id"
                                                disabled>
                                                <option value="" selected disabled>Pilih Ruangan Dulu</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div id="create-maintenance-details" style="display: none;">
                                <hr>
                                <h5 class="mb-3">Detail Maintenance</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="maintenance-kondisi" class="font-weight-bold">Kondisi Awal
                                                Barang <i class="text-danger">*</i></label>
                                            <textarea class="form-control" name="maintenance_kondisi" id="maintenance-kondisi" rows="3"
                                                placeholder="Contoh: Mati total, layar bergaris, ..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="maintenance-hasil" class="font-weight-bold">Hasil Maintenance
                                                <i class="text-danger">*</i></label>
                                            <textarea class="form-control" name="maintenance_hasil" id="maintenance-hasil" rows="3"
                                                placeholder="Contoh: Berhasil diperbaiki, penggantian komponen X, ..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- [GANTI DENGAN BLOK INI] -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="maintenance-estimasi" class="font-weight-bold">Estimasi
                                                Selesai (Opsional)</label>
                                            <input type="date" class="form-control" name="maintenance_estimasi"
                                                id="maintenance-estimasi">
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Status dan Foto hasil maintenance akan diambil secara otomatis dari Status dan
                                    Dokumentasi Laporan Internal.
                                </small>
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

                        @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)')
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
                        @endif
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
