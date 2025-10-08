@extends('inc.layout')
@section('title', 'List Penerimaan Barang (Non Pharmacy)')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Pencarian</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.penerimaan-barang.non-pharmacy') }}" method="get">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label" for="tanggal_terima">Tanggal Terima</label>
                                        <input type="date" value="{{ request('tanggal_terima') }}" class="form-control"
                                            id="tanggal_terima" name="tanggal_terima">
                                        @error('tanggal_terima')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="kode_po">Kode PO</label>
                                        <input type="text" value="{{ request('kode_po') }}" class="form-control"
                                            id="kode_po" name="kode_po">
                                        @error('kode_po')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="nama_barang">Nama Barang</label>
                                        <input type="text" value="{{ request('nama_barang') }}" class="form-control"
                                            id="nama_barang" name="nama_barang">
                                        @error('nama_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="batch_no">No Batch</label>
                                        <input type="text" value="{{ request('batch_no') }}" class="form-control"
                                            id="batch_no" name="batch_no">
                                        @error('batch_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label" for="kode_penerimaan">Kode Penerimaan</label>
                                        <input type="text" value="{{ request('kode_penerimaan') }}" class="form-control"
                                            id="kode_penerimaan" name="kode_penerimaan">
                                        @error('kode_penerimaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="no_faktur">No Faktur</label>
                                        <input type="text" value="{{ request('no_faktur') }}" class="form-control"
                                            id="no_faktur" name="no_faktur">
                                        @error('no_faktur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                                        <button type="submit" class="btn btn-outline-primary me-2">
                                            <span class="fal fa-search me-1"></span>
                                            Cari
                                        </button>
                                        <button type="button" class="btn btn-primary" id="tambah-btn">
                                            <span class="fal fa-plus me-1"></span>
                                            Penerimaan Barang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .display-none {
                display: none;
            }

            .popover {
                max-width: 100%;
            }
        </style>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Penerimaan Barang (Non Pharmacy)</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>#</th>
                                            <th>Detail</th>
                                            <th>Tanggal Penerimaan</th>
                                            <th>Kode Penerimaan</th>
                                            <th>Supplier</th>
                                            <th>Kode PO</th>
                                            <th>No Faktur</th>
                                            <th>PPN</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pbs as $pb)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-placement="top" data-bs-toggle="popover"
                                                        data-bs-title="Detail Penerimaan Barang" data-bs-html="true"
                                                        data-bs-content-id="popover-content-{{ $pb->id }}">
                                                        <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                                    </button>
                                                    <div class="display-none" id="popover-content-{{ $pb->id }}">
                                                        @include(
                                                            'pages.simrs.warehouse.penerimaan-barang.partials.pb-detail',
                                                            ['pb' => $pb]
                                                        )
                                                    </div>
                                                </td>
                                                <td>{{ tgl($pb->tanggal_terima) }}</td>
                                                <td>{{ $pb->kode_penerimaan }}</td>
                                                <td>{{ $pb->supplier->nama }}</td>
                                                <td>{{ $pb->po?->kode_po }}</td>
                                                <td>{{ $pb->no_faktur }}</td>
                                                <td>{{ rp($pb->ppn_nominal) }}</td>
                                                <td>{{ rp($pb->total_final) }}</td>
                                                <td>{{ ucfirst($pb->status) }}</td>
                                                <td>
                                                    <a class="mdi mdi-printer pointer mdi-24px text-primary print-btn"
                                                        title="Print" data-id="{{ $pb->id }}"></a>
                                                    @if ($pb->status == 'draft')
                                                        <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                            title="Edit" data-id="{{ $pb->id }}"></a>
                                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                            title="Hapus" data-id="{{ $pb->id }}"></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Detail</th>
                                            <th>Tanggal Penerimaan</th>
                                            <th>Kode Penerimaan</th>
                                            <th>Supplier</th>
                                            <th>Kode PO</th>
                                            <th>No Faktur</th>
                                            <th>PPN</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        $(document).ready(function() {
            $('#loading-spinner').show();
            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    $('#loading-spinner').hide();
                },
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm me-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm me-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm me-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm me-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });
        });
    </script>

    <script>
        const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        list.map((el) => {
            let opts = {
                animation: true,
            }
            if (el.hasAttribute('data-bs-content-id')) {
                opts.content = document.getElementById(el.getAttribute('data-bs-content-id')).innerHTML;
                opts.html = true;
                opts.sanitize = false;
            }
            new bootstrap.Popover(el, opts);
        })
    </script>

    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/non-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
