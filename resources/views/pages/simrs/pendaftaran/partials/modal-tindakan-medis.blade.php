    <div class="modal fade" id="modal-tambah-tindakan" tabindex="-1" role="dialog" aria-hidden="true"
        data-id="{{ $registration->id }}">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-loading-overlay" style="display: none;">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <h4 class="ml-3">Memproses data...</h4>
                    </div>
                </div>

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Tambah Tindakan Medis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <form id="store-form" method="post" action="javascript:void(0)" autocomplete="off" novalidate>
                    @csrf
                    @method('post')
                    <input type="hidden" id="registration" value="{{ $registration->id }}">

                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="tglTindakan" class="col-sm-3 col-form-label">Tgl Tindakan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="tglTindakan" name="tgl_tindakan"
                                    placeholder="Pilih tanggal">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dokterPerawat" class="col-sm-3 col-form-label">Dokter/Perawat</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="dokterPerawat" name="doctor_id" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach ($groupedDoctors as $department => $doctors)
                                        <optgroup label="{{ $department }}">
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    data-departement="{{ $department }}">
                                                    {{ $doctor->employee->fullname }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="departement" class="col-sm-3 col-form-label">Poliklinik</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="departement" name="departement_id" style="width: 100%;">
                                    @foreach ($dTindakan as $departement)
                                        <option value="{{ $departement->id }}"
                                            data-groups="{{ $departement->grup_tindakan_medis ? json_encode($departement->grup_tindakan_medis->toArray()) : '[]' }}">
                                            {{ $departement->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kelas" class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="kelas" name="kelas" style="width: 100%;">
                                    @foreach ($kelas_rawats as $kelas)
                                        <option value="{{ $kelas->id }}">
                                            {{ $kelas->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tindakanMedis" class="col-sm-3 col-form-label">Tindakan Medis</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="tindakanMedis" name="tindakan_medis_id"
                                    style="width: 100%;">
                                    {{-- Dibuat kosong, hanya ada placeholder awal --}}
                                    <option value="" selected>Pilih Poliklinik terlebih dahulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="qty" name="qty" value="1"
                                    min="1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="diskonDokter" class="col-sm-3 col-form-label">Diskon Dokter</label>
                            <div class="col-sm-9">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="diskonDokter" name="foc">
                                    <label class="form-check-label" for="diskonDokter">Ya</label>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Tindakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
