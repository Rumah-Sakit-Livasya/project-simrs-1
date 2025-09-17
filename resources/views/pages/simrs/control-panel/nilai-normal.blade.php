@extends('inc.layout')
@section('title', 'Migrasi Nilai Normal Laboratorium')

@section('content')
    <main id="js-page-content" role="main" class="page-content" @style('margin-top: 5rem;')>
        <div class="container" style="padding-top: 20px;">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">Import / Export Nilai Normal</div>
                <div class="card-body">
                    <form action="{{ route('nilai.normal.import') }}" method="POST" enctype="multipart/form-data"
                        id="fupload">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload File Excel</label>
                            <input type="file" name="file" class="form-control" id="file" required>
                        </div>
                        <button type="button" class="btn btn-success" onclick="cek_upload(this);">Import</button>
                        <a href="{{ route('nilai.normal.export') }}" class="btn btn-primary">Download
                            Template</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        function cek_upload(obj) {
            if (!document.getElementById('file').value) {
                alert('File belum dipilih!');
                return false;
            }
            obj.disabled = true;
            obj.innerHTML = 'Mohon tunggu...';
            document.getElementById('fupload').submit();
        }
    </script>
@endsection
