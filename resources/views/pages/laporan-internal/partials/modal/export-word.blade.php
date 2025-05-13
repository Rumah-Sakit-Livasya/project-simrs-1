<!-- Export Word Modal -->
<div class="modal fade" id="exportWordModal" tabindex="-1" role="dialog" aria-labelledby="exportWordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportWordModalLabel">Download Laporan Word Harian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="exportWordForm" action="{{ route('laporan.internal.export.word') }}" method="GET">
                <input type="hidden" name="organization_id" value="{{ auth()->user()->employee->organization_id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exportWordTanggal">Tanggal Laporan</label>
                        <input type="date" class="form-control" id="exportWordTanggal" name="tanggal" required
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="exportWordJenis">Jenis Laporan</label>
                        <select class="form-control" id="exportWordJenis" name="jenis">
                            <option value="">Semua Jenis</option>
                            <option value="kegiatan">Kegiatan</option>
                            <option value="kendala">Kendala</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pic">PIC (Person In Charge) <i class="fas fa-info-circle text-primary"
                                data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                data-toggle="tooltip" title="Orang yang bertanggungjawab atas target ini"></i></label>
                        <!-- Mengubah input menjadi select2 -->
                        <select class="select2 form-control @error('pic') is-invalid @enderror" name="pic[]"
                            id="pic" multiple>
                            @foreach ($employeeUnit as $employee)
                                <option value="{{ $employee->id }}">{{ old('pic', $employee->fullname) }}
                                </option>
                            @endforeach
                        </select>
                        @error('pic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-download mr-2"></i>Download Word
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
