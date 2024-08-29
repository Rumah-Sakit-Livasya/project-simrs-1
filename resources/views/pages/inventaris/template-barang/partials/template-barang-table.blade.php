@php
    use App\Models\Inventaris\Barang;
@endphp

<table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
    <thead>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama Barang</th>
            <th style="white-space: nowrap">Jumlah Barang</th>
            <th style="white-space: nowrap">Kategori</th>
            <th style="white-space: nowrap">Kode Barang</th>
            <th style="white-space: nowrap">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($templateBarang as $row)
            <tr>
                <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                <td style="white-space: nowrap">
                    <a href="{{ route('inventaris.template.show', $row->id) }}">{{ strtoupper($row->name) }}</a>
                </td>
                <td style="white-space: nowrap">
                    {{ count(Barang::where('template_barang_id', $row->id)->get()) }}
                </td>
                <td style="white-space: nowrap">{{ strtoupper($row->category->name) }}</td>
                <td style="white-space: nowrap">{{ strtoupper($row->barang_code) }}</td>
                <td style="white-space: nowrap">
                    <button class="btn btn-sm btn-success px-2 py-1 btn-edit" data-id="{{ $row->id }}">
                        <i class="fas fa-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger px-2 py-1 btn-delete" data-id="{{ $row->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama Barang</th>
            <th style="white-space: nowrap">Jumlah Barang</th>
            <th style="white-space: nowrap">Kategori</th>
            <th style="white-space: nowrap">Kode Barang</th>
            <th style="white-space: nowrap">Aksi</th>
        </tr>
    </tfoot>
</table>
