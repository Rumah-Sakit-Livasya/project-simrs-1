@extends('inc.layout')
@section('title', 'Farmasi Report: Stock Status')
@section('content')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
            max-height:
        }

        .loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">

        @include('pages.simrs.farmasi.report.partials.stock-detail-form')

        @include('pages.simrs.farmasi.report.partials.stock-detail-datatable')
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- Datepicker Range --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        $(document).ready(function() {
            $(".select2").select2();


            /// Get the current date and time
            var today = new Date();

            // Format it as "YYYY-MM-DD"
            var formattedToday = today.getFullYear() + '-' +
                ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
                ('0' + today.getDate()).slice(-2) + ' ' +
                ('0' + today.getHours()).slice(-2) + ':' +
                ('0' + today.getMinutes()).slice(-2) + ':' +
                ('0' + today.getSeconds()).slice(-2);

            // Set the default date for the datepicker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                startDate: moment(today).startOf('month').format('YYYY-MM-DD'),
                endDate: moment(today).format('YYYY-MM-DD'),
                // timePicker: true, // Enable time selection
                // timePicker24Hour: true, // 24-hour format
                // timePickerSeconds: true, // Include seconds in time selection
                locale: {
                    format: 'YYYY-MM-DD' // Display format for the picker
                }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') +
                    ' to ' + end.format('YYYY-MM-DD'));
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
    <script src="{{ asset('js/simrs/farmasi/revaluasi-stock/stock-opname/report/stock-detail/table.js') }}">
    </script>
    <script src="{{ asset('js/simrs/farmasi/revaluasi-stock/stock-opname/report/stock-detail/api.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('js/simrs/farmasi/revaluasi-stock/stock-opname/report/stock-detail/main.js') }}">
    </script>
@endsection
