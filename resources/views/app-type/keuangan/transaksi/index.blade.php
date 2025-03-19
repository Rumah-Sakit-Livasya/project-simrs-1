{{-- @dd($transaksi->first()->category_id =) --}}
@php
    use Carbon\Carbon;
@endphp

@extends('inc.layout')
@section('title', 'Transaksi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-transaksi" title="Tambah">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Transaksi
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Table <span class="fw-300"><i>Transaksi</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Kategori</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Pemasukan</th>
                                        <th style="white-space: nowrap">Pengeluaran</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi as $t)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td style="white-space: nowrap">{{ $t->category->nama }}</td>
                                            <td style="white-space: nowrap">{{ $t->keterangan }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $t->type_id === 1 ? 'Rp. ' . number_format($t->nominal) . ',-' : '' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ $t->type_id === 2 ? 'Rp. ' . number_format($t->nominal) . ',-' : '' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <button type="button"
                                                    class="badge mx-1 badge-primary p-2 border-0 text-white"
                                                    data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                    data-target="#lihat-transaksi{{ $t->id }}"
                                                    title="Lihat Transaksi">
                                                    <span class="fal fa-eye"></span>
                                                </button>
                                                <button type="button"
                                                    class="badge mx-1 badge-warning p-2 border-0 text-white"
                                                    data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                    data-target="#ubah-transaksi{{ $t->id }}" title="Ubah Transaksi">
                                                    <span class="fal fa-pencil"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        @include('app-type.keuangan.transaksi.partials.update-transaksi')
                                        @include('app-type.keuangan.transaksi.partials.view-transaksi')
                                    @endforeach
                                    @include('app-type.keuangan.transaksi.partials.create-transaksi')
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Kategori</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Pemasukan</th>
                                        <th style="white-space: nowrap">Pengeluaran</th>
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
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        var runDatePicker = function() {

            // input group layout for modal demo
            $('#datepicker-modal-1').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "yyyy-mm-dd"
            });
            $('#datepicker-modal-2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "yyyy-mm-dd"
            });
        }

        $(document).ready(function() {
            runDatePicker();

            // Fungsi untuk mengatur kategori berdasarkan jenis yang dipilih
            function setKategoriOptions() {
                var jenisTransaksi = $('#jenisTransaksi').val();
                var kategoriTransaksiSelect = $('#kategoriTransaksi');

                // Mengosongkan dan menonaktifkan pilihan kategori
                kategoriTransaksiSelect.empty().prop('disabled', true);

                // Ambil data kategori berdasarkan jenis dari server (AJAX)
                if (jenisTransaksi) {
                    $.ajax({
                        url: '/api/get-kategori/' + jenisTransaksi,
                        method: 'GET',
                        success: function(data) {
                            // Isi pilihan kategori dengan data yang diterima
                            $.each(data, function(index, category) {
                                kategoriTransaksiSelect.append('<option value="' + category.id +
                                    '">' + category.nama + '</option>');
                            });

                            // Aktifkan kembali pilihan kategori
                            kategoriTransaksiSelect.prop('disabled', false);
                        },
                        error: function() {
                            console.error('Error fetching kategori data.');
                        }
                    });
                }
            }

            // Panggil fungsi setKategoriOptions saat nilai jenisTransaksi berubah
            $('#jenisTransaksi').change(function() {
                setKategoriOptions();
            });

            // Panggil fungsi setKategoriOptions saat halaman dimuat (untuk menangani nilai yang sudah ada)
            setKategoriOptions();

            // Panggil fungsi setKategoriOptions saat nilai type_id berubah
            $('#type_id').change(function() {
                setKategoriOptions();
            });

            $('#jenisTransaksi').select2({
                placeholder: 'Pilih Jenis Transaksi',
                dropdownParent: $('#tambah-transaksi'),
            });
            $('#rekeningBank').select2({
                placeholder: 'Pilih Jenis Transaksi',
                dropdownParent: $('#tambah-transaksi'),
            });
            $('#kategoriTransaksi').select2({
                placeholder: 'Pilih Jenis Transaksi',
                dropdownParent: $('#tambah-transaksi'),
            });

            @foreach ($transaksi as $trans)
                $('#jenisTransaksiUpdate{{ $trans->id }}').select2({
                    placeholder: 'Pilih Jenis Transaksi',
                    dropdownParent: $('#ubah-transaksi{{ $trans->id }}'),
                });
                $('#rekeningBankUpdate{{ $trans->id }}').select2({
                    placeholder: 'Pilih Jenis Transaksi',
                    dropdownParent: $('#ubah-transaksi{{ $trans->id }}'),
                });
                $('#kategoriTransaksiUpdate{{ $trans->id }}').select2({
                    placeholder: 'Pilih Jenis Transaksi',
                    dropdownParent: $('#ubah-transaksi{{ $trans->id }}'),
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
    </script>
@endsection
