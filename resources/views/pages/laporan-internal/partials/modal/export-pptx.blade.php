<!-- Export PPTX Modal -->
<div class="modal fade" id="exportPPTXModal" tabindex="-1" role="dialog" aria-labelledby="exportPPTXModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportPPTXModalLabel">Download Laporan Bulanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="exportPPTXForm" action="{{ route('laporan.internal.export.pptx') }}" method="GET">
                <input type="hidden" name="organization_id" value="{{ auth()->user()->employee->organization_id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exportTahun">Tahun</label>
                        <select class="form-control" id="exportTahun" name="tahun" required>
                            <option value="">Pilih Tahun</option>
                            @foreach (range(date('Y') - 5, date('Y') + 5) as $year)
                                <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exportBulan">Bulan</label>
                        <select class="form-control" id="exportBulan" name="bulan" required>
                            <option value="">Pilih Bulan</option>
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}" {{ date('n') == $month ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-download mr-2"></i>Download PPTX
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
