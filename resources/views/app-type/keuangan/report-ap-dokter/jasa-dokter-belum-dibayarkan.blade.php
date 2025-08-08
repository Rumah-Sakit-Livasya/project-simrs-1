@extends('inc.layout')
@section('title', 'Jasa Dokter Belum Dibayarkan')
@section('content')
    <style>
        .form-control {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #eaeaea;
        }

        .select2-selection {
            border: 0 !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
        }

        table {
            font-size: 8pt !important;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>AP Dokter <span class="fw-300"><i>Belum Dibayarkan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="get">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Tanggal Bill</label>
                                        <input type="text" class="form-control datepicker" name="tanggal_awal"
                                            value="{{ request('tanggal_awal') ?? date('Y-m-01') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Sampai</label>
                                        <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                            value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Nama Dokter</label>
                                        <select class="form-control select2" name="dokter_id">
                                            <option value="">Pilih Dokter</option>
                                            @foreach ($dokters as $dokter)
                                                <option value="{{ $dokter->id }}"
                                                    {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                                    {{ $dokter->employee->fullname ?? 'dr. ' . $dokter->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info">
                                        <i class="fal fa-file-excel mr-1"></i> Export
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Data <span class="fw-300"><i>Jasa Dokter Belum Dibayarkan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-jasa-dokter" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No .RM/No. Reg</th>
                                        <th>Nama Pasien</th>
                                        <th>Detail Tagihan</th>
                                        <th>Penjamin</th>
                                        <th>JKP</th>
                                        <th>Jasa Dokter</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" name="select_item[]"></td>
                                        <td class="text-center">1</td>
                                        <td>22-05-2025</td>
                                        <td>RM001 / REG001</td>
                                        <td>Ahmad Setiawan</td>
                                        <td>Rawat Inap</td>
                                        <td>BPJS</td>
                                        <td class="text-center">11%</td>
                                        <td class="text-right">Rp 500.000</td>
                                        <td><span class="badge badge-warning">Belum Dibayar</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownCssClass: "move-up",
                placeholder: "Pilih Dokter",
                allowClear: true
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id'
            });

            $('#checkAll').change(function() {
                $('input[name="select_item[]"]').prop('checked', $(this).prop('checked'));
            });

            $('#dt-jasa-dokter').DataTable({
                dom: 't',
                paging: false,
                ordering: true,
                info: false,
                searching: false
            });
        });
    </script>
@endsection
@endsection
