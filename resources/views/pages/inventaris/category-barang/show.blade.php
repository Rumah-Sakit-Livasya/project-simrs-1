@php
    use App\Models\Inventaris\Room;
    use App\Models\Inventaris\Barang;
@endphp

@extends('inc.layout')
@section('title', 'Barang')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-6">
                <a href="{{ route('inventaris.category.index') }}" class="btn btn-primary waves-effect waves-themed">
                    <span class="fal fa-arrow-left mr-1"></span>
                    Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Total Barang {{ $category->name }} : {{ $jumlah }} <span class="fw-300"></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        {{-- <th style="white-space: nowrap">Foto</th> --}}
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Kategori</th>
                                        <th style="white-space: nowrap">Kode Barang</th>
                                        <th style="white-space: nowrap">Jumlah Barang</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $barang)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap"><a
                                                    href="{{ route('inventaris.template.show', $barang->id) }}">{{ strtoupper($barang->name) }}</a>
                                            </td>
                                            <td style="white-space: nowrap">{{ $barang->category->name }}</td>
                                            <td style="white-space: nowrap">{{ $barang->barang_code }}</td>
                                            <td style="white-space: nowrap">
                                                {{ count(Barang::where('template_barang_id', $barang->id)->get()) }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-primary p-2 border-0 text-white"
                                                    data-toggle="modal" data-target="#ubah-barang{{ $barang->id }}"
                                                    title="Ubah">
                                                    <span class="fal fa-pencil mr-1"></span>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Ubah Template Barang --}}
                                        {{-- @include('pages.template-barang.updateForm') --}}
                                        {{-- ./ Ubah Template Barang --}}
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        {{-- <th style="white-space: nowrap">Foto</th> --}}
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Barang</th>
                                        <th style="white-space: nowrap">Kategori</th>
                                        <th style="white-space: nowrap">Kode Barang</th>
                                        <th style="white-space: nowrap">Jumlah Barang</th>
                                        <th style="white-space: nowrap">Aksi</th>
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
    <!-- Modal Large -->
    <div class="modal fade" id="default-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form autocomplete="off" novalidate action="/barang" method="post" enctype="multipart/form-data">
                    @csrf
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
                            <label class="form-label" for="single-default">
                                Kondisi Barang
                            </label>
                            <select class="form-control w-100 @error('condition') is-invalid @enderror" id="single-default"
                                name="condition">
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

            @foreach ($items as $temp)
                $('#updateKategoriBarang{{ $temp->id }}').select2({
                    placeholder: 'Pilih Barang',
                    dropdownParent: $('#ubah-barang{{ $temp->id }}'),
                });
            @endforeach

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
    </script>
@endsection
