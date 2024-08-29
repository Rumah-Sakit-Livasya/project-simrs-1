@php
    use App\Models\Inventaris\RoomMaintenance;
@endphp

<table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
    <thead>
        <tr>
            <th style="white-space: nowrap">No</th>
            <th style="white-space: nowrap">Nama Barang</th>
            {{-- <th style="white-space: nowrap">Merk</th>
            <th style="white-space: nowrap">Kategori Barang</th>
            <th style="white-space: nowrap">Urutan Barang</th> --}}
            <th style="white-space: nowrap">Kode Barang</th>
            <th style="white-space: nowrap">Ruangan</th>
            <th style="white-space: nowrap">Tanggal Input</th>
            <th style="white-space: nowrap" class="no-export">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barang as $row)
            <tr>
                <td style="white-space: normal">{{ $loop->iteration }}</td>
                @if ($row->custom_name === null)
                    <td style="white-space: normal">
                        <a
                            href="{{ route('inventaris.maintenance.index', $row->id) }}">{{ strtoupper($row->template_barang->name) }}</a>
                    </td>
                @else
                    <td style="white-space: normal"><a
                            href="{{ route('inventaris.maintenance.index', $row->id) }}">{{ strtoupper($row->custom_name) }}</a>
                    </td>
                @endif
                {{-- <td style="white-space: normal">
                    {{ $row->merk === null ? '*tidak diketahui' : strtoupper($row->merk) }}
                </td>
                <td style="white-space: normal">
                    {{ strtoupper($row->template_barang->category->name) }}
                </td>
                <td style="white-space: normal">{{ $row->urutan_barang }}</td> --}}
                <td style="white-space: normal">
                    {{ strtoupper($row->item_code . ' ' . $row->merk) }}
                </td>
                @if ($row->room_id === 0)
                    <td style="white-space: normal">*Barang belum di Ruangan</td>
                @else
                    @if ($row->pinjam == true)
                        <td style="white-space: normal">Barang dipinjam ke ruang
                            {{ RoomMaintenance::where('id', $row->ruang_pinjam)->first()->name }}
                        </td>
                    @else
                        <td style="white-space: normal"><a href="{{ route('inventaris.rooms.show', $row->room->id) }}"
                                class="">{{ strtoupper($row->room->name) }}</a>
                        </td>
                    @endif
                @endif
                <td style="white-space: normal">{{ $row->created_at }}</td>
                <td style="white-space: nowrap" class="no-export">
                    <button class="badge mx-1 badge-primary p-2 border-0 text-white btn-edit"
                        data-id="{{ $row->id }}">
                        <i class="fal fa-pencil"></i>
                    </button>

                    <button class="badge mx-1 badge-secondary p-2 border-0 text-white btn-move"
                        data-id="{{ $row->id }}">
                        <i class="fal fa-sign-in"></i>
                    </button>

                    @if ($row->pinjam == false && $row->room != null)
                        <button class="badge mx-1 badge-success p-2 border-0 text-white btn-pinjam"
                            data-id="{{ $row->id }}">
                            <i class="fal fa-arrow-circle-right"></i>
                        </button>
                    @endif

                    @if ($row->pinjam == true && $row->room != null)
                        <button class="badge mx-1 badge-success p-2 border-0 text-white btn-back"
                            data-id="{{ $row->id }}">
                            <i class="fal fa-arrow-circle-left"></i>
                        </button>
                    @endif
                </td>
            </tr>

            {{-- FORM --}}
            {{-- @include('pages.barang.formUpdateBarang')
            @include('pages.barang.formPindahkanBarang')
            @include('pages.barang.formPinjamBarang')
            @include('pages.barang.formKembalikanBarang') --}}
            {{-- ./ FORM --}}
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            {{-- <th>Merk</th>
            <th>Kategori Barang</th>
            <th>Urutan Barang</th> --}}
            <th>Kode Barang</th>
            <th>Ruangan</th>
            <th>Tanggal Input</th>
            <th class="no-export" style="white-space: nowrap">Aksi</th>
        </tr>
    </tfoot>
</table>
