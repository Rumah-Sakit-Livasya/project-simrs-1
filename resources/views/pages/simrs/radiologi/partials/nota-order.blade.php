<html>

<head>
    <title>Print</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" media="screen, print" href="/css/print.css">
    <style type="text/css" media="all" id="less:testing-include-styles-print">
        /*  Document Reset */
        @charset "utf-8";

        /*html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td { margin: 0; padding: 0; border: 0; outline: 0; font-size: 100%; vertical-align: baseline; background: transparent; }*/
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        font,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tfoot,
        thead,
        th {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        :focus {
            outline: 0;
        }

        ins {
            text-decoration: none;
        }

        del {
            text-decoration: line-through;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans';
            /*Arial, Helvetica, sans-serif;*/

            font-size: .75em;
        }

        h1,
        h2,
        h3 {
            margin: 5px 0;
            padding: 5px 0;
            font-weight: normal;
            clear: both;
            overflow: hidden;
        }

        h1.bdr,
        h2.bdr,
        h3.bdr {
            border-bottom: 2px solid #CCCCCC;
        }

        h1 span,
        h2 span,
        h3 span {
            display: block;
            margin: 5px 0;
        }

        h1 span.rgt,
        h2 span.rgt,
        h3 span.rgt {
            border: 1px solid #CCCCCC;
            float: right;
        }

        h1 span.rgt span,
        h2 span.rgt span,
        h3 span.rgt span {
            font-size: 1em !important;
            display: inline-block;
            margin: 0;
            padding: 8px 10px;
        }

        h1 span.rgt span.til,
        h2 span.rgt span.til,
        h3 span.rgt span.til {
            background: #CCCCCC;
        }

        h1.ctr,
        h2.ctr,
        h3.ctr {
            text-align: center;
        }

        h1.nul,
        h2.nul,
        h3.nul {
            padding: 0;
            margin: 0;
        }

        h1 {
            font-size: 3em;
        }

        h1 span {
            font-size: .4em;
        }

        h2 {
            font-size: 2em;
        }

        h2 span {
            font-size: .5em;
        }

        h3 {
            font-size: 1.5em;
        }

        h3 span {
            font-size: .6em;
        }

        #functions {
            background: #EDEDED;
            border-bottom: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
            padding: 10px 5px;
            position: relative;
            overflow: hidden;
            top: 0;
            width: 100%;
        }

        #functions ul li {
            display: inline-block;
            margin: 2px 5px 2px 0;
        }

        #functions ul li a {
            background: #FFFFFF;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
            color: #000000;
            /*text-shadow: 0 0 2px rgba(0,0,0,.3); */

        }

        #previews {
            margin: 20px;
            margin-top: 50px;
            padding: 0;
            overflow: hidden;
            clear: both;
        }

        #previews.bdr {
            border: 1px solid #CCCCCC;
        }

        #lhd {
            border-bottom: 3px double #CCCCCC;
            clear: both;
            overflow: hidden;
            margin: 1px 0;
        }

        #lhd .lgo {
            float: left;
            width: 45px;
            height: 75px;
            margin: 5px 10px 5px 0;
        }

        #lhd .lgo img {
            height: 50px;
        }

        #lhd .ttl {
            float: left;
            line-height: 1.2em;
            padding: 15px 0 10px 0;
        }

        #lhd .ttl span {
            display: block;
        }

        #lhd .ttl span.nme {
            font-weight: bold;
            font-size: 2em;
            margin-bottom: 5px;
        }

        #lhd .lgo_persen {
            float: left;
            width: 8%;
            height: 75px;
            margin: 5px 10px 5px 0;
        }

        #lhd .lgo_persen img {
            height: 70px;
        }

        #lhd .ttl_persen {
            float: left;
            width: 10%;
            line-height: 1.2em;
            padding: 15px 0 10px 0;
            text-align: center;
        }

        #lhd .ttl_persen span {
            display: block;
        }

        #lhd .ttl_persen span.nme {
            font-weight: bold;
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        #hhr {
            width: 50%;
        }

        #hhr.ful {
            width: 100%;
        }

        #hhr.pad1 {
            padding: 0 20px;
        }

        #hhr.hlf {
            float: left;
        }

        #hhr.hrt {
            float: right;
        }

        #hhr ul {
            margin: 10px 0;
            padding: 0;
        }

        #hhr ul li {
            margin: 5px 0;
        }

        #hhr ul li div {
            display: inline-block;
        }

        #hhr ul li span {
            display: inline-block;
            width: 120px;
            vertical-align: text-top;
        }

        #ftr {
            overflow: hidden;
            margin: 10px 0;
        }

        #ftr.tp1 .wpr {
            width: 100%;
        }

        #ftr.tp2 .wpr {
            width: 50%;
        }

        #ftr.tp3 .wpr {
            width: 33.33333333333333%;
        }

        #ftr.tp4 .wpr {
            width: 25%;
        }

        #ftr.tp5 .wpr {
            width: 20%;
        }

        #ftr.tp6 .wpr {
            width: 16.66666666666667%;
        }

        #ftr .wpr {
            float: left;
        }

        #ftr .wpr div {
            float: none;
            padding: 10px;
            margin: 0 5px;
        }

        #ftr .wpr div.dot {
            /* This For Sample */

            text-align: center;
            text-transform: uppercase;
            border: 1px dotted #ccc;
        }

        #ftr .wpr div.npm {
            margin: 0;
            padding: 0;
        }

        #ftr .wpr div p {
            display: block;
            margin: 10px 0;
            line-height: 1.4em;
        }

        #ftr .wpr.rt {
            float: right;
        }

        table {
            margin: 0;
            padding: 0;
            border-collapse: collapse;
        }

        table table {
            font-size: 1em !important;
        }

        table.bdr1 {
            border: 1px solid #CCCCCC !important;
        }

        table.bdr1 th {
            border: 1px solid #CCCCCC !important;
        }

        table.bdr1 td {
            border: 1px solid #CCCCCC !important;
        }

        table.bdr2 {
            border: none !important;
        }

        table.bdr2 tbody {
            border-top: 1px solid #CCCCCC !important;
        }

        table.bdr2 th {
            border: none !important;
            border: 1px solid #CCCCCC !important;
        }

        table.bdr2 th:first-child {
            border-left: none !important;
        }

        table.bdr2 th:last-child {
            border-right: none !important;
        }

        table.bdr2 td {
            border: none !important;
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr3 {
            border: none !important;
        }

        table.bdr3 tbody {
            border-top: 1px solid #CCCCCC !important;
        }

        table.bdr3 tr:last-child {
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr3 th {
            border: none !important;
            border: 1px solid #CCCCCC !important;
        }

        table.bdr3 th:first-child {
            border-left: none !important;
        }

        table.bdr3 th:last-child {
            border-right: none !important;
        }

        table.bdr3 td {
            border: none !important;
            border-right: 1px solid #CCCCCC !important;
        }

        table.bdr3 td:first-child {
            border-left: none !important;
        }

        table.bdr3 td:last-child {
            border-right: none !important;
        }

        table.bdr4 {
            border: none !important;
        }

        table.bdr4 tbody {
            border-top: 1px solid #CCCCCC !important;
        }

        table.bdr4 tr:last-child {
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr4 th {
            border: none !important;
            border: 1px solid #CCCCCC !important;
        }

        table.bdr4 th:first-child {
            border-left: none !important;
        }

        table.bdr4 th:last-child {
            border-right: none !important;
        }

        table.bdr4 td {
            border: none !important;
            border-right: 1px solid #CCCCCC !important;
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr4 td:first-child {
            border-left: none !important;
        }

        table.bdr4 td:last-child {
            border-right: none !important;
        }

        table.pad th {
            padding: 8px;
        }

        table.pad td {
            padding: 4px;
        }

        table.sml {
            font-size: .85em;
        }

        table th {
            background: #D9D9D9;
            padding: 0;
            vertical-align: middle;
        }

        table td {
            padding: 0;
            vertical-align: text-top;
            line-height: 1.2em;
        }

        table td span {
            color: #414141;
            display: block;
            font-size: .8em;
        }

        .watermark {
            background-image: url({{ asset('/img/logo.png') }});
            background-position: center center;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.1;
            position: absolute;
            min-height: 80%;
            min-width: 80%;
        }

        @media print {
            #functions {
                display: none;
            }

            #preview {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- start print_penjualan.html -->
    <style type="text/css">
        table td {
            line-height: .8em;
        }

        table.pad th {
            padding: 5px;
        }

        #previews {
            font-family: Arial, Gadget, sans-serif;
            width: 95%;
            /*margin-left:5%; */
        }

        .hlf {
            font-weight: 0px;
        }
    </style>
    <!-- B: Functions -->
    <div id="functions">
        <ul>
            <li><a href="#" onclick="window.print();">Print</a></li>
            <li><a href="index-fancy.html" onclick="window.close()">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews" style="margin-top:0px;">

        <div id="lhd">
            <div class="lgo"><img src="{{ asset('/img/logo.png') }}"></div>
            <div class="ttl">
                <span class="nme">Rumah Sakit Livasya</span>
                <span>Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka, Kab. Majalengka - Jawa Barat</span>
                <span>Phone : 081211151300</span>
            </div>
        </div>

        <h2 class="bdr" style="padding:0px;">
            <span class="rgt"><span class="til">NO ORDER #</span><span>{{ $order->no_order }}</span></span>
            Nota Radiologi
            <span>Tanggal : {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
            </span>
        </h2>

        @if ($order->registration)
            <div id="hhr" class="hlf" style="font-size:.9em; margin-bottom:0px;">
                <ul>
                    <li><span>Nama</span>: {{ $order->registration->patient->name }} </li>
                    <li><span>Jenis Kelamin</span>: {{ $order->registration->patient->gender }}</li>
                    <li><span>Tgl Lahir / Umur</span>: {{ $order->registration->patient->date_of_birth }} /
                        {{ displayAge($order->registration->patient->date_of_birth) }}</li>
                    <li><span>No telp</span>: {{ $order->registration->patient->mobile_phone_number }}</li>
                    <li><span>Alamat</span>: {{ $order->registration->patient->address }}</li>
                </ul>
            </div>
            <div id="hhr" class="hrt" style="font-size:.9em; margin-bottom:0px;">
                <ul>
                    <li><span>No RM / No Registrasi</span>: {{ $order->registration->patient->medical_record_number }}
                        /
                        {{ $order->registration->registration_number }}</li>
                    <li><span>Dokter</span>: {{ $order->doctor->employee->fullname }}</li>
                    <li><span>Disiapkan Oleh</span>: {{ $order->user->employee->fullname }}</li>
                    <li><span>Poly/Ruang</span>: {{ $order->registration->poliklinik }}</li>
                    <li><span>Penjamin</span>: {{ $order->registration->penjamin->nama_perusahaan }}</li>
                </ul>
            </div>
        @else
            <div id="hhr" class="hlf" style="font-size:.9em; margin-bottom:0px;">
                <ul>
                    <li><span>Nama</span>: {{ $order->registration_otc->nama_pasien }} </li>
                    <li><span>Jenis Kelamin</span>: {{ $order->registration_otc->jenis_kelamin }}</li>
                    <li><span>Tgl Lahir / Umur</span>: {{ $order->registration_otc->date_of_birth }} /
                        {{ displayAge($order->registration_otc->date_of_birth) }}</li>
                    <li><span>No telp</span>: {{ $order->registration_otc->no_telp }}</li>
                    <li><span>Alamat</span>: {{ $order->registration_otc->alamat }}</li>
                </ul>
            </div>
            <div id="hhr" class="hrt" style="font-size:.9em; margin-bottom:0px;">
                <ul>
                    <li><span>No RM / No Registrasi</span>: OTC / {{ $order->registration_otc->registration_number }}
                    </li>
                    <li><span>Dokter</span>: {{ $order->doctor->employee->fullname }}</li>
                    <li><span>Disiapkan Oleh</span>: {{ $order->user->employee->fullname }}</li>
                    <li><span>Poly/Ruang</span>: {{ $order->registration_otc->poly_ruang ?? ' - ' }}</li>
                    <li><span>Penjamin</span>: {{ $order->registration_otc->penjamin->nama_perusahaan }}</li>
                </ul>
            </div>
        @endif

        <table width="100%" class="bdr4 pad" style="margin-top:0px;">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Item</th>
                    <th width="25%">@Harga</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($order->order_parameter_radiologi as $orderParameter)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $orderParameter->parameter_radiologi->parameter }}</td>
                        <td align="right">
                            {{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($orderParameter->nominal_rupiah, 'IDR') }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" align="right"><strong>TOTAL</strong></td>
                    <td align="right">
                        <strong>{{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($order->order_parameter_radiologi->sum('nominal_rupiah'), 'IDR') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <!--end print_penjualan.html -->


</body>

</html>
