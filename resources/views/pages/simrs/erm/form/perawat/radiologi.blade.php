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

                <div class="panel-hdr border-top">
                    <h2 class="text-light">
                        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
                        <span class="text-primary">Radiologi</span>
                    </h2>
                </div>
                <div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div id="panel-1" class="panel">
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        <!-- datatable start -->
                                        <table id="dt-basic-example"
                                            class="table table-bordered table-hover table-striped w-100">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Detail</th>
                                                    <th>Tanggal</th>
                                                    <th>No. Registrasi</th>
                                                    <th>No. Order</th>
                                                    <th>Poly / Ruang</th>
                                                    <th>Penjamin</th>
                                                    <th>Dokter</th>
                                                    <th>Status Isi Hasil</th>
                                                    <th>Status Billed</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($radiologiOrders as $order)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                data-bs-placement="top" data-bs-toggle="popover"
                                                                data-bs-title="Detail Order Radiologi" data-bs-html="true"
                                                                data-bs-content-id="popover-content-{{ $order->id }}">
                                                                <i class="fas fa-list text-light"
                                                                    style="transform: scale(1.8)"></i>
                                                            </button>
                                                            <div class="display-none"
                                                                id="popover-content-{{ $order->id }}">
                                                                @include(
                                                                    'pages.simrs.pendaftaran.partials.detail-order-radiologi',
                                                                    ['order' => $order]
                                                                )
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $order->order_date }}
                                                        </td>
                                                        <td>
                                                            {{ $order->registration->id }}
                                                        </td>
                                                        <td>
                                                            {{ $order->no_order }}
                                                        </td>
                                                        <td>
                                                            {{ $order->registration->poliklinik }}
                                                        </td>
                                                        <td>
                                                            {{ $order->registration->patient->penjamin->name ?? '-' }}
                                                        </td>
                                                        <td>
                                                            {{ $order->doctor->employee->fullname }}
                                                        </td>
                                                        <td>
                                                            {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                                        </td>
                                                        <td>
                                                            {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                                        </td>
                                                        <td> - </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Detail</th>
                                                    <th>Tanggal</th>
                                                    <th>No. Registrasi</th>
                                                    <th>No. Order</th>
                                                    <th>Poly / Ruang</th>
                                                    <th>Penjamin</th>
                                                    <th>Dokter</th>
                                                    <th>Status Isi Hasil</th>
                                                    <th>Status Billed</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <!-- datatable end -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @include('pages.simrs.pendaftaran.partials.order-radiologi')


            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    @yield('script-radiologi')

    <script>
        let listPopoverRadiologi = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        listPopoverRadiologi.map((el) => {
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
