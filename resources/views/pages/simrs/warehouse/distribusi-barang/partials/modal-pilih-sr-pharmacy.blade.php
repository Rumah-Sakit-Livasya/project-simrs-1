<div class="modal fade" id="modal-pilih-sr" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Stock Request (SR)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Tambahkan filter jika perlu --}}
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Tanggal SR</th>
                            <th>Kode SR</th>
                            <th>Gudang Asal</th>
                            <th>Gudang Tujuan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($srs as $sr)
                            {{-- Simpan data SR di atribut data-* untuk diakses oleh JS --}}
                            <tr class="pointer sr-row" data-sr='{{ json_encode($sr) }}'
                                title="Pilih {{ $sr->kode_sr }}">
                                <td>{{ tgl($sr->tanggal_sr) }}</td>
                                <td>{{ $sr->kode_sr }}</td>
                                <td>{{ $sr->asal->nama }}</td>
                                <td>{{ $sr->tujuan->nama }}</td>
                                <td>{{ $sr->user->employee->fullname }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada Stock Request yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
