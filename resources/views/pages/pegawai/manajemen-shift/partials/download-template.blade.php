<div class="modal fade" id="downloadTemplateModal" tabindex="-1" role="dialog" aria-labelledby="downloadTemplateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadTemplateModalLabel">Unduh Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Pilih bulan dan tahun untuk mengunduh template:</p>
                <form id="downloadTemplateForm"
                    action="{{ route('shift.export', auth()->user()->employee->organization_id) }}" method="GET">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="month">Bulan</label>
                            <select class="form-control" id="month" name="month">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="year">Tahun</label>
                            <select class="form-control" id="year" name="year">
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                            </select>
                        </div>
                        @if (auth()->user()->hasRole('super admin'))
                            <div class="form-group col">
                                <label class="form-label" for="organization">
                                    Unit / Organisasi
                                </label>
                                <select class="select2 form-control w-100" id="organization" name="organization_id">
                                    <option value=""></option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" form="downloadTemplateForm" class="btn btn-success"><i
                        class="fas fa-download mr-2"></i>Unduh</button>
            </div>
        </div>
    </div>
</div>
