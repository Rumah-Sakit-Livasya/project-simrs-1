<div class="row">
    <div class="col-12">
        <div id="panel-3" class="panel">
            <div class="panel-hdr">
                <h2>Data <span class="fw-300"><i>Tagihan</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="table-responsive">
                        <table id="dt-invoice-table"
                            class="table table-bordered table-striped table-hover table-sm w-100 text-center">
                            <thead class="bg-primary-600 align-middle">
                                <tr>
                                    <th>No</th>
                                    <th>No. RM / Reg.</th>
                                    <th>Nama Pasien</th>
                                    <th>No. Inv.</th>
                                    <th>Tgl Tagihan</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Tagihan</th>
                                    <th>Pelunasan</th>
                                    <th>&le;0</th>
                                    <th>0–15</th>
                                    <th>16–30</th>
                                    <th>31–60</th>
                                    <th>&gt;60</th>
                                    <th><input type="checkbox" id="check-all"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($query as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->registration->patient->medical_record_number ?? '-' }} /
                                            {{ $item->registration->registration_number ?? '-' }}</td>
                                        <td>{{ $item->registration->patient->name ?? '-' }}</td>
                                        <td>{{ $item->invoice ?? '-' }}</td>
                                        <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td>{{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td>{{ $item->days_overdue <= 0 ? abs($item->days_overdue) : 0 }}</td>
                                        <td>{{ $item->days_overdue > -15 && $item->days_overdue <= 0 ? abs($item->days_overdue) : 0 }}
                                        </td>
                                        <td>{{ $item->days_overdue > -30 && $item->days_overdue <= -15 ? abs($item->days_overdue) : 0 }}
                                        </td>
                                        <td>{{ $item->days_overdue > -60 && $item->days_overdue <= -30 ? abs($item->days_overdue) : 0 }}
                                        </td>
                                        <td>{{ $item->days_overdue <= -60 ? abs($item->days_overdue) : 0 }}</td>
                                        <td>
                                            <input type="checkbox" class="row-check" name="selected_invoices[]"
                                                value="{{ $item->id }}">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center">Tidak ada data tagihan tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
