@php
    use Carbon\Carbon;
@endphp


<div class="buton my-5" id="modalFooter">
    <button type="button" class="btn btn-primary"
        onclick="exportToPDF('printableArea', 'laporan-pemasukan-dan-pengeluaran', 'this')">
        <span class="fal fa-upload mr-1"></span>
        Export PDF
    </button>
    <button type="button" onclick="printDiv('printableArea')" class="btn btn-primary" id="btn-print">
        <span class="fal fa-print mr-1"></span>
        Print
    </button>
</div>
<div class="row">
    <div class="col-lg-6">
        <table class="table m-0">
            <tbody>
                <tr>
                    <th scope="row">DARI TANGGAL</th>
                    <td> :
                        @isset($startDate)
                            {{ Carbon::parse($startDate)->translatedFormat('d F Y') }}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <th scope="row">SAMPAI TANGGAL</th>
                    <td> :
                        @isset($endDate)
                            {{ Carbon::parse($endDate)->translatedFormat('d F Y') }}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <th scope="row">TAHUN</th>
                    <td> :
                        @isset($tahun)
                            {{ strtoupper($tahun) }}
                        @else
                            SEMUA TAHUN
                        @endisset
                    </td>
                </tr>
                <tr>
                    <th scope="row">BULAN</th>
                    <td> :
                        @isset($bulan)
                            {{ strtoupper($bulan) }}
                        @else
                            SEMUA BULAN
                        @endisset
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row mt-5">
    <div class="col">
        <table class="table m-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPemasukan = 0;
                    $totalPengeluaran = 0;
                    $totalKeseluruhan = 0;
                @endphp
                @foreach ($transaksi as $t)
                    @php
                        $totalKeseluruhan += $t->nominal;

                        if ($t->type_id === 1) {
                            $totalPemasukan += $t->nominal;
                        } elseif ($t->type_id === 2) {
                            $totalPengeluaran += $t->nominal;
                        }
                    @endphp
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            {{ Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}
                        </td>
                        <td>{{ $t->category->nama }}</td>
                        <td>{{ $t->keterangan }}</td>
                        <td>{{ $t->type_id === 1 ? number_format($t->nominal) : '' }}</td>
                        <td>{{ $t->type_id === 2 ? number_format($t->nominal) : '' }}</td>
                    </tr>
                @endforeach
                <tr class="table-bordered">
                    <th colspan="4">
                        <div class="text-right"><strong>TOTAL</strong></div>
                    </th>
                    <td><strong class="text-success">+{{ number_format($totalPemasukan) }}</strong>
                    </td>
                    <td><strong class="text-danger">-{{ number_format($totalPengeluaran) }}</strong>
                    </td>
                </tr>
                <tr>
                    <th colspan="4"></th>
                    <td colspan="2" class="bg-primary">
                        <h3 class="text-white text-center font-weight-bold">
                            Rp. {{ number_format($totalKeseluruhan) }},-</h3>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
