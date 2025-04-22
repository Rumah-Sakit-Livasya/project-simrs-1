@extends('inc.layout')
@section('title', 'Tim Livasya')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel-container show">
            <div class="panel-content">
                <input type="text" id="search-input" class="form-control mb-3"
                    placeholder="Cari organisasi atau karyawan...">
                <div id="team-content">
                    @foreach ($organizations as $organization)
                        @include('pages.pegawai.team.partials.organization-block', [
                            'organization' => $organization,
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        $(document).ready(function() {
            $('#search-input').on('keyup', function() {
                let q = $(this).val();
                $.ajax({
                    url: "{{ route('team.search') }}",
                    data: {
                        q: q
                    },
                    success: function(response) {
                        $('#team-content').html(response.html);
                    }
                });
            });
        });
    </script>
@endsection
