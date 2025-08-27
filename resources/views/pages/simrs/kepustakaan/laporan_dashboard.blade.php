@extends('inc.layout')
@section('extended-css')
    {{-- Tambahkan CSS untuk Select2 jika belum ada di layout utama --}}
    <link rel="stylesheet" type="text/css" href="/css/formplugins/select2/select2.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-chart-bar'></i> Dasbor Laporan Bulanan Unit
            </h1>
        </div>

        {{-- Panel Filter --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Filter Periode
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form method="GET" action="{{ route('kepustakaan.laporan.dashboard') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="month">Bulan</label>
                                            {{-- UPDATE: Tambahkan class "select2" --}}
                                            <select class="form-control select2" id="month" name="month">
                                                @for ($m = 1; $m <= 12; $m++)
                                                    <option value="{{ $m }}"
                                                        {{ $selectedMonth == $m ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="year">Tahun</label>
                                            {{-- UPDATE: Tambahkan class "select2" --}}
                                            <select class="form-control select2" id="year" name="year">
                                                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                    <option value="{{ $y }}"
                                                        {{ $selectedYear == $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Tabel Status --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Status Laporan - <span
                                class="fw-700">{{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }}
                                {{ $selectedYear }}</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Unit / Organisasi</th>
                                            <th>Status</th>
                                            <th>Nama File</th>
                                            <th>Tanggal Upload</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reportStatus as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item['organization_name'] }}</td>
                                                <td>
                                                    @if ($item['status'])
                                                        <span class="badge badge-success">Sudah Mengirim</span>
                                                    @else
                                                        <span class="badge badge-danger">Belum Mengirim</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item['submission'])
                                                        {{ $item['submission']->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item['submission'])
                                                        {{ \Carbon\Carbon::parse($item['submission']->created_at)->format('d M Y, H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data organisasi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- UPDATE: Tambahkan script untuk Select2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada elemen dengan kelas .select2
            $('.select2').select2();
        });
    </script>
@endsection
