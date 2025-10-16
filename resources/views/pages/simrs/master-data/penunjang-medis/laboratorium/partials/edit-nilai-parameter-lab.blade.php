<div class="modal fade" id="modal-edit-nilai-parameter-laboratorium" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{-- Menggunakan ID yang konsisten: update-form --}}
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="update-form">
                @method('PATCH') {{-- Method yang lebih sesuai untuk update --}}
                @csrf
                {{-- Input tersembunyi untuk ID record dan User yang mengedit --}}
                <input type="hidden" name="id" id="id_edit">
                <input type="hidden" name="user_input" value="{{ auth()->user()->id }}">

                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Edit Nilai Parameter Laboratorium</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-2 row">
                    <div class="col-md-12">
                        <hr style="border-color: #dedede;" class="mb-1 mt-1">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            {{-- Tanggal & User disederhanakan, karena biasanya tidak diubah --}}
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label>Tanggal Update</label>
                                    <input type="text" class="form-control" value="{{ now()->format('d-m-Y') }}"
                                        disabled>
                                    <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label>User</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="parameter_laboratorium_id_edit">Parameter <span
                                            class="text-danger fw-bold">*</span></label>
                                    {{-- Menggunakan ID dengan sufiks _edit --}}
                                    <select class="select2 form-control w-100" id="parameter_laboratorium_id_edit"
                                        name="parameter_laboratorium_id">
                                        <option value=""></option>
                                        @foreach ($parameter as $row)
                                            @if ($row->is_hasil)
                                                <option value="{{ $row->id }}">{{ $row->parameter }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Jenis Kelamin</label>
                                    {{-- Menggunakan ID unik dengan sufiks _edit --}}
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="laki_laki_edit"
                                            name="jenis_kelamin" value="Laki-laki">
                                        <label class="custom-control-label" for="laki_laki_edit">Laki - Laki</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="perempuan_edit"
                                            name="jenis_kelamin" value="Perempuan">
                                        <label class="custom-control-label mr-1" for="perempuan_edit">Perempuan</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="semuanya_edit"
                                            name="jenis_kelamin" value="Semuanya">
                                        <label class="custom-control-label" for="semuanya_edit">Semuanya</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Dari Umur</label>
                                    <div class="form-umur-wrapper d-flex align-items-center">
                                        <input type="text" name="tahun_1" id="tahun_1_edit" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="tahun_1_edit" class="form-label d-inline mr-2">Tahun</label>
                                        <input type="text" name="bulan_1" id="bulan_1_edit" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="bulan_1_edit" class="form-label d-inline mr-2">Bulan</label>
                                        <input type="text" name="hari_1" id="hari_1_edit" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="hari_1_edit" class="form-label d-inline mr-2">Hari</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Sampai Umur</label>
                                    <div class="form-umur-wrapper d-flex align-items-center">
                                        <input type="text" name="tahun_2" id="tahun_2_edit" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="tahun_2_edit" class="form-label d-inline mr-2">Tahun</label>
                                        <input type="text" name="bulan_2" id="bulan_2_edit" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="bulan_2_edit" class="form-label d-inline mr-2">Bulan</label>
                                        <input type="text" name="hari_2" id="hari_2_edit" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="hari_2_edit" class="form-label d-inline mr-2">Hari</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="min_edit">Min <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" id="min_edit" name="min"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="max_edit">Max <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" id="max_edit" name="max"
                                        placeholder="0">
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="d-block">Nilai Normal</label>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input" id="negatif_edit"
                                                    name="nilai_normal" value="Negatif">
                                                <label class="custom-control-label" for="negatif_edit">Negatif</label>
                                            </div>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input" id="positif_edit"
                                                    name="nilai_normal" value="Positif">
                                                <label class="custom-control-label" for="positif_edit">Positif</label>
                                            </div>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input" id="reaktif_edit"
                                                    name="nilai_normal" value="Reaktif">
                                                <label class="custom-control-label" for="reaktif_edit">Reaktif</label>
                                            </div>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input"
                                                    id="non_reaktif_edit" name="nilai_normal" value="Non Reaktif">
                                                <label class="custom-control-label" for="non_reaktif_edit">Non
                                                    Reaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <div class="form-group">
                                            <label for="hasil_edit">Hasil</label>
                                            <input type="text" class="form-control" id="hasil_edit"
                                                name="hasil">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="keterangan_edit">Keterangan</label>
                                    <textarea class="form-control" id="keterangan_edit" name="keterangan" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="min_kritis_edit">Kritis Jika Kurang Dari</label>
                                    <input type="text" class="form-control" id="min_kritis_edit"
                                        name="min_kritis" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="max_kritis_edit">Kritis Jika Lebih Dari</label>
                                    <input type="text" class="form-control" id="max_kritis_edit"
                                        name="max_kritis" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{-- Menggunakan ID yang konsisten: btn-update --}}
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-update"
                        class="btn mx-1 btn-primary text-white">
                        <div class="ikon-update">
                            <span class="fal fa-save mr-1"></span>Update
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
