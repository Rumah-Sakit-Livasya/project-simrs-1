<div class="modal fade" id="pilihSRModal" tabindex="-1" aria-labelledby="pilihSRModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih Stock Request</h1>
            </div>
            <div class="modal-body">
                <input type="text" id="searchSRInput" placeholder="Cari Kode Stock Request..." class="form-control">
                <br>
                <input type="text" id="searchSRAsalInput" placeholder="Cari Gudang Asal..." class="form-control">
                <br>
                <input type="text" id="searchSRTujuanInput" placeholder="Cari Gudang Tujuan..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Tanggal SR</th>
                            <th>Kode SR</th>
                            <th>Gudang Asal</th>
                            <th>Gudang Tujuan</th>
                            <th>User</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($srs as $sr)
                            <tr class="pointer sr-row" onclick="PopupDBNPharmacyClass.SelectSR({{ json_encode($sr) }})"
                                data-bs-dismiss="modal" title="Pilih {{ $sr->kode_sr }}">
                                <td>{{ tgl($sr->tanggal_sr) }}</td>
                                <td class="kode-sr">{{ $sr->kode_sr }}</td>
                                <td class="gudang-asal-sr">{{ $sr->asal->nama }}</td>
                                <td class="gudang-tujuan-sr">{{ $sr->tujuan->nama }}</td>
                                <td class="user-sr">{{ $sr->user->employee->fullname }}</td>
                                <td>{{ $sr->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
