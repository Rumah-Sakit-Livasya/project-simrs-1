@extends('inc.layout')
@section('title', 'User Akses')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar User Akses
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                @foreach ($roles as $row)
                                    <div class="col-md-4">
                                        <h3>{{ $row->name }}</h3>
                                        @if ($row->permissions->count() > 0)
                                            @foreach ($row->permissions as $col)
                                                <span>{{ $col->name }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script></script>
@endsection
