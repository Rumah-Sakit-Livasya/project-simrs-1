@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <style>
            #loading-page {
                position: absolute;
                min-height: 100%;
                min-width: 100%;
                background: rgba(0, 0, 0, 0.75);
                border-radius: 0 0 4px 4px;
                z-index: 1000;
            }

            input[type='checkbox'] {
                width: 1.5rem;
                height: 1.5rem;
                margin: 0.5rem;
            }

            .display-none {
                display: none;
            }

            .popover {
                max-width: 100%;
                max-height:
            }
        </style>

        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.poliklinik.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                
                @include('pages.simrs.pendaftaran.partials.radiologi')
                @yield('script-radiologi')

                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="p-3">
                            <div class="card-head collapsed d-flex justify-content-between">
                                <div class="title">
                                    <header class="text-primary text-center font-weight-bold mb-4">
                                        <h2 class="font-weight-bold">RESEP HARIAN</h4>
                                    </header>
                                </div> <!-- Tambahkan judul jika perlu -->
                            </div>

                            <div class="panel-content" aria-expanded="true">

                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>#</th>
                                            <th>Detail</th>
                                            <th>Tanggal</th>
                                            <th>No Resep</th>
                                            <th>Nama Dokter</th>
                                            <th>Apotek</th>
                                            <th>User Input</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reseps as $resep)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td> <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-placement="top" data-bs-toggle="popover"
                                                        data-bs-title="Detail Resep Harian" data-bs-html="true"
                                                        data-bs-content-id="popover-content-{{ $resep->id }}">
                                                        <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                                    </button>
                                                    <div class="display-none" id="popover-content-{{ $resep->id }}">
                                                        @include(
                                                            'pages.simrs.farmasi.resep-harian.partials.rh-detail',
                                                            ['resep' => $resep]
                                                        )
                                                    </div>
                                                </td>
                                                <td>{{ tgl_waktu($resep->created_at) }}</td>
                                                <td>{{ $resep->kode_resep }}</td>
                                                <td>{{ $resep->doctor->employee->fullname }}</td>
                                                <td>{{ $resep->gudang->nama }}</td>
                                                <td>{{ $resep->user->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Detail</th>
                                            <th>Tanggal</th>
                                            <th>No Resep</th>
                                            <th>Nama Dokter</th>
                                            <th>Apotek</th>
                                            <th>User Input</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-xl-3">
                                        <button type="button" class="btn btn-primary waves-effect waves-themed"
                                            id="tambah-btn" data-bs-toggle="collapse" data-bs-target="#resep-harian-form">
                                            <span class="fal fa-plus mr-1"></span>
                                            Tambah Resep
                                        </button>
                                    </div>
                                </div>

                                <br>

                                <div class="collapse" id="resep-harian-form">
                                    <div class="card card-body">
                                        <div class="loading" id="loading-page"></div>
                                        <h1>Tambah Resep Harian</h1>
                                        <form action="{{ route('farmasi.resep-harian.store') }}" method="post"
                                            id="rh-form">
                                            @method('post')
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                                            <input type="hidden" name="doctor_id" value="{{ $registration->doctor->id }}">

                                            <div class="col-md-12">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-primary text-white">
                                                        Resep Elektronik
                                                        &nbsp;
                                                        <i id="loading-spinner-head"
                                                            class="loading fas fa-spinner fa-spin"></i>
                                                        <span class="loading-message loading text-warning">Loading...</span>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row p-2">
                                                            @if (!isset($default_apotek))
                                                                <div class="col-6">
                                                                    <select
                                                                        class="select2 form-control @error('gudang_id') is-invalid @enderror"
                                                                        name="gudang_id" id="cppt_gudang_id">
                                                                        <option value="" disabled selected hidden>
                                                                            Pilih Gudang
                                                                        </option>
                                                                        @foreach ($gudangs as $gudang)
                                                                            <option value="{{ $gudang->id }}">
                                                                                {{ $gudang->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6">
                                                                @else
                                                                    <div class="col-12">
                                                                        <input type="hidden" name="gudang_id"
                                                                            value="{{ $default_apotek->id }}">
                                                            @endif
                                                            <select class="form-control" name="barang_id"
                                                                id="cppt_barang_id">
                                                                <option value="" disabled selected hidden>Pilih
                                                                    Obat
                                                                </option>
                                                                @if (isset($default_apotek))
                                                                    @foreach ($barangs as $barang)
                                                                        @php
                                                                            $items = $barang->stored_items->where(
                                                                                'gudang_id',
                                                                                $default_apotek->id,
                                                                            );
                                                                            $qty = $items->sum('qty');
                                                                            $barang->qty = $qty;
                                                                        @endphp
                                                                        @if ($qty > 0)
                                                                            <option value="{{ $barang->id }}"
                                                                                class="obat"
                                                                                data-zat="@foreach ($barang->zat_aktif as $zat_aktif) {{ $zat_aktif->zat->nama }}, @endforeach"
                                                                                data-qty="{{ $qty }}"
                                                                                data-item="{{ json_encode($barang) }}">
                                                                                {{ $barang->nama }} (Stock:
                                                                                {{ $qty }})</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <div class="row">
                                                                <div class="col-xl">
                                                                    <input type="checkbox" name="zat_aktif" id="zat_aktif">
                                                                    <label for="zat_aktif"> Zat Aktif </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-control-line"></div>

                                                            <br>

                                                            <table class="table table-striped">
                                                                <thead class="smooth card-header bg-secondary text-white">
                                                                    <tr>
                                                                        <th>Nama Obat</th>
                                                                        <th style="width: 10%;">UOM</th>
                                                                        <th style="width: 5%;">Stok</th>
                                                                        <th style="width: 10%;">Qty Perhari</th>
                                                                        <th style="width: 10%;">Jumlah Hari</th>
                                                                        <th style="width: 10%;">Total Qty</th>
                                                                        <th style="width: 15%">Signa</th>
                                                                        <th style="width: 1%;">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="table_re"></tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="col-md-12">
                                                <div class="card mt-3">
                                                    <div class="card-header bg-info text-white">
                                                        Resep Manual
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <textarea class="form-control border-0 rounded-0" id="resep_manual" name="resep_manual" rows="4"
                                                            placeholder="Resep Manual"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-end mt-3">
                                                <div class="col-xl-3">
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-themed" id="simpan-btn">
                                                        <span class="fal fa-save mr-1"></span>
                                                        Simpan Resep Harian
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/simrs/erm/form/ranap/resep-harian.js') }}?time={{ now() }}"></script>

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

    <script>
        /**
         * Custom matcher for the Select2 drug dropdown to allow searching
         * by drug name or active substance (zat aktif).
         * @param {import("select2").SearchOptions} params
         * @param {import("select2").OptGroupData | import("select2").OptionData} data
         * @returns {import("select2").OptGroupData | import("select2").OptionData | null}
         */
        function obatMatcher(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            const zatCheck = $("#zat_aktif");
            console.log(zatCheck.is(':checked'));

            const term = params.term.toLowerCase();
            const text = data.text.toLowerCase();
            const $el = $(data.element);
            const zat = $el.data('zat')?.toString().toLowerCase();

            if (zatCheck.is(':checked')) {
                if (zat && zat.includes(term)) {
                    return data;
                }
            } else {
                if (text.includes(term)) {
                    return data;
                }
            }

            return null;
        }

        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });

            $('#cppt_gudang_id').select2({
                placeholder: 'Pilih Gudang',
            });

            $("#cppt_barang_id").select2({
                matcher: obatMatcher,
                placeholder: 'Pilih Obat'
            })

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });

            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
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
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
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
    @include('pages.simrs.erm.partials.action-js.cppt')
@endsection
