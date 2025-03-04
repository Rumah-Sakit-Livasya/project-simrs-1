<div class="modal fade" id="modal-tambah-tindakan" tabindex="-1" aria-hidden="true" data-id="{{$registration->id}}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Tambah Tindakan Medis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @csrf
                @method('post')
                <input type="hidden" id="registration" value="{{ $registration->id }}">
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="tglTindakan" class="col-sm-3 col-form-label">Tgl Tindakan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="tglTindakan" placeholder="Pilih tanggal">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="dokterPerawat" class="col-sm-3 col-form-label">Dokter/Perawat</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="dokterPerawat" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($groupedDoctors as $department => $doctors)
                                    <optgroup label="{{ $department }}">
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" data-departement="{{ $department }}">
                                                {{ $doctor->employee->fullname }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="poliklinik" class="col-sm-3 col-form-label">Poliklinik</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="departement" style="width: 100%;">
                                @foreach ($departements as $departement)
                                    <option value="{{ $departement->id }}"
                                        data-groups="{{ $departement->grup_tindakan_medis ? json_encode($departement->grup_tindakan_medis->toArray()) : '[]' }}">
                                        {{ $departement->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kelas" class="col-sm-3 col-form-label">Kelas</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="kelas" style="width: 100%;">
                                <option value="" selected>Pilih Kelas</option>
                                <option value="rawat-jalan">RAWAT JALAN</option>
                                <option value="rawat-inap">RAWAT INAP</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tindakanMedis" class="col-sm-3 col-form-label">Tindakan Medis</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="tindakanMedis" style="width: 100%;">
                                <option value="" selected>Pilih Tindakan Medis</option>
                                {{-- @dd($tindakan_medis) --}}
                                @foreach ($tindakan_medis as $item)
                                    <option value="{{$item->id}}">{{$item->nama_tindakan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="qty" value="1">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="diskonDokter" class="col-sm-3 col-form-label">Diskon Dokter</label>
                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="diskonDokter">
                                <label class="form-check-label" for="diskonDokter">Ya</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>