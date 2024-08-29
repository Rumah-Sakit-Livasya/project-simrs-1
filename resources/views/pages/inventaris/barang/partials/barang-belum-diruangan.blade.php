@php
    use App\Models\Room;
@endphp

@extends('inc.layout')
@section('title', 'Barang')
@section('content')

    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Barang
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="form-container" style="display: none;" class="panel form-container">
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Barang
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="/barang" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @if (!auth()->user()->is_admin)
                                    <input type="hidden" name="instance_code" value="{{ $i->instance_code }}">
                                    <input type="hidden" name="room_id" value="{{ auth()->user()->room_id }}">
                                @endif
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="custom_name">Nama Barang <sup>(Opsional)</sup></label>
                                            <input type="text" value="{{ old('custom_name') }}"
                                                class="form-control @error('custom_name') is-invalid @enderror"
                                                id="custom_name" name="custom_name" placeholder="Nama Barang">
                                            @error('custom_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="merk">Merk <sup>(Opsional)</sup></label>
                                            <input type="text" value="{{ old('merk') }}"
                                                class="form-control @error('merk') is-invalid @enderror" id="merk"
                                                name="merk" placeholder="Merk">
                                            @error('merk')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="tambahBarang">
                                                Barang
                                            </label>
                                            <select
                                                class="form-control w-100 @error('template_barang_id') is-invalid @enderror"
                                                id="tambahBarang" name="template_barang_id">
                                                <optgroup label="TEMPLATE BARANG">
                                                    <option value="" selected disabled></option>
                                                    @foreach ($templates as $template)
                                                        <option value="{{ $template->id }}">
                                                            {{ strtoupper($template->name) }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            @error('template_barang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="kondisiBarang">
                                                Kondisi Barang
                                            </label>
                                            <select class="form-control w-100 @error('condition') is-invalid @enderror"
                                                id="kondisiBarang" name="condition">
                                                <optgroup label="KONDISI BARANG">
                                                    <option disabled selected></option>
                                                    <option value="Baik">BAIK</option>
                                                    <option value="Rusak">RUSAK</option>
                                                </optgroup>
                                            </select>
                                            @error('condition')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="tahunPengadaan">
                                                Tahun Pengadaan
                                            </label>
                                            <select class="form-control w-100 @error('bidding_year') is-invalid @enderror"
                                                id="tahunPengadaan" name="bidding_year">
                                                <optgroup label="Tahun Pengadaan">
                                                    <option value="2010">2010</option>
                                                    <option value="2011">2011</option>
                                                    <option value="2012">2012</option>
                                                    <option value="2013">2013</option>
                                                    <option value="2014">2014</option>
                                                    <option value="2015">2015</option>
                                                    <option value="2016">2016</option>
                                                    <option value="2017">2017</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2022">2022</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2025">2025</option>
                                                </optgroup>
                                            </select>
                                            @error('bidding_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah">Jumlah <sup>(Opsional)</sup></label>
                                            <input type="number" value="{{ old('jumlah', 1) }}"
                                                class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                                                name="jumlah" placeholder="Jumlah">
                                            @error('jumlah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="fal fa-plus-circle mr-1"></span>
                                        Tambah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Table <span class="fw-300"><i>Barang</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Barang</th>
                                        <th style="white-space: nowrap">Merk</th>
                                        <th style="white-space: nowrap">Kategori Barang</th>
                                        <th style="white-space: nowrap">Tahun Pengadaan</th>
                                        <th style="white-space: nowrap">Ruangan</th>
                                        <th class="no-export" style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangs as $barang)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            @if ($barang->custom_name === null)
                                                <td style="white-space: nowrap">
                                                    {{ strtoupper($barang->template_barang->name) }}
                                                </td>
                                            @else
                                                <td style="white-space: nowrap">{{ strtoupper($barang->custom_name) }}
                                                </td>
                                            @endif
                                            <td style="white-space: nowrap">
                                                {{ $barang->merk === null ? '*tidak diketahui' : strtoupper($barang->merk) }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ strtoupper($barang->template_barang->category->name) }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $barang->bidding_year }}</td>
                                            @if ($barang->room === null)
                                                <td style="white-space: nowrap">*Barang belum di Ruangan</td>
                                            @else
                                                @if ($barang->pinjam == true)
                                                    <td style="white-space: nowrap">Barang dipinjam ke ruang
                                                        {{ Room::where('id', $barang->ruang_pinjam)->first()->name }}
                                                    </td>
                                                @else
                                                    <td style="white-space: nowrap"><a
                                                            href="/rooms/{{ $barang->room->id }}"
                                                            class="">{{ strtoupper($barang->room->name) }}</a></td>
                                                @endif
                                            @endif
                                            <td style="white-space: nowrap" class="no-export">
                                                <button type="button"
                                                    class="badge mx-1 badge-primary p-2 border-0 text-white"
                                                    data-toggle="modal" data-target="#ubah-barang{{ $barang->id }}"
                                                    title="Ubah">
                                                    <span class="fal fa-pencil"></span>
                                                </button>
                                                <button type="button"
                                                    class="badge mx-1 badge-secondary p-2 border-0 text-white"
                                                    onclick="togglePindahkanForm{{ $barang->id }}()"
                                                    id="toggle-pindahkan-{{ $barang->id }}" title="Distribusikan">
                                                    <span class="fal fa-sign-in"></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="ubah-barang{{ $barang->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form autocomplete="off" novalidate
                                                        action="/barang/{{ $barang->id }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @method('put')
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Ubah Barang</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true"><i
                                                                        class="fal fa-times"></i></span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="custom_name">Nama Barang
                                                                    <sup>(Opsional)</sup></label>
                                                                <input type="text"
                                                                    value="{{ old('custom_name', $barang->custom_name) }}"
                                                                    class="form-control @error('custom_name') is-invalid @enderror"
                                                                    id="custom_name" name="custom_name"
                                                                    placeholder="Nama Barang">
                                                                @error('custom_name')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="merk">Merk
                                                                    <sup>(Opsional)</sup></label>
                                                                <input type="text"
                                                                    value="{{ old('merk', $barang->merk) }}"
                                                                    class="form-control @error('merk') is-invalid @enderror"
                                                                    id="merk" name="merk" placeholder="Merk">
                                                                @error('merk')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="template_barang_id">Barang</label>
                                                                <select
                                                                    class="form-control w-100 @error('template_barang_id') is-invalid @enderror"
                                                                    id="single-default" name="template_barang_id">
                                                                    <optgroup label="Kategori Barang">
                                                                        @foreach ($templates as $template)
                                                                            @if (old('template_barang_id') == $template->id || $barang->template_barang_id == $template->id)
                                                                                <option value="{{ $template->id }}"
                                                                                    selected>{{ $template->name }}</option>
                                                                            @else
                                                                                <option value="{{ $template->id }}">
                                                                                    {{ $template->name }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                        @error('template_barang_id')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </optgroup>
                                                                </select>
                                                                @error('template_barang_id')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="condition">Kondisi Barang</label>
                                                                <select
                                                                    class="form-control w-100 @error('condition') is-invalid @enderror"
                                                                    id="condition" name="condition">
                                                                    <optgroup label="Kondisi Barang">
                                                                        <option value="Baik"
                                                                            {{ $barang->condition === 'Baik' ? 'selected' : '' }}>
                                                                            Baik</option>
                                                                        <option value="Rusak"
                                                                            {{ $barang->condition === 'Rusak' ? 'selected' : '' }}>
                                                                            Rusak</option>
                                                                    </optgroup>
                                                                </select>
                                                                @error('condition')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="bidding_year">
                                                                    Tahun Pengadaan
                                                                </label>
                                                                <select
                                                                    class="form-control w-100 @error('bidding_year') is-invalid @enderror"
                                                                    id="bidding_year" name="bidding_year">
                                                                    <optgroup label="Tahun Pengadaan">
                                                                        <option value="2010"
                                                                            {{ $barang->bidding_year == '2010' ? 'selected' : '' }}>
                                                                            2010</option>
                                                                        <option value="2011"
                                                                            {{ $barang->bidding_year == '2011' ? 'selected' : '' }}>
                                                                            2011</option>
                                                                        <option value="2012"
                                                                            {{ $barang->bidding_year == '2012' ? 'selected' : '' }}>
                                                                            2012</option>
                                                                        <option value="2013"
                                                                            {{ $barang->bidding_year == '2013' ? 'selected' : '' }}>
                                                                            2013</option>
                                                                        <option value="2014"
                                                                            {{ $barang->bidding_year == '2014' ? 'selected' : '' }}>
                                                                            2014</option>
                                                                        <option value="2015"
                                                                            {{ $barang->bidding_year == '2015' ? 'selected' : '' }}>
                                                                            2015</option>
                                                                        <option value="2016"
                                                                            {{ $barang->bidding_year == '2016' ? 'selected' : '' }}>
                                                                            2016</option>
                                                                        <option value="2017"
                                                                            {{ $barang->bidding_year == '2017' ? 'selected' : '' }}>
                                                                            2017</option>
                                                                        <option value="2018"
                                                                            {{ $barang->bidding_year == '2018' ? 'selected' : '' }}>
                                                                            2018</option>
                                                                        <option value="2019"
                                                                            {{ $barang->bidding_year == '2019' ? 'selected' : '' }}>
                                                                            2019</option>
                                                                        <option value="2020"
                                                                            {{ $barang->bidding_year == '2020' ? 'selected' : '' }}>
                                                                            2020</option>
                                                                        <option value="2021"
                                                                            {{ $barang->bidding_year == '2021' ? 'selected' : '' }}>
                                                                            2021</option>
                                                                        <option value="2022"
                                                                            {{ $barang->bidding_year == '2022' ? 'selected' : '' }}>
                                                                            2022</option>
                                                                        <option value="2023"
                                                                            {{ $barang->bidding_year == '2023' ? 'selected' : '' }}>
                                                                            2023</option>
                                                                        <option value="2024"
                                                                            {{ $barang->bidding_year == '2024' ? 'selected' : '' }}>
                                                                            2024</option>
                                                                        <option value="2025"
                                                                            {{ $barang->bidding_year == '2025' ? 'selected' : '' }}>
                                                                            2025</option>
                                                                    </optgroup>
                                                                </select>
                                                                @error('bidding_year')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <span class="fal fa-pencil mr-1"></span>
                                                                Ubah
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="form-container-{{ $barang->id }}" style="display: none;"
                                            class="panel form-container">
                                            <div class="panel-hdr">
                                                <h2>
                                                    Pindahkan @if ($barang->custom_name === null)
                                                        {{ $barang->template_barang->name }}
                                                    @else
                                                        {{ $barang->custom_name }}
                                                    @endif
                                                </h2>
                                            </div>
                                            <div class="panel-container show">
                                                <div class="panel-content">
                                                    <form autocomplete="off" novalidate
                                                        action="/barang/{{ $barang->id }}/ruang" method="post"
                                                        enctype="multipart/form-data">
                                                        @method('put')
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="instance_code"
                                                                value="{{ $i->instance_code }}">
                                                            <input type="hidden" name="category_code"
                                                                value="{{ $barang->template_barang->category->category_code }}">
                                                            <input type="hidden" name="barang_id"
                                                                value="{{ $barang->id }}">
                                                            <input type="hidden" name="template_barang_id"
                                                                value="{{ $barang->template_barang->id }}">
                                                            <div class="form-group">
                                                                <label for="namaRuangPindah">Nama Ruang</label>
                                                                <select
                                                                    class="form-control w-100 @error('room_id') is-invalid @enderror"
                                                                    id="namaRuangPindah" name="room_id">
                                                                    <optgroup label="Ruangan">
                                                                        @foreach ($rooms as $room)
                                                                            @if (old('room_id') == $room->id || $room->room_id == $room->id)
                                                                                <option value="{{ $room->id }}"
                                                                                    selected>{{ $room->name }}</option>
                                                                            @else
                                                                                <option value="{{ $room->id }}">
                                                                                    {{ strtoupper($room->name) }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                        @error('room_id')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </optgroup>
                                                                </select>
                                                                @error('room_id')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                id="close-pindah-{{ $barang->id }}"
                                                                onclick="togglePindahkanForm{{ $barang->id }}()">Close</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <span class="fal fa-arrow-circle-right mr-1"></span>
                                                                Pindahkan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function {
                                                $('#pindahkanBarang{{ $barang->id }}').select2({
                                                    placeholder: 'Pilih Ruangan',
                                                });
                                            });

                                            function togglePindahkanForm{{ $barang->id }}() {
                                                var formContainerPindahkan = document.getElementById("form-container-{{ $barang->id }}");
                                                var togglePindahkanButton = document.getElementById("toggle-pindahkan-{{ $barang->id }}");
                                                var closeButton = document.getElementById('close-pindah-{{ $barang->id }}');

                                                if (formContainerPindahkan.style.display === 'none' || formContainerPindahkan.style.display === '') {
                                                    formContainerPindahkan.style.display = 'block';
                                                    formContainerPindahkan.style.maxHeight = formContainerPindahkan.scrollHeight + 'px';
                                                    togglePindahkanButton.innerHTML = '<span class="fal fa-times"></span>';
                                                } else if (formContainerPindahkan.style.display === 'block') {
                                                    formContainerPindahkan.style.maxHeight = '0';
                                                    setTimeout(function() {
                                                        formContainerPindahkan.style.display = 'none';
                                                    }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                                                    togglePindahkanButton.innerHTML = '<span class="fal fa-sign-in"></span>';
                                                } else {
                                                    formContainerPindahkan.style.maxHeight = '0';
                                                    setTimeout(function() {
                                                        formContainerPindahkan.style.display = 'none';
                                                    }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                                                    togglePindahkanButton.innerHTML = '<span class="fal fa-sign-in"></span>';
                                                }
                                            }
                                        </script>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Barang</th>
                                        <th style="white-space: nowrap">Merk</th>
                                        <th style="white-space: nowrap">Kategori Barang</th>
                                        <th style="white-space: nowrap">Tahun Pengadaan</th>
                                        <th style="white-space: nowrap">Ruangan</th>
                                        <th class="no-export" style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="default-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form autocomplete="off" novalidate action="/barang" method="post" enctype="multipart/form-data">
                    @csrf
                    @if (!auth()->user()->is_admin)
                        <input type="hidden" name="instance_code" value="{{ $i->instance_code }}">
                        <input type="hidden" name="room_id" value="{{ auth()->user()->room_id }}">
                    @endif

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="custom_name">Nama Barang <sup>(Opsional)</sup></label>
                            <input type="text" value="{{ old('custom_name') }}"
                                class="form-control @error('custom_name') is-invalid @enderror" id="custom_name"
                                name="custom_name" placeholder="Nama Barang">
                            @error('custom_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="merk">Merk <sup>(Opsional)</sup></label>
                            <input type="text" value="{{ old('merk') }}"
                                class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk"
                                placeholder="Merk">
                            @error('merk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="single-default">
                                Barang
                            </label>
                            <select class="form-control w-100 @error('template_barang_id') is-invalid @enderror"
                                id="single-default" name="template_barang_id">
                                <optgroup label="TEMPLATE BARANG">
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}">{{ strtoupper($template->name) }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('template_barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="single-default">
                                Kondisi Barang
                            </label>
                            <select class="form-control w-100 @error('condition') is-invalid @enderror"
                                id="single-default" name="condition">
                                <optgroup label="KONDISI BARANG">
                                    <option value="Baik">BAIK</option>
                                    <option value="Rusak">RUSAK</option>
                                </optgroup>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="single-default">
                                Tahun Pengadaan
                            </label>
                            <select class="form-control w-100 @error('bidding_year') is-invalid @enderror"
                                id="single-default" name="bidding_year">
                                <optgroup label="Tahun Pengadaan">
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </optgroup>
                            </select>
                            @error('bidding_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $('#tambahBarang').select2({
                placeholder: 'Pilih Barang',
            });
            $('#kondisiBarang').select2({
                placeholder: 'Pilih Kondisi Barang',
            });
            $('#tahunPengadaan').select2({
                placeholder: 'Pilih Tahun Pengadaan',
            });
            $('#namaRuangPindah').select2({
                placeholder: 'Pilih Ruangan',
            });

            $('#dt-basic-example').dataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'float-right btn btn-primary',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Download as Excel',
                        className: 'float-right btn btn-success',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Column Visibility',
                        titleAttr: 'Col visibility',
                        className: 'float-right mb-3 btn btn-warning',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        },
                        postfixButtons: [{
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Download as Excel',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            }
                        ]
                    }
                ]
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });

        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.image-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function previewImage2() {
            const image = document.querySelector('#foto2');
            const imgPreview = document.querySelector('.image-preview2')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function toggleForm() {
            var formContainer = document.getElementById('form-container');
            var toggleButton = document.getElementById('toggle-form-btn');
            var closeButton = document.getElementById('close-form-btn');

            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
                formContainer.style.maxHeight = formContainer.scrollHeight + 'px';
                toggleButton.innerText = 'Tutup';
            } else if (formContainer.style.display === 'block') {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah Barang';
            } else {
                formContainer.style.maxHeight = '0';
                setTimeout(function() {
                    formContainer.style.display = 'none';
                }, 500); // Sesuaikan dengan durasi transisi (0.5 detik)
                toggleButton.innerText = 'Tambah Barang';
            }
        }
    </script>
@endsection
