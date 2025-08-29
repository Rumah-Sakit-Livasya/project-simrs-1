@extends('pages.simrs.persalinan.laporan.template_print')
@section('title', 'Laporan Rekap Kunjungan Persalinan')
@section('content')
    <div class="report-header">
        <div class="report-title">REKAP KUNJUNGAN PERSALINAN BERDASARKAN TINDAKAN</div>
    </div>
    <table style="width: 50%; border: none; font-size: 10pt; margin-bottom: 20px;">
        <tbody>
            {{-- [PERBAIKAN] Menampilkan periode, bukan tahun --}}
            <tr style="border: none;">
                <td style="border: none; padding: 2px; width: 150px;">Periode</td>
                <td style="border: none; padding: 2px;">: {{ $filters['period_start'] }} s/d {{ $filters['period_end'] }}
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Tipe Rawat</td>
                <td style="border: none; padding: 2px;">: {{ $filters['tipe_rawat'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Kategori</td>
                <td style="border: none; padding: 2px;">: {{ $filters['kategori'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Kelas Rawat</td>
                <td style="border: none; padding: 2px;">: {{ $filters['kelas_rawat'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Dokter/Bidan</td>
                <td style="border: none; padding: 2px;">: {{ $filters['dokter'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Penjamin</td>
                <td style="border: none; padding: 2px;">: {{ $filters['penjamin'] }}</td>
            </tr>
        </tbody>
    </table>
    @if ($error)
        <div style="color: red; text-align: center; margin: 20px;"><strong>Error:</strong> {{ $error }}</div>
    @endif
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tindakan</th>
                <th colspan="12">Bulan</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Agu</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $result)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $result->nama_persalinan }}</td>
                    <td style="text-align: center;">{{ $result->jan > 0 ? $result->jan : '' }}</td>
                    <td style="text-align: center;">{{ $result->feb > 0 ? $result->feb : '' }}</td>
                    <td style="text-align: center;">{{ $result->mar > 0 ? $result->mar : '' }}</td>
                    <td style="text-align: center;">{{ $result->apr > 0 ? $result->apr : '' }}</td>
                    <td style="text-align: center;">{{ $result->mei > 0 ? $result->mei : '' }}</td>
                    <td style="text-align: center;">{{ $result->jun > 0 ? $result->jun : '' }}</td>
                    <td style="text-align: center;">{{ $result->jul > 0 ? $result->jul : '' }}</td>
                    <td style="text-align: center;">{{ $result->agu > 0 ? $result->agu : '' }}</td>
                    <td style="text-align: center;">{{ $result->sep > 0 ? $result->sep : '' }}</td>
                    <td style="text-align: center;">{{ $result->okt > 0 ? $result->okt : '' }}</td>
                    <td style="text-align: center;">{{ $result->nov > 0 ? $result->nov : '' }}</td>
                    <td style="text-align: center;">{{ $result->des > 0 ? $result->des : '' }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $result->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" style="text-align: center; padding: 20px;">Data tidak ditemukan!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
