<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="text-center bg-light">
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Obat</th>
                <th>Dosis</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 5; $i++)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td><input type="text" name="{{ $prefix }}[{{ $i }}][nama]" class="form-control"
                            value="{{ $data[$i]['nama'] ?? '' }}"></td>
                    <td><input type="text" name="{{ $prefix }}[{{ $i }}][dosis]" class="form-control"
                            value="{{ $data[$i]['dosis'] ?? '' }}"></td>
                    <td><input type="text" name="{{ $prefix }}[{{ $i }}][jumlah]"
                            class="form-control" value="{{ $data[$i]['jumlah'] ?? '' }}"></td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>
