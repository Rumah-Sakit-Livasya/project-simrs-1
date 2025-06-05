@extends('inc.layout-no-side')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @foreach ($laporan as $orgName => $userGroups)
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ $orgName }}</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($userGroups as $userName => $items)
                                <div class="mb-3">
                                    <h6 class="text-secondary">{{ $userName }}</h6>
                                    @foreach ($items as $l)
                                        <div class="border-bottom pb-2 mb-2">
                                            <p class="mb-1">{{ $l->kegiatan }}</p>
                                            <small class="text-muted">{{ $l->created_at->format('d-m-Y H:i') }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
