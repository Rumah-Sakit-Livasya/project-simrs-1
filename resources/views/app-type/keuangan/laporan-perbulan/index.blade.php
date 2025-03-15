{{-- @dd($tahun) --}}
@php
    use Carbon\Carbon;
@endphp
@extends('inc.layout')
@section('title', 'Laporan')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Filter Laporan Per - Bulan
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="content-1">
                                <form action="" method="post">
                                    @method('post')
                                    @csrf
                                    <div class="row align-items-center">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="datepicker-modal-1">Tanggal Awal</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text fs-xl"><i
                                                                class="fal fa-calendar"></i></span>
                                                    </div>
                                                    <input type="text" id="datepicker-modal-1" class="form-control"
                                                        placeholder="Tanggal Awal" aria-label="date"
                                                        aria-describedby="datepicker-modal-1" name="tanggal_awal"
                                                        value="{{ old('tanggal_awal') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="datepicker-modal-2">Tanggal Akhir</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text fs-xl"><i
                                                                class="fal fa-calendar"></i></span>
                                                    </div>
                                                    <input type="text" id="datepicker-modal-2" class="form-control"
                                                        placeholder="Tanggal Akhir" aria-label="date"
                                                        aria-describedby="datepicker-modal-2" name="tanggal_akhir"
                                                        value="{{ old('tanggal_akhir') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="tahunLaporan">
                                                    Tahun
                                                </label>
                                                <select class="form-control w-100 @error('tahun') is-invalid @enderror"
                                                    id="tahunLaporan" name="tahun">
                                                    <optgroup label="Pilih Tahun Laporan">
                                                        <option selected></option>
                                                        @php
                                                            $currentYear = Carbon::now()->year;
                                                            $startYear = $tahunInput;
                                                        @endphp

                                                        @for ($year = $currentYear; $year >= $startYear; $year--)
                                                            <option value="{{ $year }}">{{ $year }}</option>
                                                        @endfor
                                                    </optgroup>
                                                </select>
                                                @error('tahun')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="bulanLaporan">
                                                    Bulan
                                                </label>
                                                <select class="form-control w-100 @error('bulan') is-invalid @enderror"
                                                    id="bulanLaporan" name="bulan">
                                                    <optgroup label="Pilih Bulan Laporan">
                                                        <option selected></option>
                                                        <option value="01">Januari</option>
                                                        <option value="02">Februari</option>
                                                        <option value="03">Maret</option>
                                                        <option value="04">April</option>
                                                        <option value="05">Mei</option>
                                                        <option value="06">Juni</option>
                                                        <option value="07">Juli</option>
                                                        <option value="08">Agustus</option>
                                                        <option value="09">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fal fa-eye mr-1"></span>
                                            Tampilkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="printableArea">
                    <h4 class="ml-3 mr-5 mt-3 text-black text-right">
                        Laporan Pemasukan & Pengeluaran
                    </h4>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if ($tampil)
                                @include('app-type.keuangan.laporan-perbulan.partials.export')
                            @else
                                <div class="alert alert-info text-center">
                                    Silahkan Filter Laporan Terlebih Dulu.
                                </div>
                            @endif
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
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>


    <script>
        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }


        function exportToPDF(divId, fileName, button) {
            // Hide the modal footer
            var modalFooter = document.getElementById('modalFooter');
            modalFooter.style.display = 'none';

            // Disable the button to prevent multiple clicks during export
            button.disabled = true;

            var element = document.getElementById(divId);

            html2pdf(element, {
                margin: 10,
                filename: fileName,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            }).then(function() {
                // Re-enable the button after export is complete
                button.disabled = false;

                // Show the modal footer again
                modalFooter.style.display = 'block';
            });
        }

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
                format: "dd-mm-yyyy"
            });
            $('#datepicker-modal-2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "dd-mm-yyyy"
            });
        }

        $(document).ready(function() {
            runDatePicker();

            $('#bulanLaporan').select2({
                placeholder: 'Pilih Kategori Laporan',
            });
            $('#tahunLaporan').select2({
                placeholder: 'Pilih Tipe Laporan',
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
