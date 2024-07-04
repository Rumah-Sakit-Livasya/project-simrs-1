@extends('inc.layout')
@section('title', 'User Akses')
@section('extended-css')
    <style>
        .custom-control-input:checked~.custom-control-label::before {
            background: #fd3995;
            border-color: #cc2875;
        }
    </style>
@endsection
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
                            <div class="card-columns">
                                @foreach ($roles as $row)
                                    <div class="card border">
                                        <!-- notice the additions of utility paddings and display properties on .card-header -->
                                        <div class="card-header bg-primary-500 d-flex pr-2 align-items-center flex-wrap">
                                            <!-- we wrap header title inside a span tag with utility padding -->
                                            <div class="card-title font-weight-bold">{{ $row->name }}</div>
                                            <div class="custom-control d-flex custom-switch ml-auto">
                                                <input id="roles-{{ $row->id }}" type="checkbox"
                                                    class="custom-control-input" checked="checked">
                                                <label class="custom-control-label fw-500"
                                                    for="roles-{{ $row->id }}"></label>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex pr-2 align-items-center flex-wrap">
                                            @if ($row->permissions->count() > 0)
                                                @foreach ($row->permissions as $col)
                                                    <div class="permissions mr-1">
                                                        <span>{{ $col->name }}</span><br>
                                                    </div>
                                                    <div class="custom-control d-flex custom-switch">
                                                        <input id="permissions-{{ $col->id }}" type="checkbox"
                                                            class="custom-control-input" checked="checked">
                                                        <label class="custom-control-label fw-500"
                                                            for="permissions-{{ $col->id }}"></label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span>No permissions assigned</span>
                                            @endif
                                        </div>
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
