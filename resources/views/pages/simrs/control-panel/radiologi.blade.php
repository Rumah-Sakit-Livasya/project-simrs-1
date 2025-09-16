@extends('inc.layout')
@section('title', 'Migrasi Tarif Radiologi')

@section('content')
    <div class="container" style="padding-top: 20px;">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Form Download --}}
        <div class="card">
            <div class="card-header">Download Template Tarif Radiologi</div>
            <div class="card-body">
                <form action="{{ route('radiologi.export') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="grup_penjamin_id">Group Penjamin</label>
                        <select name="grup_penjamin_id" class="form-control">
                            @foreach ($grupPenjamins as $gp)
                                <option value="{{ $gp->id }}">{{ $gp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Download Template</button>
                </form>
            </div>
        </div>

        <br>

        {{-- Form Upload --}}
        <div class="card">
            <div class="card-header">Upload File Tarif Radiologi</div>
            <div class="card-body">
                <form action="{{ route('radiologi.import') }}" method="POST" enctype="multipart/form-data" id="fupload">
                    @csrf
                    <div class="form-group">
                        <label for="file">Pilih File (.xlsx, .csv)</label>
                        <input type="file" name="file" class="form-control" id="file">
                    </div>
                    <button type="button" class="btn btn-success" onclick="cek_upload(this);">Upload Data</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script type="text/javascript">
        function cek_upload(obj) {
            if (document.getElementById('file').value == '') {
                alert('File belum dipilih!');
                return false;
            }
            obj.disabled = true;
            obj.innerHTML = 'Mohon tunggu...';
            document.getElementById('fupload').submit();
        }
    </script>
@endsection
