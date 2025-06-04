@extends('inc.layout')
@section('title', 'Bank')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-bank" title="Tambah">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Bank
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Table <span class="fw-300"><i>Bank</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Bank</th>
                                        <th style="white-space: nowrap">Pemilik Rekening</th>
                                        <th style="white-space: nowrap">Nomor Rekening</th>
                                        <th style="white-space: nowrap">Saldo Awal</th>
                                        <th style="white-space: nowrap">Akun Kas/Bank</th>
                                        <th style="white-space: nowrap">Akun Kliring</th>
                                        <th style="white-space: nowrap">Status Aktivasi</th>
                                        <th style="white-space: nowrap">Jenis</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banks as $bank)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ $bank->nama }}</td>
                                            <td style="white-space: nowrap">{{ $bank->pemilik }}</td>
                                            <td style="white-space: nowrap">{{ $bank->nomor }}</td>
                                            <td style="white-space: nowrap">{{ rp($bank->saldo) }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $bank->akunKasBank->code ?? '' }} - {{ $bank->akunKasBank->name ?? '' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ $bank->akunKliring->code ?? '' }} - {{ $bank->akunKliring->name ?? '' }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                @if ($bank->is_aktivasi)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Non-Aktif</span>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap">
                                                @if ($bank->is_bank)
                                                    <span class="badge badge-primary">Bank</span>
                                                @else
                                                    <span class="badge badge-info">Non-Bank</span>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap">
                                                <button type="button"
                                                    class="badge mx-1 badge-primary p-2 border-0 text-white"
                                                    data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                    data-target="#ubah-bank{{ $bank->id }}" title="Ubah">
                                                    <span class="fal fa-pencil mr-1"></span> Ubah
                                                </button>
                                            </td>
                                        </tr>

                                        @include('app-type.keuangan.bank.partials.update-bank')
                                    @endforeach
                                    @include('app-type.keuangan.bank.partials.create-bank')
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Bank</th>
                                        <th style="white-space: nowrap">Pemilik Rekening</th>
                                        <th style="white-space: nowrap">Nomor Rekening</th>
                                        <th style="white-space: nowrap">Saldo Awal</th>
                                        <th style="white-space: nowrap">Akun Kas/Bank</th>
                                        <th style="white-space: nowrap">Akun Kliring</th>
                                        <th style="white-space: nowrap">Status Aktivasi</th>
                                        <th style="white-space: nowrap">Jenis</th>
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

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih Opsi',
                dropdownParent: $('#tambah-bank'), // Adjust if your modal has a different ID
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
    </script>
@endsection
