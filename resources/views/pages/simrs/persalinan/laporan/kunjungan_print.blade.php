@extends('pages.simrs.operasi.laporan.template_print') {{-- Menggunakan layout print OK --}}
@section('title', 'LAPORAN ORDER PASIEN PERSALINAN')
@section('content')
    <div class="report-header">
        <div class="report-title">LAPORAN ORDER PASIEN PERSALINAN</div>
        <div class="report-period">PERIODE: {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') }}</div>
        <div class="report-info">Tanggal Cetak: {{ $print_date }}</div>
    </div>
    @if ($error)
        <div style="color: red; text-align: center; margin: 20px;"><strong>Error:</strong> {{ $error }}</div>
    @endif
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl. Reg</th>
                <th>No. Reg</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>JK</th>
                <th>Umur</th>
                <th>Alamat</th>
                <th>Poli</th>
                <th>Dokter/Bidan</th>
                <th>Penjamin</th>
                <th>Perujuk</th>
                <th>Tindakan</th>
                <th>dr. Resusitator</th>
                <th>dr. Anastesi</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order['tanggal_registrasi'] }}</td>
                    <td>{{ $order['registration_number'] }}</td>
                    <td>{{ $order['medical_record_number'] }}</td>
                    <td>{{ $order['patient_name'] }}</td>
                    <td>{{ $order['gender'] }}</td>
                    <td>{{ $order['age'] }}</td>
                    <td>{{ $order['address'] }}</td>
                    <td>{{ $order['poli'] }}</td>
                    <td>{{ $order['dokter'] }}</td>
                    <td>{{ $order['penjamin'] }}</td>
                    <td>{{ $order['perujuk'] }}</td>
                    <td>{{ $order['tindakan'] }}</td>
                    <td>{{ $order['dr_resusitator'] }}</td>
                    <td>{{ $order['dr_anestesi'] }}</td>
                    <td>{{ $order['petugas'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" style="text-align: center;">Data tidak ditemukan untuk periode yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
