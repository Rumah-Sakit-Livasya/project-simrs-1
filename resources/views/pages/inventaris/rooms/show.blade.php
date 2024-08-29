@php
    use App\Models\Inventaris\Barang;
    use App\Models\Inventaris\Room;
@endphp

@extends('inc.layout')
@section('title', "$title")
@section('content')
    <style>
        .form-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-6">
                <a href="{{ route('inventaris.rooms.index') }}" class="btn btn-primary waves-effect waves-themed">
                    <span class="fal fa-arrow-left mr-1"></span>
                    Kembali
                </a>
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    Tambah Barang
                </button>

                <form action="/rooms/print" method="POST" class="d-inline">
                    <input type="hidden" name="room_id" value="{{ $ruang->id }}">
                    @method('post')
                    @csrf
                    <button class="btn btn-primary waves-effect waves-themed">
                        <i class="fas fa-print"></i> Print Label
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="form-container" style="display: none;" class="panel form-container"> {{-- Form Tambah Barang --}}
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Barang
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="/rooms/barang" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                {{-- <input type="hidden" name="instance_code" value="{{ $i->instance_code }}"> --}}
                                <input type="hidden" name="room_id" value="{{ $ruang->id }}">

                                <div class="modal-body">
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
                                                    <optgroup label="Kategori Barang">
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
                                                    <optgroup label="Kondisi Barang">
                                                        <option value="Baik">Baik</option>
                                                        <option value="Rusak">Rusak</option>
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
                                                <select
                                                    class="form-control w-100 @error('bidding_year') is-invalid @enderror"
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
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="close-form-btn">Close</button>
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
                            Total Barang {{ strtoupper($ruang->name) }} : {{ $jumlah }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @include('pages.inventaris.barang.partials.barang-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>

    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $(function() {
                $('#tambahBarang').select2({
                    placeholder: 'Pilih Barang',
                });
                $('#kondisiBarang').select2({
                    placeholder: 'Pilih Kondisi Barang',
                });
                $('#tahunPengadaan').select2({
                    placeholder: 'Pilih Tahun Pengadaan',
                });
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
