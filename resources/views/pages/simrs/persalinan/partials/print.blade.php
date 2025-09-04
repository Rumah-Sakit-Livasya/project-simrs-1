<!DOCTYPE HTML>
<html>

<head>
    <title>Cetak Surat Keterangan Lahir</title>
    {{-- Anda bisa menyertakan CSS jika diperlukan, tapi untuk print biasanya inline lebih aman --}}
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .print-container {
            width: 18.5cm;
            margin: 0 auto;
        }

        .cleared td {
            padding: 2px;
            vertical-align: top;
        }

        .print_function {
            display: none;
        }

        /* Sembunyikan tombol saat print */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align:center; padding: 10px; background-color: #f0f0f0;">
        <button onclick="window.print()">Print</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="print-container" style="margin-top: 1.5cm;">
        {{-- KOP SURAT --}}
        <table width="90%" style="border-bottom:double #000 2px; margin:auto">
            <tr>
                <td><img style="height:100px; padding:10px; padding-right:0" src="{{ asset('img/logo.png') }}"></td>
                {{-- Ganti dengan path logo Anda --}}
                <td>
                    <div style="text-align:left; font-size:1.2em; font-weight:bold">
                        <div style="font-size:1.4em; clear:both; width:100%">RUMAH SAKIT LIVASYA</div>
                        <span>Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>Telp. : 081211151300</span>
                    </div>
                </td>
            </tr>
        </table>

        {{-- JUDUL SURAT --}}
        <table width="100%" style="text-align: center; margin-top: 10px;">
            <tr>
                <td>
                    <p style="font-size: 1.6em; font-weight: bold; margin:0;">SURAT KETERANGAN LAHIR</p>
                    <p style="font-size: 1.2em; font-weight: bold; font-style: italic; margin:0">BIRTH CERTIFICATE</p>
                    <div style="font-size: 1.3em; font-weight: bold;">No. : {{ $bayi->no_label ?? '________________' }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- KONTEN UTAMA --}}
        <div style="width: 17cm; margin: 15px auto 5px auto; font-size:1.2em;">
            <table width="100%" class="cleared">
                <tr>
                    <td width="45%">Yang bertanda tangan dibawah ini <em>(The undersigned)</em></td>
                    <td width="5px">:</td>
                    <td>{{ optional($bayi->doctor->employee)->fullname ?? '____________' }}</td>
                </tr>
                <tr>
                    <td>Menerangkan bahwa telah lahir seorang bayi <em>(Herewith certify the birth of a baby)</em></td>
                    <td>:</td>
                    <td>{{ $bayi->jenis_kelamin }}
                        <em>({{ $bayi->jenis_kelamin == 'Laki-laki' ? 'Male' : 'Female' }})</em>
                    </td>
                </tr>
                <tr>
                    <td>Nama Bayi <em>(Baby's Name)</em></td>
                    <td>:</td>
                    <td><b>{{ $bayi->nama_bayi }}</b></td>
                </tr>
                <tr>
                    <td>Nama Ibu <em>(Mother's Name)</em></td>
                    <td>:</td>
                    <td>{{ optional(optional($bayi->registration)->patient)->name ?? 'Data tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <td>Alamat Rumah <em>(Home Address)</em></td>
                    <td>:</td>
                    <td>{{ optional(optional($bayi->registration)->patient)->address ?? 'Data tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 10px;"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table width="100%">
                            @php
                                $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                            @endphp
                            <tr>
                                <td>Pada / on</td>
                                <td width="1%">:</td>
                                <td>Hari / day</td>
                                <td width="1%">:</td>
                                <td width="25%">{{ $tglLahir->isoFormat('dddd') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>Tanggal / date</td>
                                <td>:</td>
                                <td>{{ $tglLahir->isoFormat('D MMMM YYYY') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>Jam / time</td>
                                <td>:</td>
                                <td>{{ $tglLahir->format('H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td>Biodata</td>
                                <td>:</td>
                                <td>1. Berat / weight</td>
                                <td>:</td>
                                <td>{{ $bayi->berat }} Gr</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>2. Panjang / length</td>
                                <td>:</td>
                                <td>{{ $bayi->panjang }} Cm</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>3. Kelahiran dengan Tindakan <em>(Pathological labor)</em></td>
                                <td>:</td>
                                <td>{{ $bayi->kelahiran_dgn_tindakan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>4. Kembar <em>(Multiple labor)</em></td>
                                <td>:</td>
                                <td>{{ $bayi->jenis_kelahiran }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>5. Anak ke</td>
                                <td>:</td>
                                <td>{{ $bayi->kelahiran_ke ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="margin-top: 50px;">
                            <tr>
                                <td>
                                    <div style="border:1px solid #000; padding: 8px; font-size: 0.8em;">
                                        <b>Perhatian:</b><br>
                                        Surat keterangan ini harus segera dilaporkan ke lurah atau administratif
                                        berwenang dalam waktu 14 (empat belas) hari sejak tanggal kelahiran bayi.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                    <td align="center" style="vertical-align: bottom;">
                        <table width="100%" style="margin-top: 25px;">
                            <tr>
                                <td align="center">Kab. Majalengka, {{ now()->isoFormat('D MMMM YYYY') }}</td>
                            </tr>
                            <tr>
                                <td height="75px"></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <b>({{ optional($bayi->doctor->employee)->fullname ?? '____________' }})</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        // Script untuk otomatis membuka dialog print saat halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>
