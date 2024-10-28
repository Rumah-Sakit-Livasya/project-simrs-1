<div class="modal fade" id="downloadTemplateSalaryModal" tabindex="-1" role="dialog"
    aria-labelledby="downloadTemplateSalaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadTemplateSalaryModalLabel">Unduh Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Pilih bulan dan tahun untuk mengunduh template:</p>
                <form id="downloadTemplateForm" action="{{ route('salary.export') }}" method="post">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <div class="form-row">
                        <div class="form-group col">
                            <label class="form-label" for="organization-option">
                                Unit / Organisasi
                            </label>
                            <select class="select2 form-control w-100" id="organization-option" name="organization_id">
                                <option selected value=""></option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->id }} -
                                        {{ $organization->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label class="form-label" for="employee-option">
                                Karyawan
                            </label>
                            <select class="select2 form-control w-100" id="employee-option" name="employee_id">
                                <option selected value=""></option>
                            </select>
                        </div>
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

<div class="modal fade" id="downloadTemplateDeductionModal" tabindex="-1" role="dialog"
    aria-labelledby="downloadTemplateDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadTemplateDeductionModalLabel">Unduh Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Pilih bulan dan tahun untuk mengunduh template potongan:</p>
                <form id="downloadTemplateDeductionForm" action="{{ route('deduction.export') }}" method="post">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <div class="form-row">
                        <div class="form-group col">
                            <label class="form-label" for="organization-option">
                                Unit / Organisasi
                            </label>
                            <select class="select2 form-control w-100" id="organization-option-deduction"
                                name="organization_id">
                                <option selected value=""></option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->id }} -
                                        {{ $organization->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label class="form-label" for="employee-option">
                                Karyawan
                            </label>
                            <select class="select2 form-control w-100" id="employee-option-deduction"
                                name="employee_id">
                                <option selected value=""></option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" form="downloadTemplateDeductionForm" class="btn btn-success"><i
                        class="fas fa-download mr-2"></i>Unduh</button>
            </div>
        </div>
    </div>
</div>
