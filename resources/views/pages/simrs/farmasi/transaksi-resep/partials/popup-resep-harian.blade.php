@extends('inc.layout-no-side')
@section('title', 'Resep Harian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.farmasi.transaksi-resep.partials.popup-resep-harian-form')
        @include('pages.simrs.farmasi.transaksi-resep.partials.popup-resep-harian-datatable')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
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
    </main>
@endsection
